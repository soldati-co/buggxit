@extends('layouts.app')

@section('title', 'Terms of Service – BUGGXIT Couture')

@section('content')
    <section class="relative mb-16 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-14 md:py-20">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-bone mb-3">Terms of Service</h1>
                <p class="text-bone-dim">Last updated {{ now()->format('F Y') }}</p>
            </div>
        </div>
    </section>

    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 space-y-10 text-bone-dim leading-relaxed">

            <div>
                <p>These Terms of Service govern your use of the BUGGXIT Couture website and your purchase of any product from us. By placing an order or using this site, you agree to these terms.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Our products</h2>
                <p>Each BUGGXIT Couture piece is handcrafted to order. Colours, fabric texture, and finer details may vary slightly from the photographs shown, as every piece is individually made. Estimated turnaround and delivery times are shown on each product page and are estimates, not guarantees.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Orders and pricing</h2>
                <ul class="list-disc list-inside space-y-2">
                    <li>All prices are listed in South African Rand (ZAR) and include applicable taxes unless stated otherwise.</li>
                    <li>Placing an order is an offer to purchase; we confirm your order once payment has been successfully processed.</li>
                    <li>We reserve the right to decline or cancel an order — for example, in cases of pricing errors or stock unavailability — in which case any payment made will be refunded.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Payment</h2>
                <p>We currently accept payment via <strong class="text-bone">PayFast</strong> only, supporting major cards and South African payment methods. Payment is processed securely by PayFast; we never see or store your full card details.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Accounts</h2>
                <p>If you create an account with us, you're responsible for keeping your login details confidential and for all activity that happens under your account.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Intellectual property</h2>
                <p>All designs, photography, logos, and content on this site belong to BUGGXIT Couture and may not be reproduced or used without our prior written permission.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Limitation of liability</h2>
                <p>We aim to describe our products as accurately as possible, but we don't guarantee that the website will always be error-free or uninterrupted. To the extent permitted by law, we aren't liable for indirect or consequential loss arising from your use of this site.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Governing law</h2>
                <p>These terms are governed by the laws of the Republic of South Africa.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Changes to these terms</h2>
                <p>We may update these terms from time to time. Continued use of the site after changes are posted means you accept the updated terms.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Contact us</h2>
                <p>Questions about these terms? Reach out via our <a href="{{ route('contact') }}" class="text-gold hover:text-gold-bright transition-colors">Contact page</a>.</p>
            </div>

        </div>
    </section>
@endsection
