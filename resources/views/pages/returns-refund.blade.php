@extends('layouts.app')

@section('title', 'ReturnsRefund – BUGGXIT Couture')

@section('content')
    <section class="relative mb-16 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-14 md:py-20">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-bone mb-3">ReturnsRefund</h1>
                <p class="text-bone-dim">Effective Date: June 2026</p>
            </div>
        </div>
    </section>

    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 space-y-10 text-bone-dim leading-relaxed">

            <div class="space-y-2">
                <p>At Buggxit Couture, every garment is prepared with care and quality checked before it reaches you.</p>
                <p>Because many of our pieces are limited-run, handmade, or custom-produced, please review this policy carefully before purchasing.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Refund Policy</h2>
                <div class="space-y-2">
                    <p>We do not offer refunds for purchases made through Buggxit Couture.</p>
                    <p>Please review product details, sizing information, and measurements carefully before placing your order.</p>
                    <p>If you need assistance before purchasing, we encourage you to contact us. We are happy to help.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Exchange Eligibility</h2>
                <p class="mb-2">We accept exchanges for eligible items within 7 days of delivery or collection.</p>
                <p class="mb-2">To qualify for an exchange, the item must be:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Unworn</li>
                    <li>Unwashed</li>
                </ul>
                <p class="mt-4">Courier selection may vary depending on parcel size, delivery location, and service availability.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Non-Exchangeable Items</h2>
                <p class="mb-2">The following items are not eligible for exchange:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Sale or discounted items</li>
                    <li>Custom or made-to-measure garments</li>
                    <li>Altered garments</li>
                    <li>Items damaged after delivery</li>
                    <li>Any item returned outside the 7-day exchange window</li>
                </ul>
                <p class="mt-4">Custom garments are created specifically to your measurements and design choices. For this reason, they cannot be exchanged or refunded once production has started.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Fit and Sizing</h2>
                <div class="space-y-2">
                    <p>Every woman wears a garment differently. Our product images showcase the intended design and silhouette, but the final fit may vary based on individual body shape, proportions, and sizing.</p>
                    <p>We encourage customers to review sizing information carefully before purchasing.</p>
                    <p>For custom orders, customers are responsible for ensuring submitted measurements are accurate unless measurements were taken directly by Buggxit Couture.</p>
                    <p>Buggxit Couture cannot accept responsibility for fit issues caused by incorrect self-submitted measurements.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Incorrect or Faulty Items</h2>
                <p class="mb-2">If you receive:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>The wrong item</li>
                    <li>A damaged item</li>
                    <li>A garment with a manufacturing fault</li>
                </ul>
                <div class="space-y-2 mt-4">
                    <p>Please contact us within 48 hours of delivery.</p>
                    <p>Where the error is on our side, Buggxit Couture will cover reasonable return courier costs and work to resolve the issue as quickly as possible.</p>
                    <p>Supporting photos may be requested during assessment.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Return Shipping Costs</h2>
                <div class="space-y-2">
                    <p>If the correct item was delivered as ordered, return courier costs are the customer's responsibility.</p>
                    <p>If the issue resulted from an error by Buggxit Couture, we will cover the return shipping cost.</p>
                    <p>We recommend using a trackable courier service for all returns.</p>
                    <p>Buggxit Couture cannot be held responsible for parcels lost during return transit.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Exchange Approval</h2>
                <p class="mb-2">All returned items are inspected before an exchange is approved.</p>
                <p class="mb-2">Once approved, exchanges are subject to:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Stock availability</li>
                    <li>Fabric availability</li>
                    <li>Product availability at the time of processing</li>
                </ul>
                <p class="mt-4 mb-2">If the requested replacement is unavailable, we may offer:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>An exchange for another item of equal value, or</li>
                    <li>Store credit for future use</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Important Ceremony Deadlines</h2>
                <div class="space-y-2">
                    <p>We understand that many Buggxit garments are purchased for weddings, ceremonies, and important cultural occasions.</p>
                    <p>We strongly encourage customers to place orders well in advance of event dates.</p>
                </div>
                <p class="mt-4 mb-2">Buggxit Couture cannot accept liability for missed events caused by:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Late order placement</li>
                    <li>Courier delays</li>
                    <li>Incorrect delivery information</li>
                    <li>Events outside our reasonable control</li>
                </ul>
                <p class="mt-4">Please plan early where possible.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Processing Time</h2>
                <div class="space-y-2">
                    <p>Please allow 3 to 7 business days for exchange processing after we receive and inspect the returned item.</p>
                    <p>Processing times may be longer during busy periods.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Contact Information</h2>
                <div class="space-y-2">
                    <p>For exchange requests or return-related questions, contact us at:</p>
                    <p>Buggxit Couture<br>Email: <a href="mailto:info@buggxit.store" class="text-gold hover:text-gold-bright transition-colors">info@buggxit.store</a></p>
                </div>
                <p class="mt-4 mb-2">Please include:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Your order number</li>
                    <li>The item name</li>
                    <li>Reason for the exchange request</li>
                    <li>Supporting images where relevant</li>
                </ul>
            </div>

        </div>
    </section>
@endsection
