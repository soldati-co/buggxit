@extends('layouts.app')

@section('title', 'Terms of Use – BUGGXIT Couture')

@section('content')
    <section class="relative mb-16 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-14 md:py-20">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-bone mb-3">Terms of Use</h1>
                <p class="text-bone-dim">Effective Date: June 2026</p>
            </div>
        </div>
    </section>

    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 space-y-10 text-bone-dim leading-relaxed">

            <div class="space-y-2">
                <p>Welcome to Buggxit Couture.</p>
                <p>These Terms of Use govern your access to and use of the Buggxit Couture website, products, and services. By visiting our website or placing an order, you agree to these terms.</p>
                <p>Please read them carefully before making a purchase.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">About Us</h2>
                <div class="space-y-2">
                    <p>All prices are listed in South African Rand (ZAR).</p>
                    <p>Prices may change without notice.</p>
                    <p>While we work hard to avoid pricing errors, Buggxit Couture reserves the right to correct pricing, product, or description errors at any time, including after an order has been placed.</p>
                    <p>If an order is affected by a pricing error, we will contact you before proceeding.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Use of Our Website</h2>
                <p class="mb-2">By using this website, you agree to:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Use the site lawfully and responsibly</li>
                    <li>Provide accurate and complete information when placing orders</li>
                    <li>Not misuse, disrupt, or attempt to compromise website functionality</li>
                    <li>Not use our content or images without permission</li>
                </ul>
                <p class="mt-4">We reserve the right to restrict or terminate access where misuse is suspected.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Product Information</h2>
                <p>We aim to present all products as accurately as possible. However, slight variations may occur.</p>

                <h3 class="text-lg font-semibold text-bone mt-6 mb-2">Fit Disclaimer</h3>
                <p>Every woman wears a garment differently. Our product images showcase the intended design and silhouette, but the final fit may vary based on individual body shape, proportions, and sizing.</p>

                <h3 class="text-lg font-semibold text-bone mt-6 mb-2">Colour and Fabric Variation</h3>
                <p>Due to lighting, screen settings, and fabric batch variations, actual colours, prints, and pattern placement may vary slightly from website images.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Orders</h2>
                <p class="mb-2">Submitting an order does not automatically guarantee acceptance. Orders are confirmed only once:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Payment has been successfully received, and</li>
                    <li>We confirm stock availability or production availability</li>
                </ul>
                <p class="mt-4 mb-2">We reserve the right to decline or cancel orders where:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Fraud or suspicious activity is suspected</li>
                    <li>Payment cannot be verified</li>
                    <li>Stock becomes unavailable</li>
                    <li>Incorrect pricing or product information was displayed</li>
                </ul>
                <p class="mt-4">If payment has already been made for a cancelled order, eligible refunds will be processed accordingly.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Payment Terms</h2>
                <p>We accept payment through approved secure payment providers. Payment must be completed before ready-made items are dispatched.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Custom Orders</h2>
                <p class="mb-2">Custom garments follow separate payment terms.</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Custom orders below R2,000 require full payment upfront before production begins.</li>
                    <li>Custom orders of R2,000 or more require a non-refundable 50% deposit to secure the booking and begin production.</li>
                    <li>The remaining balance must be paid in full before dispatch or collection.</li>
                </ul>
                <p class="mt-4">Production begins only once payment terms, measurements, and design details are confirmed.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Custom Orders and Measurements</h2>
                <div class="space-y-2">
                    <p>Custom work is created specifically for you.</p>
                    <p>Customers are responsible for ensuring submitted measurements are accurate unless measurements are taken directly by Buggxit Couture.</p>
                    <p>Buggxit Couture cannot accept responsibility for fit issues caused by incorrect self-submitted measurements.</p>
                    <p>Custom order timelines may vary depending on complexity, fabric availability, and workload.</p>
                    <p>Standard custom production time is approximately 6 weeks unless otherwise communicated.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Shipping and Delivery</h2>
                <div class="space-y-2">
                    <p>Ready-made orders are processed and dispatched within 2 business days after payment confirmation, excluding weekends and public holidays.</p>
                    <p>Shipping timelines after dispatch depend on the courier. Delivery estimates are provided in our <a href="{{ route('shipping-policy') }}" class="text-gold hover:text-gold-bright transition-colors">Shipping Policy</a>.</p>
                    <p>While we work to meet all delivery timelines, delays caused by couriers, weather, strikes, public unrest, or other events outside our control may occur.</p>
                    <p>Customers are encouraged to order well in advance of weddings, ceremonies, and important events.</p>
                    <p>Buggxit Couture is not liable for losses resulting from delays outside our reasonable control.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Returns and Exchanges</h2>
                <div class="space-y-2">
                    <p>Returns and exchanges are governed by our Returns &amp; Exchange Policy. Please review that policy before purchasing.</p>
                    <p>Certain products, including sale items and custom garments, may not qualify for exchange or return.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Limitation of Liability</h2>
                <p class="mb-2">To the fullest extent permitted by law, Buggxit Couture shall not be liable for indirect, incidental, or consequential damages arising from:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Use of this website</li>
                    <li>Delays in delivery</li>
                    <li>Product misuse</li>
                    <li>Technical interruptions</li>
                    <li>Third-party platform failures</li>
                </ul>
                <p class="mt-4">Nothing in these terms limits rights that cannot legally be excluded under South African consumer law.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Intellectual Property</h2>
                <p class="mb-2">All content on this website belongs to Buggxit Couture unless otherwise stated. This includes:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Logos</li>
                    <li>Product designs</li>
                    <li>Images</li>
                    <li>Written content</li>
                    <li>Branding elements</li>
                    <li>Graphics</li>
                </ul>
                <p class="mt-4">You may not reproduce, distribute, copy, or commercially use any content without written permission.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Privacy</h2>
                <p>Your use of our website is also governed by our <a href="{{ route('privacy-policy') }}" class="text-gold hover:text-gold-bright transition-colors">Privacy Policy</a>. Please review it to understand how we collect and protect your information.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Changes to These Terms</h2>
                <p>We may update these Terms of Use from time to time. Updated terms will be published on this page. Continued use of the website means you accept any changes.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Contact Information</h2>
                <p>For questions about these terms, contact:</p>
                <p>Buggxit Couture<br>Email: <a href="mailto:info@buggxit.store" class="text-gold hover:text-gold-bright transition-colors">info@buggxit.store</a></p>
            </div>

        </div>
    </section>
@endsection
