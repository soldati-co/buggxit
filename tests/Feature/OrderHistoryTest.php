<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Dress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 6 parity check: OrderController/orders.index/orders.show render a
 * real order (with a shipping address and line items) end-to-end against
 * the now-UUID-keyed schema.
 */
class OrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function createOrderFor(User $user): Order
    {
        $address = Address::factory()->create(['user_id' => $user->id]);
        $dress = Dress::factory()->create(['price' => 450]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'shipping_address_id' => $address->id,
            'billing_address_id' => $address->id,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'dress_id' => $dress->id,
            'quantity' => 2,
            'price' => $dress->price,
        ]);

        return $order;
    }

    public function test_user_sees_their_own_orders_in_history(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderFor($user);

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertOk();
        $response->assertSee($order->order_number);
    }

    public function test_user_can_view_their_own_order_detail_with_items_and_address(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderFor($user);

        $response = $this->actingAs($user)->get(route('orders.show', $order));

        $response->assertOk();
        $response->assertSee($order->order_number);
        $response->assertSee($order->shippingAddress->address_line1);
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = $this->createOrderFor($owner);

        $this->actingAs($intruder)
            ->get(route('orders.show', $order))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('orders.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_download_their_own_receipt(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderFor($user);

        $response = $this->actingAs($user)->get(route('orders.receipt', $order));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_user_cannot_download_another_users_receipt(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = $this->createOrderFor($owner);

        $this->actingAs($intruder)
            ->get(route('orders.receipt', $order))
            ->assertForbidden();
    }
}
