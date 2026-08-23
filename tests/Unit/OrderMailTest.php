<?php

namespace Tests\Unit;

use App\Mail\NewOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Address;
use App\Models\Dress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Mail::fake() (used in PayfastWebhookTest) never actually renders a
 * Mailable's Blade view, so it can't catch a template referencing a
 * missing/misspelled relation. These tests render the real HTML.
 */
class OrderMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_confirmation_mail_renders_with_items_and_address(): void
    {
        $order = $this->orderWithItemsAndAddress();

        $html = (new OrderConfirmationMail($order))->render();

        $this->assertStringContainsString($order->order_number, $html);
        $this->assertStringContainsString('Zimbini Dress', $html);
        $this->assertStringContainsString('Size 34', $html);
        $this->assertStringContainsString($order->shippingAddress->city, $html);
    }

    public function test_new_order_notification_mail_renders_with_items_and_address(): void
    {
        $order = $this->orderWithItemsAndAddress();

        $html = (new NewOrderNotificationMail($order))->render();

        $this->assertStringContainsString($order->order_number, $html);
        $this->assertStringContainsString('buyer@example.com', $html);
        $this->assertStringContainsString('Zimbini Dress', $html);
    }

    private function orderWithItemsAndAddress(): Order
    {
        $address = Address::factory()->create();
        $dress = Dress::factory()->create(['name' => 'Zimbini Dress', 'price' => 1540]);

        $order = Order::factory()->create([
            'order_number' => 'ORD-MAILTEST',
            'email' => 'buyer@example.com',
            'shipping_address_id' => $address->id,
            'total' => 1540,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'dress_id' => $dress->id,
            'quantity' => 1,
            'price' => 1540,
            'attributes' => ['size' => '34', 'color' => 'brown'],
        ]);

        return $order->fresh(['items.dress', 'shippingAddress', 'user']);
    }
}
