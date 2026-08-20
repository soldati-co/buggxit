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

        $this->assertSame(5, $cart->all()[$dress->id]);
        $this->assertSame(5, $cart->count());
    }

    public function test_add_caps_accumulated_quantity_at_the_per_item_max(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();

        $cart->add($dress->id, CartService::MAX_QUANTITY_PER_ITEM);
        $cart->add($dress->id, 1);

        $this->assertSame(CartService::MAX_QUANTITY_PER_ITEM, $cart->all()[$dress->id]);
    }

    public function test_update_caps_quantity_at_the_per_item_max(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();

        $cart->update($dress->id, CartService::MAX_QUANTITY_PER_ITEM + 10);

        $this->assertSame(CartService::MAX_QUANTITY_PER_ITEM, $cart->all()[$dress->id]);
    }

    public function test_update_to_zero_or_below_removes_the_item(): void
    {
        $dress = Dress::factory()->create();
        $cart = new CartService();
        $cart->add($dress->id, 2);

        $cart->update($dress->id, 0);

        $this->assertArrayNotHasKey($dress->id, $cart->all());
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
