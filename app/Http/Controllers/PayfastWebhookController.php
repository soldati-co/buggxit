<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\PayfastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PayfastWebhookController extends Controller
{
    /**
     * PayFast's Instant Transaction Notification (ITN) — a server-to-server
     * POST with no Laravel session/CSRF token, so this route is CSRF-exempt
     * (see bootstrap/app.php) and authenticated purely via PayfastService's
     * signature + source-domain + server-confirmation checks instead.
     *
     * Always returns HTTP 200 promptly: PayFast retries on any non-200
     * response, and a verification failure isn't something a retry fixes —
     * it's logged for investigation instead of surfaced as an error.
     */
    public function handleItn(Request $request, PayfastService $payfast)
    {
        $payload = $request->all();

        if (! $payfast->verifyItn($payload)) {
            Log::warning('PayFast ITN failed verification', ['payload' => $payload]);

            return response('', 200);
        }

        $order = Order::where('order_number', $payload['m_payment_id'] ?? null)->first();

        if (! $order) {
            Log::warning('PayFast ITN for unknown order', ['m_payment_id' => $payload['m_payment_id'] ?? null]);

            return response('', 200);
        }

        match ($payload['payment_status'] ?? null) {
            'COMPLETE' => $this->markPaidAndNotify($order),
            'FAILED', 'CANCELLED' => $order->update(['payment_status' => 'failed']),
            default => Log::info('PayFast ITN with unhandled payment_status', [
                'order' => $order->order_number,
                'payment_status' => $payload['payment_status'] ?? null,
            ]),
        };

        return response('', 200);
    }

    private function markPaidAndNotify(Order $order): void
    {
        $order->update(['payment_status' => 'paid', 'status' => 'processing']);

        $order->load('items.dress', 'shippingAddress', 'user');

        $customerEmail = $order->email ?? $order->user?->email;

        try {
            if ($customerEmail) {
                Mail::to($customerEmail)->send(new OrderConfirmationMail($order));
            }

            Mail::to(config('mail.store_notification_address'))->send(new NewOrderNotificationMail($order));
        } catch (\Throwable $e) {
            // Payment is already confirmed and recorded above -- a mail delivery
            // failure shouldn't turn into a PayFast retry of the whole ITN.
            Log::error('Order confirmation/notification email failed to send', [
                'order' => $order->order_number,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
