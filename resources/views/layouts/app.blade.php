<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="BUGGXIT Couture - Est 2018">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon/Logo for browser tab -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('Favicon_Gold.svg') }}">
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('Favicon_Gold.svg') }}">

    <title>@yield('title', config('app.name', 'Buggxit Couture Est 2018'))</title>

    {{-- Preload the first active hero slide image (LCP) --}}
    @if(Route::is('home'))
        @php
            $heroSlidesForPreload = \Illuminate\Support\Facades\Schema::hasTable('hero_slides')
                ? \App\Models\HeroSlide::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                : collect();
        @endphp
        @if($heroSlidesForPreload->isNotEmpty() && $heroSlidesForPreload->first()->image_api_url)
            <link rel="preload" href="{{ $heroSlidesForPreload->first()->image_api_url }}" as="image" fetchpriority="high">
        @endif
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- Custom Styles for Consistent Theme -->
    <style>
        :root {
            --primary-bg: #0b0908;
            --secondary-bg: #17130f;
            --accent-gold: #b8873f;
            --accent-gold-light: #e3b968;
            --text-primary: #f3ede3;
            --text-secondary: #a89a87;
            --text-muted: #6f6455;
            --border-dark: #2e2620;
        }

        html,
        body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
            position: relative;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: linear-gradient(160deg, #0b0908 0%, #17130f 100%);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Placeholder for the client's licensed Juana Light (buggxit.store) —
           Cormorant Light is the closest free match until the real font files
           are provided. Kept in sync with the same rule in app.css (@layer
           base) so admin pages, which don't include this inline block, match. */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant', Georgia, serif;
            font-weight: 300;
        }

        .font-numeric {
            font-family: 'JetBrains Mono', ui-monospace, monospace;
            font-variant-numeric: tabular-nums;
        }

        /* Main content wrapper that pushes footer down */
        .page-wrapper {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 100%;
            position: relative;
            overflow-x: hidden;
        }

        .main-content {
            flex: 1 0 auto;
            padding-top: 64px;
            /* Navigation height */
            width: 100%;
            max-width: 100%;
        }

        /* Footer stays at bottom */
        .footer-wrapper {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-gold);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-gold-light);
        }

        /* Wider container */
        .container-wide {
            max-width: 90rem;
            /* 1440px */
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            /* max() keeps the usual 1rem everywhere, but grows on notched iPhones
               in landscape so content never sits under the camera cutout. */
            padding-left: max(1rem, env(safe-area-inset-left));
            padding-right: max(1rem, env(safe-area-inset-right));
        }

        /* Prevent horizontal overflow */
        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Page wrapper for sticky footer -->
    <div class="page-wrapper">
        <!-- Breeze-structured navigation with BUGGXIT design -->
        @include('layouts.navigation')

        <!-- Page Heading -->
        @hasSection('header')
            <header class="bg-gradient-to-r from-ink-raised to-ink border-b border-gold/20 w-full">
                <div class="container-wide py-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="main-content">
            @yield('content')
        </main>

        <!-- Footer - This will stick to bottom -->
        <div class="footer-wrapper">
            @include('layouts.footer')
        </div>
    </div>

    @include('components.whatsapp-button')

    <script>
        // Shared by every "Add to Cart" entry point (landing, product listing,
        // product detail, cart page) so the navbar badge and user feedback stay
        // consistent no matter where the cart is touched from.
        window.updateCartBadges = function (count) {
            document.querySelectorAll('.cart-count').forEach((el) => {
                el.textContent = count > 99 ? '99+' : count;
                el.classList.toggle('hidden', count <= 0);
                el.classList.add('scale-150');
                setTimeout(() => el.classList.remove('scale-150'), 300);
            });
        };

        window.showCartToast = function (message, isError = false) {
            document.getElementById('cart-toast')?.remove();

            const toast = document.createElement('div');
            toast.id = 'cart-toast';
            toast.className = `fixed top-20 left-1/2 -translate-x-1/2 z-[60] px-5 py-3 rounded-lg shadow-lg text-sm font-medium text-center opacity-0 transition-opacity duration-300 ${isError ? 'bg-bad text-white' : 'bg-gold text-ink'}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            requestAnimationFrame(() => toast.classList.remove('opacity-0'));

            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        };
    </script>

    @stack('scripts')
</body>

</html>