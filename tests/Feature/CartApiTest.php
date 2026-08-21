<?php

namespace Tests\Feature;

use App\Models\Dress;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression coverage for a set of cart bugs found while auditing the flow:
 * the update/remove JSON responses are what the cart page's JS now reads to
 * redraw a line's subtotal (it used to derive it from stale DOM text with a
 * formula that divided by zero on 2 -> 1), and the navbar badge relies on a
 * `cart-count` class + always-rendered markup that didn't previously exist.
 */
class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_creates_a_cart_entry(): void
    {
        $dress = Dress::factory()->create(['status' => 'active', 'sizes' => [34, 36], 'colors' => ['red', 'blue']]);

        $response = $this->postJson(route('api.cart.add'), [
            'product_id' => $dress->id,
            'quantity' => 2,
            'size' => '34',
            'color' => 'red',
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'cart_count' => 2]);
    }

    public function test_add_requires_a_size_when_the_dress_has_sizes_configured(): void
    {
        $dress = Dress::factory()->create(['status' => 'active', 'sizes' => [34, 36], 'colors' => []]);

        $response = $this->postJson(route('api.cart.add'), [
            'product_id' => $dress->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertStringContainsString('size', $response->json('message'));
    }

    public function test_add_requires_a_color_when_the_dress_has_colors_configured(): void
    {
        $dress = Dress::factory()->create(['status' => 'active', 'sizes' => [], 'colors' => ['red', 'blue']]);

        $response = $this->postJson(route('api.cart.add'), [
            'product_id' => $dress->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertStringContainsString('color', $response->json('message'));
    }

    public function test_add_succeeds_without_a_size_or_color_when_the_dress_has_none_configured(): void
    {
        $dress = Dress::factory()->create(['status' => 'active', 'sizes' => [], 'colors' => []]);

        $response = $this->postJson(route('api.cart.add'), [
            'product_id' => $dress->id,
            'quantity' => 1,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    public function test_add_rejects_a_size_that_is_not_offered_for_the_dress(): void
    {
        $dress = Dress::factory()->create(['status' => 'active', 'sizes' => [34, 36], 'colors' => ['red']]);

        $response = $this->postJson(route('api.cart.add'), [
            'product_id' => $dress->id,
            'quantity' => 1,
            'size' => '99',
            'color' => 'red',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_same_dress_with_different_size_or_color_creates_separate_cart_lines(): void
    {
        $dress = Dress::factory()->create(['status' => 'active', 'sizes' => [34, 36], 'colors' => ['red', 'blue']]);

        $this->postJson(route('api.cart.add'), ['product_id' => $dress->id, 'size' => '34', 'color' => 'red'])->assertOk();
        $response = $this->postJson(route('api.cart.add'), ['product_id' => $dress->id, 'size' => '36', 'color' => 'blue']);

        $response->assertOk();
        $response->assertJson(['success' => true, 'cart_count' => 2]);

        $items = $this->getJson(route('api.cart.index'))->json('items');
        $this->assertCount(2, $items);
    }

    public function test_add_validates_product_id_and_quantity(): void
    {
        $response = $this->postJson(route('api.cart.add'), [
            'product_id' => 'not-a-real-id',
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('product_id');
    }

    public function test_add_rejects_quantity_above_the_max(): void
    {
        $dress = Dress::factory()->create(['status' => 'active']);

        $response = $this->postJson(route('api.cart.add'), [
            'product_id' => $dress->id,
            'quantity' => CartService::MAX_QUANTITY_PER_ITEM + 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('quantity');
    }

    public function test_add_caps_cumulative_quantity_and_reports_it_in_the_response(): void
    {
        $dress = Dress::factory()->create(['status' => 'active', 'sizes' => [34], 'colors' => ['red']]);
        $max = CartService::MAX_QUANTITY_PER_ITEM;
        $payload = ['product_id' => $dress->id, 'quantity' => 1, 'size' => '34', 'color' => 'red'];

        // Repeated single-unit adds (the real "Add to Cart" button flow) must
        // not be able to push the total past the cap just because each
        // individual request is within the per-request limit.
        for ($i = 0; $i < $max; $i++) {
            $this->postJson(route('api.cart.add'), $payload)
                ->assertJson(['success' => true, 'capped' => false]);
        }

        $response = $this->postJson(route('api.cart.add'), $payload);

        $response->assertOk();
        $response->assertJson(['success' => true, 'capped' => true, 'cart_count' => $max]);
        $this->assertStringContainsString((string) $max, $response->json('message'));
    }

    public function test_update_rejects_quantity_above_the_max(): void
    {
        $dress = Dress::factory()->create(['status' => 'active']);
        app(CartService::class)->add($dress->id, 1);

        $response = $this->postJson(route('api.cart.update'), [
            'product_id' => $dress->id,
            'quantity' => CartService::MAX_QUANTITY_PER_ITEM + 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('quantity');
    }

    public function test_update_response_includes_the_matching_item_with_its_correct_subtotal(): void
    {
        $dress = Dress::factory()->create(['price' => 500, 'status' => 'active']);
        app(CartService::class)->add($dress->id, 1);

        $response = $this->postJson(route('api.cart.update'), [
            'product_id' => $dress->id,
            'quantity' => 3,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'cart_count' => 3]);

        $items = $response->json('items');
        $this->assertCount(1, $items);
        $this->assertSame($dress->id, $items[0]['dress']['id']);
        $this->assertEquals(1500, $items[0]['subtotal']);
    }

    public function test_update_to_zero_removes_the_item(): void
    {
        $dress = Dress::factory()->create(['status' => 'active']);
        app(CartService::class)->add($dress->id, 2);

        $response = $this->postJson(route('api.cart.update'), [
            'product_id' => $dress->id,
            'quantity' => 0,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'cart_count' => 0, 'items' => []]);
    }

    public function test_remove_deletes_the_item_and_recomputes_the_subtotal(): void
    {
        $dressA = Dress::factory()->create(['price' => 500, 'status' => 'active']);
        $dressB = Dress::factory()->create(['price' => 300, 'status' => 'active']);
        $cart = app(CartService::class);
        $cart->add($dressA->id, 1);
        $cart->add($dressB->id, 1);

        $response = $this->postJson(route('api.cart.remove'), [
            'product_id' => $dressA->id,
        ]);

        $response->assertOk();
        $items = $response->json('items');
        $this->assertCount(1, $items);
        $this->assertSame($dressB->id, $items[0]['dress']['id']);
        $this->assertSame('R300', $response->json('subtotal'));
    }

    public function test_navbar_badge_markup_always_renders_and_toggles_hidden_with_cart_state(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();

        $this->assertMatchesRegularExpression(
            '/class="cart-count[^"]*\bhidden\b[^"]*"/',
            $response->getContent(),
            'Badge should render (so JS can update it without a reload) but stay hidden while the cart is empty.'
        );

        $dress = Dress::factory()->create(['status' => 'active']);
        app(CartService::class)->add($dress->id, 4);

        $response = $this->get(route('home'));
        $response->assertOk();

        $this->assertDoesNotMatchRegularExpression(
            '/class="cart-count[^"]*\bhidden\b[^"]*"/',
            $response->getContent(),
            'Badge should lose the hidden class once the cart has items.'
        );
    }
}
