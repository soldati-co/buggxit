<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon/Logo for browser tab -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('Favicon_Gold.svg') }}">
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('Favicon_Gold.svg') }}">

    <title>{!! trim($__env->yieldContent('title', 'BUGGXIT Admin')) !!}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-ink-raised text-bone font-sans antialiased overflow-x-hidden" x-data="{ sidebarOpen: false }">

    <!-- Fixed top gradient line (signature detail) -->
    <div
        class="fixed top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent pointer-events-none z-50">
    </div>

    <!-- Glowing orbs (fixed background) -->
    <div class="fixed -top-40 -left-40 w-80 h-80 bg-gold/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed -bottom-40 -right-40 w-80 h-80 bg-gold/3 rounded-full blur-3xl pointer-events-none"></div>

    @php
        $adminNavLinks = [
            ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'fa-tachometer-alt', 'label' => 'Dashboard'],
            ['route' => 'admin.dresses.index', 'match' => 'admin.dresses.*', 'icon' => 'fa-tshirt', 'label' => 'Dresses'],
            ['route' => 'admin.categories.index', 'match' => 'admin.categories.*', 'icon' => 'fa-tags', 'label' => 'Categories'],
            ['route' => 'admin.orders.index', 'match' => 'admin.orders.*', 'icon' => 'fa-receipt', 'label' => 'Orders'],
            ['route' => 'admin.hero-slides.index', 'match' => 'admin.hero-slides.*', 'icon' => 'fa-images', 'label' => 'Hero Slides'],
            ['route' => 'admin.settings.edit', 'match' => 'admin.settings.*', 'icon' => 'fa-cog', 'label' => 'Settings'],
        ];
    @endphp

    <!-- ========== SIDEBAR NAV (shared partial for desktop-fixed + mobile off-canvas) ========== -->

    <!-- Desktop sidebar (always visible, fixed) -->
    <aside class="hidden md:flex md:flex-col fixed inset-y-0 left-0 z-40 w-64 bg-ink-raised/95 backdrop-blur-sm border-r border-line/50">
        @include('admin.partials.sidebar-nav', ['adminNavLinks' => $adminNavLinks])
    </aside>

    <!-- Mobile off-canvas sidebar -->
    <div x-show="sidebarOpen" x-cloak class="md:hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-ink/80 backdrop-blur-sm" @click="sidebarOpen = false"
            x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"></div>
        <aside class="relative flex flex-col w-64 h-full bg-ink-raised border-r border-line/50"
            x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full">
            @include('admin.partials.sidebar-nav', ['adminNavLinks' => $adminNavLinks, 'isMobile' => true])
        </aside>
    </div>

    <!-- Mobile top bar -->
    <header class="md:hidden sticky top-0 z-30 bg-ink-raised/90 backdrop-blur-sm border-b border-line/50">
        <div class="flex items-center justify-between h-16 px-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <div class="relative p-2 bg-gradient-to-br from-ink-raised2 to-ink-raised border border-line rounded-full">
                    <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                    </svg>
                </div>
                <span class="text-bone font-bold text-lg tracking-tight">BUGGXIT<span class="text-gold">.</span>admin</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 text-bone-dim hover:text-gold hover:bg-ink-raised2/50 rounded-lg transition-all duration-300">
                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 9h16.5M3.75 15h16.5" />
                </svg>
                <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </header>

    <!-- ========== CONTENT WRAPPER (offset for the desktop sidebar) ========== -->
    <div class="md:pl-64 flex flex-col min-h-screen">
        <main class="relative z-10 flex-1 container-wide px-4 sm:px-6 lg:px-8 py-8 mx-auto w-full">

            <!-- Page Header (can be overridden) -->
            @hasSection('page-header')
                <div class="mb-8">
                    @yield('page-header')
                </div>
            @else
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-bone">
                            @yield('page-title', 'Admin Dashboard')
                        </h1>
                        <p class="text-bone-dim text-sm mt-1">
                            @yield('page-description', 'Manage your BUGGXIT Couture collection')
                        </p>
                    </div>
                    @yield('header-actions')
                </div>
            @endif

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-good/10 border border-good/30 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-good mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                    <span class="text-good text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-bad/10 border border-bad/30 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-bad mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                    <span class="text-bad text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Yield Content -->
            @yield('content')
        </main>

        <!-- ========== ADMIN FOOTER ========== -->
        <footer class="relative bg-ink-raised/90 backdrop-blur-sm border-t border-line/50 w-full">
            <div
                class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent pointer-events-none">
            </div>

            <div class="container-wide px-4 sm:px-6 lg:px-8 py-6 mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-bone-dim text-xs">
                        &copy; {{ date('Y') }} <span class="text-gold font-semibold">Buggxit Couture</span>. All
                        rights reserved.
                    </p>
                    <div class="flex items-center space-x-4 text-xs text-bone-faint">
                        <span>Admin Portal v1.0</span>
                        <span class="text-bone-faint">•</span>
                        <span>Session timeout: 30 min</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @include('components.back-to-top')

    <!-- Inline styles for consistency -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        .container-wide {
            max-width: 90rem;
            /* 1440px */
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* Smooth backdrop blur fallback */
        @supports not (backdrop-filter: blur(10px)) {
            .backdrop-blur-sm {
                background-color: rgba(10, 10, 10, 0.95);
            }
        }
    </style>

    @stack('scripts')
</body>

</html>
