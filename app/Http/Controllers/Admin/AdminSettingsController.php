<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
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
        ]);

        Setting::set('whatsapp_enabled', $request->boolean('whatsapp_enabled') ? '1' : '0');
        Setting::set('whatsapp_number', $validated['whatsapp_number'] ?? null);
        Setting::set('whatsapp_position', $validated['whatsapp_position']);
        Setting::set('instagram_widget_id', $validated['instagram_widget_id'] ?? null);
        Setting::set('popup_banner_enabled', $request->boolean('popup_banner_enabled') ? '1' : '0');
        Setting::set('popup_banner_text', $validated['popup_banner_text'] ?? null);

        return back()->with('success', 'Settings updated successfully.');
    }
}
