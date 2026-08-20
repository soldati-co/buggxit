@extends('layouts.app')

@section('title', 'Shipping & Exchange Policy – BUGGXIT Couture')

@section('content')
    <section class="relative mb-16 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-14 md:py-20">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-bone mb-3">Shipping & Exchange Policy</h1>
                <p class="text-bone-dim">Last updated {{ now()->format('F Y') }}</p>
            </div>
        </div>
    </section>

    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 space-y-10 text-bone-dim leading-relaxed">

            <div>
                <p>Every BUGGXIT Couture piece is made to order, so shipping and exchanges work a little differently to off-the-shelf retail. Here's what to expect.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Turnaround time</h2>
                <p>Ready-made pieces ship per the estimated turnaround shown on their product page — please check this before ordering, especially if you need a piece by a specific date. Custom, made-to-measure pieces take approximately 6 weeks from the time your deposit is confirmed and all measurements and design details are finalised. We'll keep you updated on the production process throughout.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Delivery areas</h2>
                <p>We deliver nationwide across South Africa via The Courier Guy and PEP Paxi. If you're outside South Africa and would like your order shipped internationally, please get in touch before placing your order so we can confirm feasibility and cost.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Shipping cost & delivery time</h2>
                <p>Delivery takes 7–14 working days. Shipping costs start from R150 and are calculated at checkout based on parcel size — you'll always know your shipping cost before you pay.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Order tracking</h2>
                <p>Once your order ships, you'll receive a tracking number via email or WhatsApp so you can follow its progress. You can also view your order status at any time from your account's <a href="{{ route('orders.index') }}" class="text-gold hover:text-gold-bright transition-colors">Order History</a>.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Delays</h2>
                <p>Occasionally, factors outside our control (courier delays, load-shedding, public holidays) can affect delivery times. If your order is running later than expected, please reach out and we'll help track it down.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Exchanges</h2>
                <p>We do not offer refunds. Exchanges are accepted within 7 days of delivery or purchase, provided the item is unworn and returned with all tags attached. If the correct item was delivered as ordered, return courier costs are the customer's responsibility; if the error is on our side, we cover it. Sale items are final sale and we offer no exchange on them.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Custom sizing & alterations</h2>
                <p>We do not alter existing designs. Custom sizing is only available for made-to-measure orders, and we take a maximum of 2 custom clients per month to ensure every piece gets the attention it deserves. Because custom and made-to-order pieces are cut and sewn to your exact measurements, they are final sale and can't be exchanged for a change of mind — this doesn't affect your right to a repair or replacement if the piece arrives faulty or doesn't match what was agreed at consultation.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Faulty or damaged items</h2>
                <p>If your order arrives damaged, faulty, or not as described, contact us within 7 days of delivery with a few photos of the issue. We'll arrange a repair or replacement at no cost to you.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">How to request an exchange</h2>
                <p>Get in touch via our <a href="{{ route('contact') }}" class="text-gold hover:text-gold-bright transition-colors">Contact page</a> with your order number and the reason for the exchange, and we'll guide you through the next steps, including return shipping.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Questions</h2>
                <p>For anything shipping or exchange related, contact us via our <a href="{{ route('contact') }}" class="text-gold hover:text-gold-bright transition-colors">Contact page</a> and we'll be happy to help.</p>
            </div>

        </div>
    </section>
@endsection
