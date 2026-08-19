@extends('layouts.app')

@section('title', 'Privacy Policy – BUGGXIT Couture')

@section('content')
    <section class="relative mb-16 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-14 md:py-20">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-bone mb-3">Privacy Policy</h1>
                <p class="text-bone-dim">Last updated {{ now()->format('F Y') }}</p>
            </div>
        </div>
    </section>

    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 space-y-10 text-bone-dim leading-relaxed">

            <div>
                <p>BUGGXIT Couture ("we", "us", "our") respects your privacy and is committed to protecting the personal information you share with us when you use this website. This policy explains what we collect, why, and the choices you have. It's written to comply with South Africa's Protection of Personal Information Act (POPIA).</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Information we collect</h2>
                <ul class="list-disc list-inside space-y-2">
                    <li>Contact details you give us: name, email address, phone number, and shipping/billing address.</li>
                    <li>Order information: items purchased, order value, and order status.</li>
                    <li>Account information, if you register: your name, email, and encrypted password.</li>
                    <li>Messages you send us through the contact form.</li>
                    <li>Basic technical information (such as browser type and IP address) collected automatically for security and performance.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">How we use your information</h2>
                <ul class="list-disc list-inside space-y-2">
                    <li>To process and fulfil your orders, including arranging delivery.</li>
                    <li>To communicate with you about your order, account, or enquiries.</li>
                    <li>To improve our products, services, and website experience.</li>
                    <li>To meet our legal and accounting obligations.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Payment information</h2>
                <p>All payments are processed securely by <strong class="text-bone">PayFast</strong>, a licensed South African payment gateway. We do not receive or store your card details — they are entered directly on PayFast's secure payment page and handled under PayFast's own privacy and security policies.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Sharing your information</h2>
                <p>We do not sell your personal information. We share it only with the service providers that help us run this store — for example, our payment processor (PayFast), hosting and database providers, and delivery couriers — and only to the extent needed to provide their service to us.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Cookies</h2>
                <p>We use essential cookies to keep you signed in and to remember the contents of your shopping cart. We do not use cookies for third-party advertising.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Data retention</h2>
                <p>We keep your order and account information for as long as necessary to fulfil the purposes described above, and to meet our legal, tax, and accounting requirements.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Your rights</h2>
                <p>Under POPIA, you have the right to request access to, correction of, or deletion of your personal information, and to object to certain uses of it. To exercise any of these rights, please contact us using the details below.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Contact us</h2>
                <p>If you have any questions about this policy or how we handle your information, please get in touch via our <a href="{{ route('contact') }}" class="text-gold hover:text-gold-bright transition-colors">Contact page</a>.</p>
            </div>

        </div>
    </section>
@endsection
