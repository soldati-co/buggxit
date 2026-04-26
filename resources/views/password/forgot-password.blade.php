@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8 bg-[#0a0a0a]">
    <div class="w-full max-w-md bg-black/90 border border-gray-800 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-white mb-4">Forgot Password</h2>
        <p class="text-sm text-gray-400 mb-6">
            Forgot your password? No problem. Enter your email and we’ll send you a reset link.
        </p>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                <p class="text-sm text-green-400">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-300 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full px-6 py-3 bg-yellow-500 text-gray-900 font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
                Email Password Reset Link
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('signin') }}" class="text-sm text-gray-400 hover:text-yellow-500">← Back to sign in</a>
        </div>
    </div>
</div>
@endsection