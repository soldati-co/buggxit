@extends('layouts.app')

@section('title', 'Shipping Policy – BUGGXIT Couture')

@section('content')
    <section class="relative mb-16 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-14 md:py-20">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-bone mb-3">Shipping Policy</h1>
                <p class="text-bone-dim">Effective Date: June 2026</p>
            </div>
        </div>
    </section>

    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 space-y-10 text-bone-dim leading-relaxed">

            <div class="space-y-2">
                <p>At Buggxit Couture, every order is prepared with care and attention to detail.</p>
                <p>We work to get your order to you safely and as quickly as possible. Please review our shipping terms before placing your order.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Shipping Locations</h2>
                <div class="space-y-2">
                    <p>We currently ship nationwide across South Africa.</p>
                    <p>If international shipping becomes available in future, this policy will be updated accordingly.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Courier Partners</h2>
                <p class="mb-2">We ship using trusted courier partners, including:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>The Courier Guy</li>
                    <li>PEP Paxi</li>
                </ul>
                <p class="mt-4">Courier selection may vary depending on parcel size, delivery location, and service availability.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Shipping Costs</h2>
                <p class="mb-2">Shipping costs are calculated at checkout.</p>
                <p class="mb-2">Delivery fees start from R150 and may vary based on:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Delivery location</li>
                    <li>Parcel size or weight</li>
                    <li>Courier selected</li>
                    <li>Special delivery requirements</li>
                </ul>
                <p class="mt-4">The final shipping fee will be shown before payment is completed.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Ready-Made Orders</h2>
                <p class="mb-2">Ready-made garments are processed and dispatched within 2 business days after payment confirmation.</p>
                <p class="mb-2">Business days exclude:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Saturdays</li>
                    <li>Sundays</li>
                    <li>South African public holidays</li>
                </ul>
                <div class="space-y-2 mt-4">
                    <p>Once dispatched, standard delivery typically takes 7 to 14 working days, depending on your location and courier service.</p>
                    <p>Remote or outlying areas may take longer.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Custom Orders</h2>
                <p class="mb-2">Custom garments require production time before shipping.</p>
                <p class="mb-2">Standard production time for custom orders is approximately 6 weeks from the date that:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Payment requirements are met</li>
                    <li>Measurements are confirmed</li>
                    <li>Design details are finalised</li>
                </ul>
                <div class="space-y-2 mt-4">
                    <p>Production timelines may vary depending on garment complexity, fabric availability, and order volume.</p>
                    <p>Estimated delivery begins only after production is complete and the order has been dispatched.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Order Tracking</h2>
                <div class="space-y-2">
                    <p>Once your order ships, you will receive tracking details via email or WhatsApp where available.</p>
                    <p>You may use the tracking number to monitor delivery progress directly with the courier.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Delivery Delays</h2>
                <p class="mb-2">While we work hard to meet delivery timelines, delays may happen due to circumstances outside our control.</p>
                <p class="mb-2">These may include:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Courier delays</li>
                    <li>Severe weather</li>
                    <li>Public unrest or strikes</li>
                    <li>High seasonal demand</li>
                    <li>Service disruptions in certain regions</li>
                </ul>
                <p class="mt-4">We appreciate your patience when these situations occur.</p>
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
                <h2 class="text-2xl font-bold text-bone mb-3">Incorrect Shipping Information</h2>
                <p class="mb-2">Customers are responsible for providing accurate shipping details.</p>
                <p class="mb-2">This includes:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Full name</li>
                    <li>Contact number</li>
                    <li>Delivery address</li>
                    <li>Postal or collection information</li>
                </ul>
                <div class="space-y-2 mt-4">
                    <p>Buggxit Couture is not responsible for delays or failed deliveries caused by incorrect or incomplete information provided at checkout.</p>
                    <p>Additional courier fees caused by address corrections or redelivery may be charged to the customer.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Lost or Damaged Parcels</h2>
                <div class="space-y-2">
                    <p>If your parcel arrives damaged or appears lost in transit, please contact us as soon as possible.</p>
                    <p>We will work with the courier partner to investigate and assist where possible.</p>
                    <p>Claims should be reported within 48 hours of delivery or expected delivery date.</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Contact Information</h2>
                <p>For shipping-related questions, contact us at:</p>
                <p>Buggxit Couture<br>Email: <a href="mailto:info@buggxit.store" class="text-gold hover:text-gold-bright transition-colors">info@buggxit.store</a></p>
            </div>

        </div>
    </section>
@endsection
