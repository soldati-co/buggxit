<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PayFast\PayFastPayment;

/**
 * Thin wrapper around the payfast/payfast-php-sdk package. Signature
 * generation and ITN (Instant Transaction Notification) verification — the
 * two places integration bugs classically creep in — are delegated entirely
 * to the SDK rather than hand-rolled, since it already implements PayFast's
 * documented signature algorithm, source-domain check, and the required
 * server-to-server confirmation postback to PayFast's /eng/query/validate.
 */
class PayfastService
{
    private PayFastPayment $payfast;

    public function __construct()
    {
        $this->payfast = new PayFastPayment([
            'merchantId' => config('payfast.merchant_id'),
            'merchantKey' => config('payfast.merchant_key'),
            'passPhrase' => config('payfast.passphrase'),
            'testMode' => (bool) config('payfast.sandbox'),
        ]);
    }

    /**
     * Ready-to-render auto-submitting HTML <form> that sends the buyer to
     * PayFast's hosted payment page, signature already computed by the SDK.
     */
    public function paymentFormHtml(Order $order): string
    {
        $user = $order->user;
        [$firstName, $lastName] = $this->splitName($user?->name);

        $data = array_filter([
            'return_url' => URL::temporarySignedRoute('checkout.success', now()->addHours(48), ['order' => $order->id]),
            'cancel_url' => route('checkout.index'),
            'notify_url' => route('payfast.notify'),
            'name_first' => $firstName,
            'name_last' => $lastName,
            'email_address' => $user?->email,
            'm_payment_id' => $order->order_number,
            'amount' => (float) $order->total,
            'item_name' => 'BUGGXIT Couture Order '.$order->order_number,
        ]);

        // The SDK's createFormFields() builds <input value="..."> by raw string
        // concatenation with no escaping, and computes the ITN signature from
        // these same raw values — so we can't HTML-escape (that would make the
        // signed string disagree with what the browser submits once entities
        // decode). Stripping HTML-metacharacters instead closes the injection
        // path (e.g. a user's own account name containing `">`) while leaving
        // the submitted value byte-for-byte identical to what gets signed.
        $data = array_map(
            static fn ($value) => is_string($value) ? preg_replace('/[<>"\'&]/', '', $value) : $value,
            $data
        );

        return $this->payfast->custom->createFormFields($data, [
            'value' => 'Continue to PayFast',
            'class' => 'w-full py-3.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-bold rounded-lg cursor-pointer',
        ]);
    }

    /**
     * Verify an incoming ITN payload: signature, source domain, and the
     * required PayFast server confirmation postback. Never throws — a
     * failure here should be logged and acknowledged (HTTP 200), not
     * surfaced as a 500 that would make PayFast endlessly retry.
     */
    public function verifyItn(array $payload, array $checks = []): bool
    {
        try {
            return $this->payfast->notification->isValidNotification($payload, $checks);
        } catch (\Throwable $e) {
            Log::warning('PayFast ITN verification threw', ['message' => $e->getMessage()]);

            return false;
        }
    }

    private function splitName(?string $name): array
    {
        if (! $name) {
            return [null, null];
        }

        $parts = explode(' ', trim($name), 2);

        return [$parts[0], $parts[1] ?? null];
    }
}
