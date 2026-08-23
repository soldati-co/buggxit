<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Dress;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression coverage for the checkout flow: Address/Order/OrderItem
 * previously had no UUID-generation logic and their `id` columns have no
 * database-level default on Postgres, so creating any of them threw a
 * NOT NULL violation — no order could ever be placed. These tests exercise
 * a real end-to-end order creation through the extracted services.
 */
class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_an_order_from_cart_with_a_new_address(): void
    {
        $dress = Dress::factory()->create(['price' => 250, 'status' => 'active']);

        $cart = new CartService();
        $cart->add($dress->id, 2);

        $address = Address::create([
            'user_id' => null,
            'address_line1' => '123 Main St',
            'city' => 'Durban',
            'postal_code' => '4001',
            'country' => 'South Africa',
            'phone' => '0000000000',
            'is_default' => false,
        ]);

        $this->assertNotEmpty($address->id, 'Address should receive a generated UUID.');

        $order = app(OrderService::class)->createOrderFromCart(
            shippingAddress: $address,
            billingAddress: null,
            paymentMethod: 'payfast',
            userId: null,
        );

        $this->assertNotEmpty($order->id, 'Order should receive a generated UUID.');
        $this->assertSame('pending', $order->status);
        $this->assertSame('pending', $order->payment_status);
        $this->assertEquals(500.0, (float) $order->subtotal);
        $this->assertEquals(500.0, (float) $order->total);
        $this->assertSame($address->id, $order->shipping_address_id);
        $this->assertSame($address->id, $order->billing_address_id);
        $this->assertNull($order->user_id);

        $this->assertDatabaseCount('order_items', 1);
        $item = $order->items()->first();
        $this->assertNotEmpty($item->id);
        $this->assertSame($dress->id, $item->dress_id);
        $this->assertSame(2, $item->quantity);

        $this->assertTrue($cart->isEmpty(), 'Cart should be cleared after a successful order.');
    }

    public function test_authenticated_user_order_links_user_id(): void
    {
        $user = User::factory()->create();
        $dress = Dress::factory()->create(['price' => 100, 'status' => 'active']);
        $address = Address::factory()->create(['user_id' => $user->id]);

        $cart = new CartService();
        $cart->add($dress->id, 1);

        $order = app(OrderService::class)->createOrderFromCart(
            shippingAddress: $address,
            billingAddress: null,
            paymentMethod: 'payfast',
            userId: $user->id,
        );

        $this->assertSame($user->id, $order->user_id);
    }

    public function test_creating_an_order_with_an_empty_cart_throws(): void
    {
        $this->expectException(\RuntimeException::class);

        $address = Address::factory()->create();

        app(OrderService::class)->createOrderFromCart(
            shippingAddress: $address,
            billingAddress: null,
            paymentMethod: 'payfast',
            userId: null,
        );
    }

    public function test_full_http_checkout_form_submission_redirects_to_a_signed_success_url(): void
    {
        $dress = Dress::factory()->create(['price' => 300, 'status' => 'active']);

        $cartEntry = ['dress_id' => $dress->id, 'size' => null, 'color' => null, 'quantity' => 1];

        $response = $this->withSession(['cart' => [$cartEntry]])
            ->post(route('checkout.store'), [
                'address_line1' => '456 Test Ave',
                'city' => 'Cape Town',
                'postal_code' => '8001',
                'country' => 'South Africa',
                'phone' => '0111234567',
                'email' => 'guest@example.com',
                'payment_method' => 'payfast',
            ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('addresses', 1);
        $this->assertDatabaseCount('order_items', 1);

        $order = \App\Models\Order::first();
        $this->assertSame('guest@example.com', $order->email);
        $response->assertRedirect();
        $this->assertStringContainsString('signature=', $response->headers->get('Location'));
        $this->assertStringContainsString((string) $order->id, $response->headers->get('Location'));

        // Following the redirect as the same guest session works.
        $this->get($response->headers->get('Location'))->assertOk();
    }

    public function test_guest_without_a_valid_signature_cannot_view_someone_elses_order_success_page(): void
    {
        $order = \App\Models\Order::factory()->create(['user_id' => null]);

        $this->get(route('checkout.success', $order->id))->assertForbidden();
    }

    public function test_owning_authenticated_user_can_revisit_success_page_without_a_signature(): void
    {
        $user = User::factory()->create();
        $order = \App\Models\Order::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('checkout.success', $order->id))
            ->assertOk();
    }

    public function test_authenticated_non_owner_without_a_signature_is_forbidden(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = \App\Models\Order::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($intruder)
            ->get(route('checkout.success', $order->id))
            ->assertForbidden();
    }
}
