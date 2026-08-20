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
        ]);

        Setting::set('whatsapp_enabled', $request->boolean('whatsapp_enabled') ? '1' : '0');
        Setting::set('whatsapp_number', $validated['whatsapp_number'] ?? null);
        Setting::set('whatsapp_position', $validated['whatsapp_position']);

        return back()->with('success', 'Settings updated successfully.');
    }
}
