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
}
