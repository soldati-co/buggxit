@extends('layouts.app')

@section('title', 'About Buggxit Couture | Our Story, Our Craft')

@section('meta_description', 'Founded in 2018 by Siyasanga Magabuko-Soldati in Durban, Buggxit Couture celebrates Southern African heritage through contemporary traditional fashion.')

@section('content')
    {{-- Hero Section --}}
    <section class="relative mb-20 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gold/5 rounded-full blur-3xl"></div>

        <div
            class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-16 md:py-24">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto">
                <div class="max-w-4xl mx-auto text-center">
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-gold/10 text-gold border border-gold/30 mb-6 backdrop-blur-sm">
                        <span class="w-2 h-2 bg-gold rounded-full mr-2 animate-pulse"></span>
                        Since 2018 · Southern African Heritage Fashion
                    </span>

                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-bone mb-6 leading-tight">
                        Dressed in <span class="text-gold block mt-2">Culture & Confidence</span>
                    </h1>

                    <p class="text-xl text-bone-dim mb-10 max-w-2xl mx-auto">
                        Buggxit Couture creates ceremony-ready traditional wear and casual wear for the modern
                        woman. Rooted in Southern African heritage. Ready when she is.
                    </p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-ink-raised to-transparent pointer-events-none">
        </div>
    </section>

    {{-- Story Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 overflow-hidden">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-gold text-sm font-semibold uppercase tracking-wider">Our Origin</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-bone mt-2 mb-6">
                        The <span class="text-gold">Story</span> Behind BUGGXIT
                    </h2>
                    <div class="space-y-5 text-bone-dim leading-relaxed">
                        <p>
                            BUGGXIT started with a nickname. Her brother playfully called her Buggz — because of
                            her big teeth, a nod to Bugs Bunny. What began as a childhood tease became a source of
                            identity. The name stuck, grew, and eventually became the brand: BUGGXIT. A reminder
                            that what makes you different is exactly what makes you remarkable.
                        </p>
                        <p>
                            Siyasanga Soldati has wanted to be a fashion designer since she was nine years old.
                            She started Buggxit Couture in 2018, during her college years, when money was tight
                            and the dream still felt far away. She could not always afford the basics. But the
                            desire to create beautiful things, to dress women with intention, never left her.
                        </p>
                        <p>
                            So she started where she was. And she kept going.
                        </p>
                        <p>
                            Today, Buggxit Couture is a nationally recognised traditional fashion brand, built
                            ceremony by ceremony on the values of resilience, cultural pride, and real
                            craftsmanship.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div class="border-2 border-gold p-4 rounded-2xl relative z-10 bg-ink-raised/50">
                        <div class="w-full h-80 flex items-center justify-center bg-ink-raised2 rounded-xl">
                            <i class="fas fa-gem text-8xl text-gold/70 transform rotate-12"></i>
                        </div>
                    </div>
                    <div class="absolute -top-5 -left-5 w-full h-full border-2 border-line rounded-2xl -z-0"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Philosophy Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-gold text-sm font-semibold uppercase tracking-wider">Our Philosophy</span>
            <h2 class="text-3xl md:text-4xl font-bold text-bone mt-2">
                Where <span class="text-gold">Heritage</span> Meets Style
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $philosophies = [
                    [
                        'icon' => 'fa-cube',
                        'title' => 'Cultural Pride',
                        'desc' => 'Heritage is not a trend here. Every garment we make is an act of cultural celebration.',
                    ],
                    [
                        'icon' => 'fa-leaf',
                        'title' => 'Ceremony Ready',
                        'desc' => 'She should never have to wait weeks to look beautiful. Our pieces are ready when the occasion calls.',
                    ],
                    [
                        'icon' => 'fa-hands',
                        'title' => 'Real Craftsmanship',
                        'desc' => 'Hand-finished pieces, authentic details, and quality that holds up long after the ceremony ends.',
                    ],
                    [
                        'icon' => 'fa-infinity',
                        'title' => 'Made for Her',
                        'desc' => 'Confident, busy, proud of her roots. Every design decision is made with her in mind.',
                    ],
                ];
            @endphp
            @foreach ($philosophies as $item)
                <div
                    class="group bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl p-6 hover:border-gold/50 transition-all duration-500 hover:shadow-2xl hover:shadow-gold/10">
                    <div
                        class="w-14 h-14 mb-5 bg-gold/10 rounded-2xl flex items-center justify-center group-hover:bg-gold/20 transition-colors">
                        <i class="fas {{ $item['icon'] }} text-2xl text-gold"></i>
                    </div>
                    <h3 class="text-bone font-semibold text-xl mb-2 group-hover:text-gold transition-colors">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-bone-dim leading-relaxed">
                        {{ $item['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <div class="inline-block bg-ink-raised/50 backdrop-blur-sm border border-line rounded-2xl p-6 max-w-2xl">
                <p class="text-bone-dim italic text-lg mb-3">
                    "Fashion is more than clothing. It is a celebration of culture,
                    confidence, and creativity. My mission is to help every woman feel
                    proud and empowered in every piece she wears."
                </p>
                <p class="text-gold font-semibold">— Siyasanga Magabuko-Soldati, Founder & Creative Director</p>
            </div>
        </div>
    </section>

    {{-- Craftsmanship Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div
            class="bg-gradient-to-br from-ink-raised2 to-ink-raised border border-line rounded-3xl p-8 md:p-12 overflow-hidden">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1 row-span-2">
                            <div
                                class="h-64 bg-ink-raised/50 border border-line rounded-2xl flex items-center justify-center">
                                <i class="fas fa-ruler-combined text-5xl text-gold/70"></i>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div
                                class="h-28 bg-ink-raised/50 border border-line rounded-2xl flex items-center justify-center">
                                <i class="fas fa-cut text-3xl text-gold/70"></i>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div
                                class="h-28 bg-ink-raised/50 border border-line rounded-2xl flex items-center justify-center">
                                <i class="fas fa-seedling text-3xl text-gold/70"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <span class="text-gold text-sm font-semibold uppercase tracking-wider">The Craft</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-bone mt-2 mb-6">
                        Made with <span class="text-gold block mt-2">Intention</span>
                    </h2>
                    <p class="text-bone-dim mb-6 leading-relaxed">
                        Each piece leaves our studio only when it is right. No shortcuts. No mass production.
                    </p>
                    <ul class="space-y-3 text-bone-dim">
                        <li class="flex items-start gap-2"><span class="text-gold font-bold">✓</span> Authentic
                            Shweshwe and 3 Cats fabrics, sourced with care</li>
                        <li class="flex items-start gap-2"><span class="text-gold font-bold">✓</span> Every pattern
                            hand-cut to shape</li>
                        <li class="flex items-start gap-2"><span class="text-gold font-bold">✓</span> Hand-finished
                            seams and cultural detailing</li>
                        <li class="flex items-start gap-2"><span class="text-gold font-bold">✓</span> Quality-checked
                            before every dispatch</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Values Section (Commitments) --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-gold text-sm font-semibold uppercase tracking-wider">Our Commitments</span>
            <h2 class="text-3xl md:text-4xl font-bold text-bone mt-2">
                Built on <span class="text-gold">Trust & Craft</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $commitments = [
                    [
                        'num' => '01',
                        'title' => 'Ready When You Need It',
                        'desc' =>
                            'Our ready-made collections are designed so you never have to choose between quality and convenience. Shop and receive — no long waits.',
                    ],
                    [
                        'num' => '02',
                        'title' => 'Honest Communication',
                        'desc' =>
                            'We keep you updated from the moment you order to the moment it arrives. No chasing. No guessing.',
                    ],
                    [
                        'num' => '03',
                        'title' => 'Quality You Can Feel',
                        'desc' =>
                            'Every piece is checked before it leaves us. If something is not right, we make it right.',
                    ],
                ];
            @endphp
            @foreach ($commitments as $item)
                <div
                    class="text-center p-6 bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl hover:border-gold/50 transition-all duration-300">
                    <div
                        class="w-20 h-20 mx-auto mb-5 bg-gold/10 rounded-full flex items-center justify-center border-2 border-gold">
                        <span class="text-2xl font-bold text-gold">{{ $item['num'] }}</span>
                    </div>
                    <h3 class="text-bone font-semibold text-xl mb-3">{{ $item['title'] }}</h3>
                    <p class="text-bone-dim leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                @php
                    $stats = [
                        ['value' => '2018', 'label' => 'Year Founded'],
                        ['value' => '7+', 'label' => 'Years of Craftsmanship'],
                        ['value' => '5', 'label' => 'Signature Collections'],
                        ['value' => 'SA-Wide', 'label' => 'Nationwide Delivery'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-gold mb-2">{{ $stat['value'] }}</div>
                        <div class="text-bone-dim text-sm uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div
            class="relative bg-gradient-to-br from-ink-raised2 to-ink-raised border border-line rounded-3xl p-8 md:p-12 overflow-hidden text-center">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-bone mb-6">
                    Ready to Find <span class="text-gold block mt-2">Your Piece?</span>
                </h2>
                <p class="text-bone-dim text-lg mb-10">
                    Browse our collections and find ceremony-ready traditional wear designed for the woman who
                    carries her culture with confidence.
                </p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <a href="{{ route('products.index') }}"
                        class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-gold to-gold-dim text-ink font-bold rounded-full hover:from-gold-bright hover:to-gold transition-all duration-300 shadow-2xl shadow-gold/25">
                        Shop Ready-Made
                        <svg class="w-5 h-5 ml-3 group-hover:translate-x-1.5 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="{{ route('newarrival') }}"
                        class="group inline-flex items-center justify-center px-8 py-4 bg-ink-raised2/50 backdrop-blur-sm border border-line text-bone-dim font-bold rounded-full hover:bg-ink-raised2 hover:text-bone hover:border-gold/50 transition-all duration-300">
                        See New Arrivals
                        <svg class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
