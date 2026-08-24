<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_settings_page(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.settings.edit'));

        $response->assertOk();
    }

    public function test_admin_can_enable_whatsapp_button_with_a_valid_number(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_enabled' => '1',
            'whatsapp_number' => '+27821234567',
            'whatsapp_position' => 'left',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSame('1', Setting::get('whatsapp_enabled'));
        $this->assertSame('+27821234567', Setting::get('whatsapp_number'));
        $this->assertSame('left', Setting::get('whatsapp_position'));
    }

    public function test_invalid_whatsapp_number_is_rejected(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_enabled' => '1',
            'whatsapp_number' => 'not-a-number',
            'whatsapp_position' => 'right',
        ]);

        $response->assertSessionHasErrors('whatsapp_number');
        $this->assertNull(Setting::get('whatsapp_number'));
    }

    public function test_whatsapp_button_is_hidden_by_default(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('wa.me', false);
    }

    public function test_whatsapp_button_appears_once_enabled_with_a_number(): void
    {
        Setting::set('whatsapp_enabled', '1');
        Setting::set('whatsapp_number', '+27821234567');
        Setting::set('whatsapp_position', 'right');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('https://wa.me/27821234567', false);
    }

    public function test_whatsapp_button_stays_hidden_if_disabled_even_with_a_number_set(): void
    {
        Setting::set('whatsapp_enabled', '0');
        Setting::set('whatsapp_number', '+27821234567');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('wa.me', false);
    }

    public function test_admin_can_set_instagram_widget_id(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_position' => 'right',
            'instagram_widget_id' => '123456',
        ]);

        $response->assertRedirect();
        $this->assertSame('123456', Setting::get('instagram_widget_id'));
    }

    public function test_invalid_instagram_widget_id_is_rejected(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_position' => 'right',
            'instagram_widget_id' => '<script>alert(1)</script>',
        ]);

        $response->assertSessionHasErrors('instagram_widget_id');
        $this->assertNull(Setting::get('instagram_widget_id'));
    }

    public function test_admin_can_disable_the_instagram_feed_section(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_position' => 'right',
            // instagram_feed_enabled omitted -- unchecked checkboxes send nothing
        ]);

        $response->assertRedirect();
        $this->assertSame('0', Setting::get('instagram_feed_enabled'));
    }

    public function test_admin_can_re_enable_the_instagram_feed_section(): void
    {
        Setting::set('instagram_feed_enabled', '0');
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_position' => 'right',
            'instagram_feed_enabled' => '1',
        ]);

        $response->assertRedirect();
        $this->assertSame('1', Setting::get('instagram_feed_enabled'));
    }

    public function test_admin_can_enable_popup_banner_with_custom_text(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_position' => 'right',
            'popup_banner_enabled' => '1',
            'popup_banner_text' => 'Pop-up in Durban this Saturday!',
        ]);

        $response->assertRedirect();
        $this->assertSame('1', Setting::get('popup_banner_enabled'));
        $this->assertSame('Pop-up in Durban this Saturday!', Setting::get('popup_banner_text'));
    }

    public function test_admin_can_set_and_toggle_social_media_links(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_position' => 'right',
            'social_instagram_url' => 'https://www.instagram.com/buggxit_couture/',
            'social_instagram_enabled' => '1',
            'social_facebook_url' => 'https://www.facebook.com/p/Buggxit-Couture-Clothing-Accessories-100053004263016/',
            'social_facebook_enabled' => '1',
            'social_twitter_url' => 'https://twitter.com/buggxit_couture',
            'social_twitter_enabled' => '0',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSame('https://www.instagram.com/buggxit_couture/', Setting::get('social_instagram_url'));
        $this->assertSame('1', Setting::get('social_instagram_enabled'));
        $this->assertSame('https://twitter.com/buggxit_couture', Setting::get('social_twitter_url'));
        $this->assertSame('0', Setting::get('social_twitter_enabled'));
        $this->assertSame('0', Setting::get('social_tiktok_enabled'));
    }

    public function test_invalid_social_media_url_is_rejected(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'whatsapp_position' => 'right',
            'social_instagram_url' => 'javascript:alert(1)',
        ]);

        $response->assertSessionHasErrors('social_instagram_url');
        $this->assertNull(Setting::get('social_instagram_url'));
    }

    public function test_social_link_is_hidden_on_the_site_when_no_url_is_set(): void
    {
        Setting::set('social_twitter_enabled', '1');
        Setting::set('social_twitter_url', null);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('fa-twitter', false);
    }

    public function test_social_link_is_hidden_on_the_site_when_disabled_even_with_a_url_set(): void
    {
        Setting::set('social_tiktok_enabled', '0');
        Setting::set('social_tiktok_url', 'https://www.tiktok.com/@buggxit_couture');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('tiktok.com/@buggxit_couture', false);
    }

    public function test_social_link_appears_on_the_site_once_enabled_with_a_url(): void
    {
        Setting::set('social_facebook_enabled', '1');
        Setting::set('social_facebook_url', 'https://www.facebook.com/p/Buggxit-Couture-Clothing-Accessories-100053004263016/');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('https://www.facebook.com/p/Buggxit-Couture-Clothing-Accessories-100053004263016/', false);
    }
}
