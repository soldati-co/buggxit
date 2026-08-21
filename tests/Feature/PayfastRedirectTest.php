<?php

namespace Tests\Feature;

use App\Models\Dress;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PayfastRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_without_a_valid_signature_cannot_view_someone_elses_payfast_redirect(): void
    {
        $order = Order::factory()->create(['payment_method' => 'payfast']);

        $this->get(route('payfast.redirect', $order->id))->assertForbidden();
    }

    public function test_signed_url_renders_the_auto_submitting_payfast_form(): void
    {
        $order = Order::factory()->create(['payment_method' => 'payfast']);

        $url = URL::temporarySignedRoute('payfast.redirect', now()->addHours(48), ['order' => $order->id]);

        $response = $this->get($url);

        $response->assertOk();
        $response->assertSee('sandbox.payfast.co.za', false);
    }

    public function test_redirect_rejects_an_order_that_did_not_choose_payfast(): void
    {
        $order = Order::factory()->create(['payment_method' => 'eft']);
        $url = URL::temporarySignedRoute('payfast.redirect', now()->addHours(48), ['order' => $order->id]);

        $this->get($url)->assertNotFound();
    }

    public function test_checkout_with_payfast_selected_redirects_to_payfast_not_success(): void
    {
        $dress = Dress::factory()->create(['price' => 200, 'status' => 'active']);

        $cartEntry = ['dress_id' => $dress->id, 'size' => null, 'color' => null, 'quantity' => 1];

        $response = $this->withSession(['cart' => [$cartEntry]])
            ->post(route('checkout.store'), [
                'address_line1' => '1 Test St',
                'city' => 'Johannesburg',
                'postal_code' => '2000',
                'country' => 'South Africa',
                'phone' => '0123456789',
                'payment_method' => 'payfast',
            ]);

        $order = Order::first();
        $response->assertRedirect();
        $this->assertStringContainsString('/payfast/redirect/'.$order->id, $response->headers->get('Location'));
        $this->assertSame('pending', $order->payment_status);
    }
}
