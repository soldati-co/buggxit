@extends('layouts.app')

@section('title', 'Create Account – BUGGXIT Couture')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-[#0a0a0a] relative overflow-hidden"
        style="contain: layout paint;">
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gold/3 rounded-full blur-3xl"></div>
        </div>
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent">
        </div>

        <div class="w-full max-w-md relative z-10" style="contain: layout style;">
            <!-- Card -->
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
                                    d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-bone mb-2">Create Account</h2>
                    <p class="text-sm text-bone-dim">Join the BUGGXIT community</p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="px-8 pt-8 pb-8">
                    @csrf

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-bone-dim mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v1.2c0 .66.54 1.2 1.2 1.2h16.8c.66 0 1.2-.54 1.2-1.2v-1.2c0-3.2-6.4-4.8-9.6-4.8z" />
                            </svg>
                            Full Name
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            autocomplete="name"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                            placeholder="John Doe">
                        @error('name')
                            <p class="mt-2 text-sm text-bad flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-bone-dim mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                            Email Address
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autocomplete="username"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                            placeholder="your.email@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-bad flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-bone-dim mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                            </svg>
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full px-4 py-3 pl-12 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200 pr-12"
                                placeholder="••••••••">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bone-dim">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                                </svg>
                            </div>
                            <button type="button" onclick="togglePassword('password', 'toggleIcon1')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-bone-dim hover:text-gold transition-colors duration-200">
                                <svg id="toggleIcon1" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-bad flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-8">
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-bone-dim mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                            </svg>
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full px-4 py-3 pl-12 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200 pr-12"
                                placeholder="••••••••">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bone-dim">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                                </svg>
                            </div>
                            <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-bone-dim hover:text-gold transition-colors duration-200">
                                <svg id="toggleIcon2" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-bad flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 flex items-center justify-center group">
                        <span class="mr-3">Create Account</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 17l5-5-5-5v10z" />
                        </svg>
                    </button>

                    @if ($errors->any())
                        <div class="mt-6 p-4 bg-bad/10 border border-bad/30 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-bad mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-bad font-medium">Registration failed</p>
                                    <p class="text-xs text-bad mt-1">Please correct the errors and try again.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Back to store -->
                    <div class="mt-8 pt-6 border-t border-line/50 text-center">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center text-sm text-bone-dim hover:text-gold transition-colors duration-200 group">
                            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-300"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                            </svg>
                            Return to BUGGXIT Store
                        </a>
                    </div>
                </form>

                <!-- Already Registered Link -->
                <div class="px-8 pb-6">
                    <div class="text-center text-sm text-bone-dim">
                        Already have an account?
                        <a href="{{ route('signin') }}" class="text-gold hover:text-gold-bright font-medium">Sign
                            in</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline JavaScript -->
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML =
                    '<path d="M12 6.5c2.76 0 5 2.24 5 5 0 .51-.1 1-.24 1.46l3.06 3.06c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l2.17 2.17c.47-.14.96-.24 1.47-.24zM2.71 3.16c-.39.39-.39 1.02 0 1.41l1.97 1.97C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.97-.3 4.31-.82l2.72 2.72c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L4.13 3.16c-.39-.39-1.03-.39-1.42 0zM12 16.5c-2.76 0-5-2.24-5-5 0-.77.18-1.5.49-2.14l1.57 1.57c-.03.18-.06.37-.06.57 0 1.66 1.34 3 3 3 .2 0 .38-.03.57-.07L14.14 16c-.64.32-1.37.5-2.14.5zm2.97-5.33c-.15-1.4-1.25-2.49-2.64-2.64l2.64 2.64z"/>';
            } else {
                input.type = 'password';
                icon.innerHTML =
                    '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
            }
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML =
                '<svg class="w-4 h-4 mr-2 animate-spin" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1a11 11 0 1 0 11 11A11 11 0 0 0 12 1zm0 19a8 8 0 1 1 8-8 8 8 0 0 1-8 8z" opacity=".25"/><path d="M10.14 1.16a11 11 0 0 0-9 8.94A1.59 1.59 0 0 0 2.46 12a1.59 1.59 0 0 0 1.65-1.3 8 8 0 0 1 6.66-6.61A1.42 1.42 0 0 0 12 2.69a1.57 1.57 0 0 0-1.86-1.53z"/></svg> Creating account...';
        });
    </script>

    <style>
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
