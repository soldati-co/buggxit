@extends('layouts.admin')

@section('title', 'Settings - BUGGXIT Admin')
@section('page-title', 'Settings')
@section('page-description', 'Manage site-wide settings')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
            <h2 class="text-lg font-semibold text-bone mb-1 flex items-center">
                <i class="fab fa-whatsapp text-good mr-2"></i> WhatsApp Chat Button
            </h2>
            <p class="text-sm text-bone-dim mb-6">
                Show a floating WhatsApp button on every page of the site so customers can message you directly.
            </p>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                @if ($errors->has('error'))
                    <div class="p-4 bg-bad/10 border border-bad/30 rounded-lg flex items-start gap-3">
                        <i class="fas fa-triangle-exclamation text-bad mt-0.5"></i>
                        <p class="text-bad text-sm">{{ $errors->first('error') }}</p>
                    </div>
                @endif

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

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
