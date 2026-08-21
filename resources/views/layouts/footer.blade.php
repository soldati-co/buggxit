<footer class="relative bg-ink-raised/90 backdrop-blur-sm border-t border-line w-full overflow-hidden">
    <!-- Decorative element -->
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent">
    </div>

    <!-- Glowing orbs - fixed to prevent horizontal scroll -->
    <div
        class="absolute top-0 left-0 w-40 h-40 bg-gold/5 rounded-full blur-3xl md:w-60 md:h-60 -translate-x-1/2 -translate-y-1/2">
    </div>
    <div
        class="absolute bottom-0 right-0 w-60 h-60 bg-gold/3 rounded-full blur-3xl md:w-80 md:h-80 translate-x-1/2 translate-y-1/2">
    </div>

    <div class="container-wide py-12 relative z-10 w-full">
        <!-- Top section: 4 columns on desktop, 2 columns on tablet, 1 column on mobile -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-10 w-full">
            <!-- Logo & Social -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('Buggxit_Submark_Gold.svg') }}" alt="BUGGXIT Logo" class="h-12 w-auto md:h-14">
                </div>
                <p class="text-bone-dim text-sm">Ceremony-ready fashion. Proudly South African.</p>
                <x-social-links variant="footer" />
            </div>

            <!-- Collections -->
            <div class="lg:col-span-1">
                <h3 class="text-bone font-semibold mb-4 pb-2 border-b border-line/50">Collections</h3>
                <ul class="space-y-3">

                    <li>
                        <a href="{{ route('products.index') }}"
                            class="flex items-center text-bone-dim hover:text-gold text-sm transition-all duration-300 group">
                            <i
                                class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 group-hover:text-gold transition-all duration-300"></i>
                            Products
                        </a>
                    </li>


                </ul>
            </div>

            <!-- Customer Service -->
            <div class="lg:col-span-1">
                <h3 class="text-bone font-semibold mb-4 pb-2 border-b border-line/50">Customer Service</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('contact') }}"
                            class="flex items-center text-bone-dim hover:text-gold text-sm transition-all duration-300 group">
                            <i
                                class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 group-hover:text-gold transition-all duration-300"></i>
                            Contact
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('about') }}"
                            class="flex items-center text-bone-dim hover:text-gold text-sm transition-all duration-300 group">
                            <i
                                class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 group-hover:text-gold transition-all duration-300"></i>
                            About
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('size-guide') }}"
                            class="flex items-center text-bone-dim hover:text-gold text-sm transition-all duration-300 group">
                            <i
                                class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 group-hover:text-gold transition-all duration-300"></i>
                            Size Guide
                        </a>
                    </li>

                </ul>
            </div>

            <!-- Newsletter -->
            <div class="space-y-6 sm:col-span-2 lg:col-span-1">
                <div>
                    <h3 class="text-bone font-semibold mb-4 pb-2 border-b border-line/50">Stay in the Loop</h3>
                    <p class="text-bone-dim text-sm mb-4">New drops, pop-up dates, and exclusive offers. Straight to your inbox.</p>
                </div>

                <form class="space-y-4">
                    <div class="relative">
                        <input type="email" placeholder="Your email address"
                            class="w-full px-4 py-3 bg-ink-raised2/50 backdrop-blur-sm border border-line rounded-full text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-300 pl-12">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bone-dim">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-full hover:from-gold-bright hover:to-gold hover:shadow-lg hover:shadow-gold/25 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                        Subscribe
                    </button>
                </form>

                <!-- Payment Methods -->
                <div class="pt-6 mt-6 border-t border-line/50">
                    <p class="text-bone-dim text-sm mb-3">Secure Payment Methods</p>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="inline-flex items-center justify-center h-8 px-2.5 bg-white rounded-md">
                            <img src="{{ asset('icons/payment-methods/visa.svg') }}" alt="Visa" class="h-4 w-auto">
                        </span>
                        <span class="inline-flex items-center justify-center h-8 px-2.5 bg-white rounded-md">
                            <img src="{{ asset('icons/payment-methods/mastercard.svg') }}" alt="Mastercard" class="h-5 w-auto">
                        </span>
                        <span class="inline-flex items-center justify-center h-8 px-2.5 bg-white rounded-md">
                            <img src="{{ asset('icons/payment-methods/payfast.svg') }}" alt="PayFast" class="h-4 w-auto">
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom section -->
        <div class="mt-12 pt-8 border-t border-line/50">
            <!-- On mobile: Stack everything -->
            <!-- On desktop: Copyright left, Legal Links center, Creator Credit right (but moved left) -->
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <!-- Copyright - left on desktop -->
                <p class="text-bone-dim text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} <span class="text-gold font-semibold">Buggxit Couture</span>. All
                    rights reserved.
                </p>

                <!-- Legal Links - center on desktop -->
                <div class="flex items-center justify-center space-x-6">
                    <a href="{{ route('privacy-policy') }}"
                        class="text-bone-dim hover:text-gold text-sm transition-colors duration-300">
                        Privacy Policy
                    </a>
                    <a href="{{ route('terms-of-service') }}"
                        class="text-bone-dim hover:text-gold text-sm transition-colors duration-300">
                        Terms of Service
                    </a>
                    <a href="{{ route('shipping-policy') }}"
                        class="text-bone-dim hover:text-gold text-sm transition-colors duration-300">
                        Exchange Policy
                    </a>
                    <a href="{{ route('admin.login') }}"
                        class="text-bone-dim hover:text-gold text-sm transition-colors duration-300">
                        Admin
                    </a>
                </div>

                <!-- Creator Credit with watermark - right on desktop but moved left -->
                {{-- <div class="text-bone-dim text-sm text-center md:text-right md:pr-12">
                    Crafted by
                    <img src="{{ asset('watermarks/watermark22.webp') }}" alt="Watermark" class="inline-block h-4 w-auto mx-1 align-middle">
                    <a href="https://linkedin.com/in/nkosi2k" class="text-gold hover:text-gold-bright transition-colors duration-300 font-semibold" target="_blank">
                        Nkosi
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

</footer>

@include('components.back-to-top')

<style>
    @keyframes glow {

        0%,
        100% {
            filter: drop-shadow(0 0 5px rgba(212, 175, 55, 0.5));
        }

        50% {
            filter: drop-shadow(0 0 15px rgba(212, 175, 55, 0.8));
        }
    }

    .drop-shadow-glow {
        animation: glow 2s ease-in-out infinite;
    }

    /* Smooth backdrop blur fallback */
    @supports not (backdrop-filter: blur(10px)) {
        .backdrop-blur-sm {
            background-color: rgba(10, 10, 10, 0.95);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .sm\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .sm\:grid-cols-2 {
            grid-template-columns: 1fr;
        }

        .flex-wrap {
            justify-content: center;
        }

        .text-center {
            text-align: center !important;
        }
    }
</style>
