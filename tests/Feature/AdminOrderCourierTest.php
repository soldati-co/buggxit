<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Dress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Manual orders (recorded by an admin for WhatsApp/phone/in-person sales)
 * bypass the customer checkout form entirely, so they need their own
 * courier_method/pep_point validation and PaxiPointService lookup --
 * mirroring CheckoutController's "never trust the client-submitted point,
 * always re-look-up server-side" pattern.
 */
class AdminOrderCourierTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    private function payload(array $overrides = []): array
    {
        $dress = Dress::factory()->create(['price' => 500, 'status' => 'active']);

        return array_merge([
            'name' => 'Walk-in Customer',
            'email' => 'walkin@example.com',
            'address_line1' => '1 Manual St',
            'city' => 'Cape Town',
            'postal_code' => '8001',
            'country' => 'South Africa',
            'phone' => '0111234567',
            'payment_method' => 'eft',
            'payment_status' => 'paid',
            'status' => 'processing',
            'items' => [
                ['dress_id' => $dress->id, 'quantity' => 1, 'price' => 500],
            ],
        ], $overrides);
    }

    public function test_manual_order_defaults_to_no_courier_method(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), $this->payload());

        $response->assertRedirect();
        $order = \App\Models\Order::first();
        $this->assertNull($order->courier_method);
        $this->assertNull($order->pep_point);
    }

    public function test_manual_order_with_courier_guy_stores_courier_method(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), $this->payload([
            'courier_method' => 'courier_guy',
        ]));

        $response->assertRedirect();
        $order = \App\Models\Order::first();
        $this->assertSame('courier_guy', $order->courier_method);
        $this->assertNull($order->pep_point);
    }

    public function test_manual_order_with_pep_courier_and_a_valid_point_stores_the_point(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response([
            ['nc' => 'A0001', 'sn' => 'PEP SANDTON', 'nd' => 'PEP SANDTON', 'a1' => 'SHOP 1', 'a4' => 'SANDTON', 'a6' => 'GAUTENG', 'pc' => '2196', 'ns' => 'open'],
        ], 200)]);

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), $this->payload([
            'state' => 'Gauteng',
            'courier_method' => 'pep',
            'pep_point_code' => 'A0001',
        ]));

        $response->assertRedirect();
        $order = \App\Models\Order::first();
        $this->assertSame('pep', $order->courier_method);
        $this->assertSame('A0001', $order->pep_point['code']);
        $this->assertSame('PEP SANDTON', $order->pep_point['name']);
    }

    public function test_manual_order_with_invalid_pep_point_fails_gracefully(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response([
            ['nc' => 'A0001', 'sn' => 'PEP SANDTON', 'nd' => 'PEP SANDTON', 'a1' => 'SHOP 1', 'a4' => 'SANDTON', 'a6' => 'GAUTENG', 'pc' => '2196', 'ns' => 'open'],
        ], 200)]);

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), $this->payload([
            'state' => 'Gauteng',
            'courier_method' => 'pep',
            'pep_point_code' => 'DOES-NOT-EXIST',
        ]));

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_pep_point_code_is_required_when_pep_courier_selected(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), $this->payload([
            'state' => 'Gauteng',
            'courier_method' => 'pep',
        ]));

        $response->assertSessionHasErrors('pep_point_code');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_province_is_required_when_pep_courier_selected(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), $this->payload([
            'courier_method' => 'pep',
            'pep_point_code' => 'A0001',
        ]));

        $response->assertSessionHasErrors('state');
        $this->assertDatabaseCount('orders', 0);
    }
}
