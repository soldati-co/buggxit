@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-[#0a0a0a]">
        <div class="w-full max-w-md bg-black/90 border border-gray-800 rounded-xl p-8">
            <h2 class="text-2xl font-bold text-white mb-4">{{ __('Confirm Password') }}</h2>
            <p class="text-sm text-gray-400 mb-6">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block w-full" type="password" name="password" required
                        autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6">
                    <x-primary-button>
                        {{ __('Confirm') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
