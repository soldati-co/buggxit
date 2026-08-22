@extends('layouts.app')

@section('title', 'Verify Email – BUGGXIT Couture')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-[#0a0a0a]">
        <div class="w-full max-w-md bg-ink-raised/90 border border-line rounded-xl p-8">
            <h2 class="text-2xl font-bold text-bone mb-4">Verify Email</h2>
            <p class="text-sm text-bone-dim mb-6">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just
                emailed to you? If you didn't receive the email, we will gladly send you another.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-good/10 border border-good/30 rounded-lg">
                    <p class="text-sm text-good">
                        A new verification link has been sent to the email address you provided during registration.
                    </p>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <x-primary-button>
                        Resend Verification Email
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="text-sm text-bone-dim hover:text-gold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-ink focus:ring-gold/50 rounded-md">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
