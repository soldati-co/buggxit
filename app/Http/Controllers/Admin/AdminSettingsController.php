<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    /**
     * Social platforms managed by the admin settings page, with the link that is
     * already live on the site today used as the fallback default.
     */
    private const SOCIAL_PLATFORM_DEFAULTS = [
        'instagram' => 'https://www.instagram.com/buggxit_couture/',
        'facebook' => 'https://www.facebook.com/p/Buggxit-Couture-Clothing-Accessories-100053004263016/',
        'twitter' => '',
        'tiktok' => '',
    ];

    public function edit()
    {
        $settings = [
            'whatsapp_enabled' => Setting::get('whatsapp_enabled', '0'),
            'whatsapp_number' => Setting::get('whatsapp_number', ''),
            'whatsapp_position' => Setting::get('whatsapp_position', 'right'),
            'instagram_widget_id' => Setting::get('instagram_widget_id', ''),
            'popup_banner_enabled' => Setting::get('popup_banner_enabled', '0'),
            'popup_banner_text' => Setting::get('popup_banner_text', "We're coming to you. Pop-up shop coming soon — follow @buggxit_couture for the date and location."),
        ];

        foreach (self::SOCIAL_PLATFORM_DEFAULTS as $platform => $defaultUrl) {
            $settings["social_{$platform}_url"] = Setting::get("social_{$platform}_url", $defaultUrl);
            $settings["social_{$platform}_enabled"] = Setting::get("social_{$platform}_enabled", $defaultUrl !== '' ? '1' : '0');
        }

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_enabled' => 'nullable|boolean',
            // International format, digits only (with an optional leading +).
            'whatsapp_number' => 'nullable|string|max:20|regex:/^\+?[0-9]{7,15}$/',
            'whatsapp_position' => 'required|in:left,right',
            'instagram_widget_id' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_-]*$/',
            'popup_banner_enabled' => 'nullable|boolean',
            'popup_banner_text' => 'nullable|string|max:300',
            'social_instagram_url' => 'nullable|url:http,https|max:255',
            'social_instagram_enabled' => 'nullable|boolean',
            'social_facebook_url' => 'nullable|url:http,https|max:255',
            'social_facebook_enabled' => 'nullable|boolean',
            'social_twitter_url' => 'nullable|url:http,https|max:255',
            'social_twitter_enabled' => 'nullable|boolean',
            'social_tiktok_url' => 'nullable|url:http,https|max:255',
            'social_tiktok_enabled' => 'nullable|boolean',
        ]);

        Setting::set('whatsapp_enabled', $request->boolean('whatsapp_enabled') ? '1' : '0');
        Setting::set('whatsapp_number', $validated['whatsapp_number'] ?? null);
        Setting::set('whatsapp_position', $validated['whatsapp_position']);
        Setting::set('instagram_widget_id', $validated['instagram_widget_id'] ?? null);
        Setting::set('popup_banner_enabled', $request->boolean('popup_banner_enabled') ? '1' : '0');
        Setting::set('popup_banner_text', $validated['popup_banner_text'] ?? null);

        foreach (array_keys(self::SOCIAL_PLATFORM_DEFAULTS) as $platform) {
            Setting::set("social_{$platform}_url", $validated["social_{$platform}_url"] ?? null);
            Setting::set("social_{$platform}_enabled", $request->boolean("social_{$platform}_enabled") ? '1' : '0');
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
