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
                <p>Because each piece is handcrafted, production takes time. The estimated turnaround and expected delivery window for each design is shown on its product page — please check this before ordering, especially if you need a piece by a specific date.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Delivery areas</h2>
                <p>We currently ship within South Africa. If you're outside South Africa and would like your order shipped internationally, please get in touch before placing your order so we can confirm feasibility and cost.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Shipping cost</h2>
                <p>Shipping cost depends on your delivery address and is confirmed with you after your order is placed and before it's dispatched. We'll always let you know the shipping cost before your order ships.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Order tracking</h2>
                <p>Once your order ships, we'll notify you with your courier's tracking details so you can follow its progress. You can also view your order status at any time from your account's <a href="{{ route('orders.index') }}" class="text-gold hover:text-gold-bright transition-colors">Order History</a>.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Delays</h2>
                <p>Occasionally, factors outside our control (courier delays, load-shedding, public holidays) can affect delivery times. If your order is running later than expected, please reach out and we'll help track it down.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Exchanges</h2>
                <p>Not quite the right fit? If you ordered a standard (non-custom) piece, you may request an exchange within 7 days of delivery, provided it's unworn, unwashed, and still has its tags and packaging. Exchanges are for a different size or an item of equal value — we don't currently offer cash refunds for a change of mind.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Custom & made-to-order pieces</h2>
                <p>Because most BUGGXIT Couture pieces are cut and sewn to your exact measurements, custom and made-to-order orders are final sale and can't be exchanged or refunded for change of mind. This doesn't affect your right to a repair, replacement, or refund if the piece arrives faulty or doesn't match what was agreed at consultation.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Faulty or damaged items</h2>
                <p>If your order arrives damaged, faulty, or not as described, contact us within 7 days of delivery with a few photos of the issue. We'll arrange a repair, replacement, or refund at no cost to you.</p>
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
