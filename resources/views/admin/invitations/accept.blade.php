@extends('layouts.admin-auth')

@section('title', 'Create Your Admin Account - BUGGXIT Admin')

@section('header')
    <div class="flex flex-col items-center justify-center text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-bone mb-2">
            <span class="text-gold">Create</span> Your Admin Account
        </h1>
        <p class="text-bone-dim text-sm md:text-base">
            You've been invited to join BUGGXIT Couture admin
        </p>
    </div>
@endsection

@section('content')
    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-8 relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gold/3 rounded-full blur-3xl"></div>
        </div>
        <div
            class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent pointer-events-none">
        </div>

        <div class="w-full max-w-md relative z-10">
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl shadow-lg overflow-hidden">
                <div
                    class="px-8 pt-8 pb-6 text-center border-b border-line/50 bg-gradient-to-b from-ink-raised2/20 to-transparent">
                    <div class="relative inline-block mb-4">
                        <div class="absolute inset-0 bg-gold/20 rounded-full blur-md"></div>
                        <div
                            class="relative p-4 bg-gradient-to-br from-ink-raised2 to-ink-raised border border-line rounded-full">
                            <svg class="w-6 h-6 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-bone mb-2">Welcome to BUGGXIT</h2>
                    <p class="text-sm text-bone-dim">Fill in your details to activate your admin account</p>
                </div>

                <form method="POST" action="{{ route('admin.invitations.store') }}" class="px-8 pt-8 pb-8">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-bone-dim mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone focus:border-gold focus:ring-1 focus:ring-gold/30">
                            @error('name')
                                <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="surname" class="block text-sm font-medium text-bone-dim mb-2">Surname</label>
                            <input type="text" name="surname" id="surname" value="{{ old('surname') }}" required
                                class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone focus:border-gold focus:ring-1 focus:ring-gold/30">
                            @error('surname')
                                <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-bone-dim mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                            <span>Email Address</span>
                        </label>
                        <input type="email" name="email" value="{{ $email }}" required readonly
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone-dim cursor-not-allowed">
                        @error('email')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-bone-dim mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                            </svg>
                            <span>Password</span>
                        </label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone focus:border-gold focus:ring-1 focus:ring-gold/30">
                        @error('password')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-bone-dim mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gold" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                            </svg>
                            <span>Confirm Password</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone focus:border-gold">
                    </div>

                    <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 flex items-center justify-center group">
                        <span class="mr-3">Create Admin Account</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M10 17l5-5-5-5v10z" />
                        </svg>
                    </button>
                </form>

                <div class="px-8 pb-6">
                    <div class="bg-ink-raised2/30 border border-line/50 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gold mt-1 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" />
                            </svg>
                            <div>
                                <p class="text-sm text-bone-dim font-medium mb-1">Password Requirements</p>
                                <p class="text-xs text-bone-dim">Use at least 8 characters, include uppercase, lowercase,
                                    numbers or symbols for strong security.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
