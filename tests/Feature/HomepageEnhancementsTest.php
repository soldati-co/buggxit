<?php

namespace Tests\Feature;

use App\Models\Dress;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_trust_strip_appears_on_homepage(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Nationwide Delivery');
        $response->assertSee('7-Day Exchange Policy');
        $response->assertSee('Proudly South African');
    }

    public function test_popup_banner_hidden_by_default(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('popup-banner-dismiss', false);
    }

    public function test_popup_banner_appears_once_enabled_with_text(): void
    {
        Setting::set('popup_banner_enabled', '1');
        Setting::set('popup_banner_text', 'Pop-up in Durban this Saturday!');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Pop-up in Durban this Saturday!');
        $response->assertSee('Follow on Instagram');
    }

    public function test_popup_banner_stays_hidden_if_disabled_even_with_text_set(): void
    {
        Setting::set('popup_banner_enabled', '0');
        Setting::set('popup_banner_text', 'Pop-up in Durban this Saturday!');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Pop-up in Durban this Saturday!');
    }

    public function test_follow_along_section_appears_without_widget_script_by_default(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Follow Along');
        $response->assertSee('@buggxit_couture');
        $response->assertDontSee('snapwidget.js', false);
    }

    public function test_follow_along_section_embeds_snapwidget_when_widget_id_set(): void
    {
        Setting::set('instagram_widget_id', '987654');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('data-id="987654"', false);
        $response->assertSee('snapwidget.js', false);
    }

    public function test_size_guide_page_loads_with_expected_content(): void
    {
        $response = $this->get(route('size-guide'));

        $response->assertOk();
        $response->assertSee('Find Your Fit');
        $response->assertSee('Bust (cm)');
        $response->assertSee('made-to-measure');
    }

    public function test_product_page_shows_shipping_trust_block(): void
    {
        $dress = Dress::factory()->create();

        $response = $this->get(route('products.show', $dress));

        $response->assertOk();
        $response->assertSee('Ships within 2 business days');
        $response->assertSee('The Courier Guy & PEP Paxi', false);
        $response->assertSee('7-day exchange policy');
    }
}
