@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-[#0a0a0a]">
        <div class="w-full max-w-md">
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl shadow-lg overflow-hidden">
                <!-- Card Header -->
                <div
                    class="px-8 pt-8 pb-6 text-center border-b border-line/50 bg-gradient-to-b from-ink-raised2/20 to-transparent">
                    <div class="relative inline-block mb-4">
                        <div class="absolute inset-0 bg-gold/20 rounded-full blur-md"></div>
                        <div
                            class="relative p-4 bg-gradient-to-br from-ink-raised2 to-ink-raised border border-line rounded-full">
                            <svg class="w-6 h-6 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-bone mb-2">Forgot Password</h2>
                    <p class="text-sm text-bone-dim">Enter your email and we'll send you a reset link.</p>

                    @if (session('status'))
                        <div class="mt-4 p-3 bg-good/10 border border-good/30 rounded-lg">
                            <p class="text-sm text-good">{{ session('status') }}</p>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="px-8 pt-8 pb-8">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-bone-dim mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200">
                        @error('email')
                            <p class="text-bad text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300">
                        Email Password Reset Link
                    </button>

                    <div class="mt-6 text-center">
                        <a href="{{ route('signin') }}" class="text-sm text-bone-dim hover:text-gold transition-colors duration-200">← Back to sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
