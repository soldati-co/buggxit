@extends('layouts.app')

@section('title', 'My Dashboard – BUGGXIT Couture')

@section('header')
    <h1 class="text-3xl md:text-4xl font-bold text-bone">My Dashboard</h1>
    <p class="mt-2 text-bone-dim max-w-3xl">Welcome back, {{ Auth::user()->name ?? 'valued customer' }}. Manage your account, view orders, and discover the latest collections.</p>
@endsection

@section('content')
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto py-12">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-line/70 bg-ink-raised/80 p-8 shadow-xl shadow-gold/5">
                <h2 class="text-xl font-semibold text-bone mb-4">Order History</h2>
                <p class="text-bone-dim mb-6">View the details of your past purchases and track any recent orders.</p>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3 text-sm font-semibold text-ink hover:bg-gold-bright transition">View Orders</a>
            </div>

            <div class="rounded-3xl border border-line/70 bg-ink-raised/80 p-8 shadow-xl shadow-gold/5">
                <h2 class="text-xl font-semibold text-bone mb-4">Account Settings</h2>
                <p class="text-bone-dim mb-6">Update your profile details, email address, and security settings.</p>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3 text-sm font-semibold text-ink hover:bg-gold-bright transition">Manage Profile</a>
            </div>

            <div class="rounded-3xl border border-line/70 bg-ink-raised/80 p-8 shadow-xl shadow-gold/5">
                <h2 class="text-xl font-semibold text-bone mb-4">Explore New Arrivals</h2>
                <p class="text-bone-dim mb-6">Discover the freshest pieces from our latest luxury collections.</p>
                <a href="{{ route('newarrival') }}" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3 text-sm font-semibold text-ink hover:bg-gold-bright transition">Browse New Arrivals</a>
            </div>
        </div>
    </section>
@endsection
