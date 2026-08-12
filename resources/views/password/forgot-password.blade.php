@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-[#0a0a0a]">
        <div class="w-full max-w-md">
            <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Card Header -->
                <div
                    class="px-8 pt-8 pb-6 text-center border-b border-gray-800/50 bg-gradient-to-b from-gray-900/20 to-transparent">
                    <div class="relative inline-block mb-4">
                        <div class="absolute inset-0 bg-yellow-500/20 rounded-full blur-md"></div>
                        <div
                            class="relative p-4 bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-full">
                            <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Forgot Password</h2>
                    <p class="text-sm text-gray-400">Enter your email and we'll send you a reset link.</p>

                    @if (session('status'))
                        <div class="mt-4 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                            <p class="text-sm text-green-400">{{ session('status') }}</p>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="px-8 pt-8 pb-8">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all duration-200">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300">
                        Email Password Reset Link
                    </button>

                    <div class="mt-6 text-center">
                        <a href="{{ route('signin') }}" class="text-sm text-gray-400 hover:text-yellow-500 transition-colors duration-200">← Back to sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
