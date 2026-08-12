<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\User;
use App\Services\PayfastService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PayFast\Auth;
use Tests\TestCase;

/**
 * Covers the network-free half of the PayFast integration: outbound payment
 * form field/signature generation. The SDK's isValidNotification() (used by
 * PayfastService::verifyItn()) unconditionally performs a real HTTP request
 * to PayFast's server-confirmation endpoint as one of four checks it runs
 * before combining the results — it does not short-circuit on an invalid
 * signature — so ITN verification cannot be meaningfully unit-tested without
 * hitting PayFast's servers. See tests/Feature/PayfastWebhookTest.php for
 * coverage of this app's own handling logic around that boundary instead.
 */
class PayfastServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_form_html_contains_a_form_posting_to_the_sandbox_process_url(): void
    {
        $order = Order::factory()->create(['total' => 1234.5, 'order_number' => 'ORD-TESTFORM']);

        $html = app(PayfastService::class)->paymentFormHtml($order);

        $this->assertStringContainsString('<form action="https://sandbox.payfast.co.za/eng/process" method="post">', $html);
        $this->assertStringContainsString('name="m_payment_id" type="hidden" value="ORD-TESTFORM"', $html);
        $this->assertStringContainsString('name="amount" type="hidden" value="1234.50"', $html);
        $this->assertStringContainsString('name="merchant_id" type="hidden" value="'.config('payfast.merchant_id').'"', $html);
    }

    public function test_payment_form_html_includes_a_valid_signature_matching_the_sdk_algorithm(): void
    {
        $order = Order::factory()->create(['total' => 500, 'order_number' => 'ORD-SIGCHECK']);

        $html = app(PayfastService::class)->paymentFormHtml($order);

        preg_match('/name="signature" type="hidden" value="([a-f0-9]{32})"/', $html, $matches);
        $this->assertNotEmpty($matches, 'A 32-character hex MD5 signature field should be present.');

        // Re-derive the field set exactly as PayfastService builds it, then
        // verify the SDK's own signature algorithm reproduces the same value.
        preg_match_all('/name="([a-z_]+)" type="hidden" value="([^"]*)"/', $html, $fieldMatches, PREG_SET_ORDER);
        $fields = [];
        foreach ($fieldMatches as $field) {
            if ($field[1] !== 'signature') {
                $fields[$field[1]] = $field[2];
            }
        }

        $expected = Auth::generateSignature($fields, config('payfast.passphrase'));
        $this->assertSame($expected, $matches[1]);
    }

    public function test_payment_form_html_omits_buyer_fields_for_a_guest_order(): void
    {
        $order = Order::factory()->create(['user_id' => null]);

        $html = app(PayfastService::class)->paymentFormHtml($order);

        $this->assertStringNotContainsString('name="email_address"', $html);
        $this->assertStringNotContainsString('name="name_first"', $html);
    }

    public function test_payment_form_html_includes_buyer_name_and_email_for_an_authenticated_order(): void
    {
        $user = User::factory()->create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $html = app(PayfastService::class)->paymentFormHtml($order);

        $this->assertStringContainsString('name="name_first" type="hidden" value="Jane"', $html);
        $this->assertStringContainsString('name="name_last" type="hidden" value="Doe"', $html);
        $this->assertStringContainsString('name="email_address" type="hidden" value="jane@example.com"', $html);
    }
}
