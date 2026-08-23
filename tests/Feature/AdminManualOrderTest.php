<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Admin;
use App\Models\Dress;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminManualOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_the_manual_order_form(): void
    {
        $this->get(route('admin.orders.create'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_record_a_paid_manual_order_and_it_emails_a_receipt(): void
    {
        Mail::fake();

        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create(['status' => 'active', 'price' => 800]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), [
            'name' => 'Thandi Nkosi',
            'email' => 'thandi@example.com',
            'address_line1' => '5 Church St',
            'city' => 'Pretoria',
            'postal_code' => '0002',
            'country' => 'South Africa',
            'phone' => '0827654321',
            'payment_method' => 'whatsapp',
            'payment_status' => 'paid',
            'status' => 'processing',
            'items' => [
                ['dress_id' => $dress->id, 'quantity' => 2, 'price' => 800, 'size' => '36', 'color' => 'red'],
            ],
        ]);

        $this->assertDatabaseCount('orders', 1);
        $order = Order::first();

        $response->assertRedirect(route('admin.orders.show', $order));
        $this->assertSame('Thandi Nkosi', $order->name);
        $this->assertSame('thandi@example.com', $order->email);
        $this->assertSame('whatsapp', $order->payment_method);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('processing', $order->status);
        $this->assertNull($order->user_id);
        $this->assertEquals(1600, (float) $order->total);

        $this->assertDatabaseCount('order_items', 1);
        $item = $order->items()->first();
        $this->assertSame($dress->id, $item->dress_id);
        $this->assertSame(2, $item->quantity);
        $this->assertSame(['size' => '36', 'color' => 'red'], $item->attributes);

        Mail::assertSent(OrderConfirmationMail::class, fn ($mail) => $mail->hasTo('thandi@example.com'));
    }

    public function test_pending_manual_order_does_not_email_a_receipt(): void
    {
        Mail::fake();

        $admin = Admin::factory()->create();
        $dress = Dress::factory()->create(['status' => 'active', 'price' => 500]);

        $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), [
            'name' => 'Walk-in Customer',
            'email' => 'walkin@example.com',
            'address_line1' => '1 Main Rd',
            'city' => 'Cape Town',
            'postal_code' => '8001',
            'country' => 'South Africa',
            'phone' => '0821112222',
            'payment_method' => 'eft',
            'payment_status' => 'pending',
            'status' => 'pending',
            'items' => [
                ['dress_id' => $dress->id, 'quantity' => 1, 'price' => 500],
            ],
        ]);

        Mail::assertNotSent(OrderConfirmationMail::class);
    }

    public function test_it_requires_at_least_one_item(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.orders.store'), [
            'name' => 'No Items',
            'address_line1' => '1 Main Rd',
            'city' => 'Cape Town',
            'postal_code' => '8001',
            'country' => 'South Africa',
            'phone' => '0821112222',
            'payment_method' => 'eft',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_manual_order_receipt_downloads_as_a_pdf(): void
    {
        $admin = Admin::factory()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.orders.receipt', $order));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
