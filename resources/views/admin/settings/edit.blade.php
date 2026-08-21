@extends('layouts.admin')

@section('title', 'Settings - BUGGXIT Admin')
@section('page-title', 'Settings')
@section('page-description', 'Manage site-wide settings')

@section('content')
    <div class="max-w-2xl space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            @if ($errors->has('error'))
                <div class="p-4 bg-bad/10 border border-bad/30 rounded-lg flex items-start gap-3">
                    <i class="fas fa-triangle-exclamation text-bad mt-0.5"></i>
                    <p class="text-bad text-sm">{{ $errors->first('error') }}</p>
                </div>
            @endif

            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h2 class="text-lg font-semibold text-bone mb-1 flex items-center">
                    <i class="fab fa-whatsapp text-good mr-2"></i> WhatsApp Chat Button
                </h2>
                <p class="text-sm text-bone-dim mb-6">
                    Show a floating WhatsApp button on every page of the site so customers can message you directly.
                </p>

                <div class="space-y-6">
                    <label class="flex items-center p-3 border border-line rounded-lg cursor-pointer hover:border-gold/50 transition-colors">
                        <input type="checkbox" name="whatsapp_enabled" value="1"
                            {{ old('whatsapp_enabled', $settings['whatsapp_enabled']) ? 'checked' : '' }}
                            class="h-4 w-4 rounded bg-ink-raised2 border-line text-gold focus:ring-gold">
                        <span class="ml-3 text-bone">Show the floating WhatsApp button on the site</span>
                    </label>

                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-1">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}"
                            placeholder="e.g. +27821234567"
                            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
                        @error('whatsapp_number')
                            <p class="text-bad text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-bone-faint mt-1">Include the country code (e.g. 27 for South Africa), with or without the leading +. No spaces or dashes.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Button Position</label>
                        <div class="flex gap-3">
                            <label class="flex-1 flex items-center justify-center p-3 border border-line rounded-lg cursor-pointer hover:border-gold/50 transition-colors has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                <input type="radio" name="whatsapp_position" value="left"
                                    {{ old('whatsapp_position', $settings['whatsapp_position']) === 'left' ? 'checked' : '' }}
                                    class="sr-only">
                                <span class="text-bone text-sm"><i class="fas fa-arrow-left mr-2 text-gold"></i>Left side</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center p-3 border border-line rounded-lg cursor-pointer hover:border-gold/50 transition-colors has-[:checked]:border-gold has-[:checked]:bg-gold/10">
                                <input type="radio" name="whatsapp_position" value="right"
                                    {{ old('whatsapp_position', $settings['whatsapp_position']) === 'right' ? 'checked' : '' }}
                                    class="sr-only">
                                <span class="text-bone text-sm"><i class="fas fa-arrow-right mr-2 text-gold"></i>Right side</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h2 class="text-lg font-semibold text-bone mb-1 flex items-center">
                    <i class="fab fa-instagram text-gold mr-2"></i> Instagram Feed
                </h2>
                <p class="text-sm text-bone-dim mb-6">
                    Shows a "Follow Along" section on the homepage. Paste an
                    <a href="https://elfsight.com" target="_blank" rel="noopener noreferrer" class="text-gold hover:text-gold-bright underline">Elfsight</a>
                    Instagram Feed widget ID to embed your live @buggxit_couture feed — leave it blank to show a simple follow
                    link instead.
                </p>

                <div>
                    <label class="block text-sm font-medium text-bone-dim mb-1">Elfsight Widget ID</label>
                    <input type="text" name="instagram_widget_id" value="{{ old('instagram_widget_id', $settings['instagram_widget_id']) }}"
                        placeholder="e.g. 29f4d79e-8ae9-4324-bce0-5553440396bf"
                        class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
                    @error('instagram_widget_id')
                        <p class="text-bad text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-bone-faint mt-1">Find this in your Elfsight dashboard under the widget's Export/Publish code — it's the UUID after "elfsight-app-" in the embed snippet.</p>
                </div>
            </div>

            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h2 class="text-lg font-semibold text-bone mb-1 flex items-center">
                    <i class="fas fa-store text-gold mr-2"></i> Pop-Up Shop Banner
                </h2>
                <p class="text-sm text-bone-dim mb-6">
                    A dismissible banner on the homepage announcing an upcoming pop-up. Turn it on only while a
                    pop-up is actually coming up.
                </p>

                <div class="space-y-6">
                    <label class="flex items-center p-3 border border-line rounded-lg cursor-pointer hover:border-gold/50 transition-colors">
                        <input type="checkbox" name="popup_banner_enabled" value="1"
                            {{ old('popup_banner_enabled', $settings['popup_banner_enabled']) ? 'checked' : '' }}
                            class="h-4 w-4 rounded bg-ink-raised2 border-line text-gold focus:ring-gold">
                        <span class="ml-3 text-bone">Show the pop-up shop banner</span>
                    </label>

                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-1">Banner Text</label>
                        <textarea name="popup_banner_text" rows="3"
                            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">{{ old('popup_banner_text', $settings['popup_banner_text']) }}</textarea>
                        @error('popup_banner_text')
                            <p class="text-bad text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-bone-faint mt-1">The "Follow on Instagram" button is added automatically.</p>
                    </div>
                </div>
            </div>

            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h2 class="text-lg font-semibold text-bone mb-1 flex items-center">
                    <i class="fas fa-share-nodes text-gold mr-2"></i> Social Media
                </h2>
                <p class="text-sm text-bone-dim mb-6">
                    Add a link for each platform and switch it on to show its icon on the site. A platform stays
                    hidden until it has both a link and is switched on.
                </p>

                <div class="space-y-6">
                    @foreach ([
                        'instagram' => ['label' => 'Instagram', 'icon' => 'fa-instagram', 'placeholder' => 'https://www.instagram.com/your-handle/'],
                        'facebook' => ['label' => 'Facebook', 'icon' => 'fa-facebook', 'placeholder' => 'https://www.facebook.com/your-page/'],
                        'twitter' => ['label' => 'Twitter / X', 'icon' => 'fa-twitter', 'placeholder' => 'https://twitter.com/your-handle'],
                        'tiktok' => ['label' => 'TikTok', 'icon' => 'fa-tiktok', 'placeholder' => 'https://www.tiktok.com/@your-handle'],
                    ] as $platform => $meta)
                        <div class="border border-line rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="flex items-center text-bone font-medium">
                                    <i class="fab {{ $meta['icon'] }} mr-2 text-gold"></i> {{ $meta['label'] }}
                                </span>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="social_{{ $platform }}_enabled" value="1"
                                        {{ old("social_{$platform}_enabled", $settings["social_{$platform}_enabled"]) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded bg-ink-raised2 border-line text-gold focus:ring-gold">
                                    <span class="ml-2 text-sm text-bone-dim">Visible</span>
                                </label>
                            </div>
                            <input type="url" name="social_{{ $platform }}_url"
                                value="{{ old("social_{$platform}_url", $settings["social_{$platform}_url"]) }}"
                                placeholder="{{ $meta['placeholder'] }}"
                                class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
                            @error("social_{$platform}_url")
                                <p class="text-bad text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                    <p class="text-xs text-bone-faint">Leave a link blank to remove that platform from the site regardless of the toggle.</p>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection
