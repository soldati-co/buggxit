<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Dress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\ReceiptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_renders_a_pdf_for_the_customer_copy(): void
    {
        $order = $this->orderWithItemsAndAddress();

        $pdf = app(ReceiptService::class)->render($order, 'customer');

        $this->assertStringStartsWith('%PDF-', $pdf);
    }

    public function test_it_renders_a_pdf_for_the_store_copy(): void
    {
        $order = $this->orderWithItemsAndAddress();

        $pdf = app(ReceiptService::class)->render($order, 'store');

        $this->assertStringStartsWith('%PDF-', $pdf);
    }

    public function test_it_renders_fine_for_an_order_with_no_shipping_address_or_items(): void
    {
        $order = Order::factory()->create(['shipping_address_id' => null]);

        $pdf = app(ReceiptService::class)->render($order, 'customer');

        $this->assertStringStartsWith('%PDF-', $pdf);
    }

    public function test_filename_uses_the_order_number(): void
    {
        $order = Order::factory()->create(['order_number' => 'ORD-RECEIPT1']);

        $this->assertSame('Receipt-ORD-RECEIPT1.pdf', app(ReceiptService::class)->filename($order));
    }

    private function orderWithItemsAndAddress(): Order
    {
        $address = Address::factory()->create();
        $dress = Dress::factory()->create(['name' => 'Zimbini Dress', 'price' => 1540]);

        $order = Order::factory()->create([
            'order_number' => 'ORD-RECEIPTTEST',
            'name' => 'Nkosi Mteshana',
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

        return $order->fresh();
    }
}
