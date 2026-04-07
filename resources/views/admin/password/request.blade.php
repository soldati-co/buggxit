@extends('layouts.admin-auth')

@section('header')
    <div class="flex flex-col items-center justify-center text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
            <span class="text-yellow-500">Reset</span> Password
        </h1>
        <p class="text-gray-400 text-sm md:text-base">
            Enter your email to receive a reset link
        </p>
    </div>
@endsection

@section('content')
<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-8 relative overflow-hidden">
    <!-- Decorative elements -->
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
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Forgot Password?</h2>
                <p class="text-sm text-gray-400">We'll send a reset link to your email</p>
            </div>

            <form method="POST" action="{{ route('admin.password.email') }}" class="px-8 pt-8 pb-8">
                @csrf

                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                        <p class="text-sm text-green-400 text-center">{{ session('status') }}</p>
                    </div>
                @endif

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <span>Admin Email</span>
                    </label>
                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 pl-12 bg-gray-800/50 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all duration-200"
                               placeholder="admin@buggxit.com">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                        </div>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 flex items-center justify-center group">
                    <span class="mr-3">Send Reset Link</span>
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
                            <p class="text-sm text-gray-300 font-medium mb-1">Password Reset</p>
                            <p class="text-xs text-gray-400">A secure link will be sent to your registered email address.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <div class="inline-flex items-center text-xs text-gray-500 bg-gray-900/50 border border-gray-800/50 rounded-full px-4 py-2">
                <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                    <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                </svg>
                <span>Reset link valid for 60 minutes</span>
            </div>
        </div>
    </div>
</div>
@endsection