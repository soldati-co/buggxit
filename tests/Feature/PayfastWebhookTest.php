<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Services\PayfastService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Covers PayfastWebhookController's own handling logic (order lookup,
 * status transitions, always-200 acknowledgement) with PayfastService::
 * verifyItn() mocked, since the real implementation makes a live network
 * call to PayFast regardless of signature validity — see
 * tests/Unit/PayfastServiceTest.php for why that can't run offline.
 */
class PayfastWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_payment_marks_the_order_paid_and_processing(): void
    {
        $this->mock(PayfastService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyItn')->once()->andReturn(true);
        });

        $order = Order::factory()->create(['order_number' => 'ORD-ITN1', 'payment_status' => 'pending', 'status' => 'pending']);

        $response = $this->post(route('payfast.notify'), [
            'm_payment_id' => 'ORD-ITN1',
            'payment_status' => 'COMPLETE',
        ]);

        $response->assertOk();
        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertSame('processing', $order->fresh()->status);
    }

    public function test_failed_payment_marks_the_order_payment_failed(): void
    {
        $this->mock(PayfastService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyItn')->once()->andReturn(true);
        });

        $order = Order::factory()->create(['order_number' => 'ORD-ITN2', 'payment_status' => 'pending']);

        $this->post(route('payfast.notify'), [
            'm_payment_id' => 'ORD-ITN2',
            'payment_status' => 'FAILED',
        ])->assertOk();

        $this->assertSame('failed', $order->fresh()->payment_status);
    }

    public function test_unverified_notification_does_not_change_order_status_but_still_acknowledges(): void
    {
        $this->mock(PayfastService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyItn')->once()->andReturn(false);
        });

        $order = Order::factory()->create(['order_number' => 'ORD-ITN3', 'payment_status' => 'pending']);

        $response = $this->post(route('payfast.notify'), [
            'm_payment_id' => 'ORD-ITN3',
            'payment_status' => 'COMPLETE',
        ]);

        $response->assertOk();
        $this->assertSame('pending', $order->fresh()->payment_status);
    }

    public function test_notification_for_unknown_order_still_acknowledges_without_error(): void
    {
        // The order lookup happens before verifyItn() now (its result feeds
        // the amount_gross/m_payment_id checks), so an unknown order should
        // short-circuit without ever calling PayFast's verification.
        $this->mock(PayfastService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyItn')->never();
        });

        $response = $this->post(route('payfast.notify'), [
            'm_payment_id' => 'ORD-DOES-NOT-EXIST',
            'payment_status' => 'COMPLETE',
        ]);

        $response->assertOk();
    }

    public function test_notify_route_is_csrf_exempt(): void
    {
        $this->mock(PayfastService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyItn')->once()->andReturn(false);
        });

        Order::factory()->create(['order_number' => 'whatever', 'payment_status' => 'pending']);

        // No _token/X-CSRF-TOKEN provided at all — a real Laravel session
        // middleware stack would 419 this without the except() entry in
        // bootstrap/app.php, since PayFast's server-to-server POST carries none.
        $response = $this->post(route('payfast.notify'), ['m_payment_id' => 'whatever']);

        $response->assertOk();
    }
}
