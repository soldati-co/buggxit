<?php

namespace Tests\Unit;

use App\Models\Dress;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_accumulates_quantity_for_repeated_calls(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();

        $cart->add($dress->id, 2);
        $cart->add($dress->id, 3);

        $this->assertSame(5, $cart->quantityFor($dress->id));
        $this->assertSame(5, $cart->count());
    }

    public function test_add_caps_accumulated_quantity_at_the_per_item_max(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();

        $cart->add($dress->id, CartService::MAX_QUANTITY_PER_ITEM);
        $cart->add($dress->id, 1);

        $this->assertSame(CartService::MAX_QUANTITY_PER_ITEM, $cart->quantityFor($dress->id));
    }

    public function test_update_caps_quantity_at_the_per_item_max(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();
        $cart->add($dress->id, 1);

        $cart->update($dress->id, CartService::MAX_QUANTITY_PER_ITEM + 10);

        $this->assertSame(CartService::MAX_QUANTITY_PER_ITEM, $cart->quantityFor($dress->id));
    }

    public function test_update_to_zero_or_below_removes_the_item(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();
        $cart->add($dress->id, 2);

        $cart->update($dress->id, 0);

        $this->assertSame(0, $cart->quantityFor($dress->id));
        $this->assertTrue($cart->isEmpty());
    }

    public function test_same_dress_with_different_size_or_color_is_tracked_as_separate_lines(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();

        $cart->add($dress->id, 1, '34', 'red');
        $cart->add($dress->id, 2, '36', 'blue');

        $this->assertSame(1, $cart->quantityFor($dress->id, '34', 'red'));
        $this->assertSame(2, $cart->quantityFor($dress->id, '36', 'blue'));
        $this->assertSame(0, $cart->quantityFor($dress->id, '38', 'red'));
        $this->assertSame(3, $cart->count());
        $this->assertCount(2, $cart->all());
    }

    public function test_add_with_matching_size_and_color_accumulates_onto_the_same_line(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();

        $cart->add($dress->id, 1, '34', 'red');
        $cart->add($dress->id, 2, '34', 'red');

        $this->assertSame(3, $cart->quantityFor($dress->id, '34', 'red'));
        $this->assertCount(1, $cart->all());
    }

    public function test_remove_only_drops_the_matching_size_and_color_line(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();
        $cart->add($dress->id, 1, '34', 'red');
        $cart->add($dress->id, 1, '36', 'blue');

        $cart->remove($dress->id, '34', 'red');

        $this->assertSame(0, $cart->quantityFor($dress->id, '34', 'red'));
        $this->assertSame(1, $cart->quantityFor($dress->id, '36', 'blue'));
    }

    public function test_items_with_dress_excludes_inactive_dresses(): void
    {
        $active = Dress::factory()->create(['status' => 'active', 'price' => 100]);
        $draft = Dress::factory()->create(['status' => 'draft', 'price' => 200]);

        $cart = new CartService();
        $cart->add($active->id, 2);
        $cart->add($draft->id, 1);

        $items = $cart->itemsWithDress();

        $this->assertCount(1, $items);
        $this->assertSame($active->id, $items[0]['dress']->id);
        $this->assertEquals(200.0, $items[0]['subtotal']);
    }

    public function test_items_with_dress_drops_entries_for_deleted_dresses(): void
    {
        $cart = new CartService();
        $cart->add('nonexistent-id', 1);

        $this->assertSame([], $cart->itemsWithDress());
        $this->assertSame(0.0, $cart->subtotal());
    }

    public function test_clear_empties_the_cart(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();
        $cart->add($dress->id, 1);

        $cart->clear();

        $this->assertTrue($cart->isEmpty());
        $this->assertSame([], Session::get('cart', []));
    }
}
