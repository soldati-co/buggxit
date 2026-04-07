@extends('layouts.admin-auth')

@section('header')
    <div class="flex flex-col items-center justify-center text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
            <span class="text-yellow-500">Create</span> New Password
        </h1>
        <p class="text-gray-400 text-sm md:text-base">
            Choose a strong password for your admin account
        </p>
    </div>
@endsection

@section('content')
<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-8 relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-yellow-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-yellow-500/3 rounded-full blur-3xl"></div>
    </div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-yellow-500/30 to-transparent pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="px-8 pt-8 pb-6 text-center border-b border-gray-800/50 bg-gradient-to-b from-gray-900/20 to-transparent">
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-yellow-500/20 rounded-full blur-md"></div>
                    <div class="relative p-4 bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-full">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Set New Password</h2>
                <p class="text-sm text-gray-400">Minimum 8 characters, strong passwords recommended</p>
            </div>

            <form method="POST" action="{{ route('admin.password.update') }}" class="px-8 pt-8 pb-8">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <span>Email Address</span>
                    </label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                           class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-400 cursor-not-allowed">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span>New Password</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required autofocus
                               class="w-full px-4 py-3 pl-12 bg-gray-800/50 border border-gray-700 rounded-lg text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 pr-12">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-yellow-500 transition-colors duration-200">
                            <svg id="toggleIcon" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span>Confirm Password</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white focus:border-yellow-500">
                </div>

                <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 flex items-center justify-center group">
                    <span class="mr-3">Reset Password</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 17l5-5-5-5v10z"/>
                    </svg>
                </button>

                <div class="mt-6 text-center">
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-yellow-500 transition-colors duration-200 group">
                        <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                        </svg>
                        Back to login
                    </a>
                </div>
            </form>

            <div class="px-8 pb-6">
                <div class="bg-gray-900/30 border border-gray-800/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-1 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-300 font-medium mb-1">Password Requirements</p>
                            <p class="text-xs text-gray-400">Use at least 8 characters, include uppercase, lowercase, numbers or symbols for strong security.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.innerHTML = '<path d="M12 6.5c2.76 0 5 2.24 5 5 0 .51-.1 1-.24 1.46l3.06 3.06c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l2.17 2.17c.47-.14.96-.24 1.47-.24zM2.71 3.16c-.39.39-.39 1.02 0 1.41l1.97 1.97C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.97-.3 4.31-.82l2.72 2.72c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L4.13 3.16c-.39-.39-1.03-.39-1.42 0zM12 16.5c-2.76 0-5-2.24-5-5 0-.77.18-1.5.49-2.14l1.57 1.57c-.03.18-.06.37-.06.57 0 1.66 1.34 3 3 3 .2 0 .38-.03.57-.07L14.14 16c-.64.32-1.37.5-2.14.5zm2.97-5.33c-.15-1.4-1.25-2.49-2.64-2.64l2.64 2.64z"/>';
        } else {
            passwordInput.type = 'password';
            toggleIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
        }
    }
</script>
@endsection