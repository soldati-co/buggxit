@extends('layouts.app')

@section('title', 'About BUGGXIT | Geometric Luxury Fashion')

@section('content')
    {{-- Hero Section --}}
    <section class="relative mb-20 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-yellow-500/5 rounded-full blur-3xl"></div>

        <div class="relative z-10 bg-gradient-to-br from-black via-gray-900 to-black border-y border-gray-800/50 py-16 md:py-24">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto">
                <div class="max-w-4xl mx-auto text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 mb-6 backdrop-blur-sm">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                        Since 2018 • Geometric Luxury
                    </span>

                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                        Redefining <span class="text-yellow-500 block mt-2">Geometric Elegance</span>
                    </h1>

                    <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                        At BUGGXIT Couture, we transform sharp angles and precise lines into wearable art.
                        Since 2018, we've been challenging conventional fashion with our geometric luxury collections.
                    </p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-black to-transparent pointer-events-none"></div>
    </section>

    {{-- Story Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-3xl p-8 md:p-12 overflow-hidden">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-yellow-500 text-sm font-semibold uppercase tracking-wider">Our Origin</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-6">
                        The <span class="text-yellow-500">Story</span> Behind BUGGXIT
                    </h2>
                    <div class="space-y-5 text-gray-300 leading-relaxed">
                        <p>
                            Founded by visionary designer Alexei Volkov, BUGGXIT emerged from a fascination with
                            architectural forms and their intersection with human movement. What began as a
                            graduate thesis project has evolved into a globally recognized luxury brand.
                        </p>
                        <p>
                            The name "BUGGXIT" derives from the concept of "breaking out of the box" –
                            challenging traditional fashion norms through angular precision and mathematical elegance.
                        </p>
                        <p>
                            Each collection is meticulously crafted, combining advanced tailoring techniques
                            with innovative materials to create pieces that are both structurally remarkable
                            and surprisingly comfortable.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div class="border-2 border-yellow-500 p-4 rounded-2xl relative z-10 bg-black/50">
                        <div class="w-full h-80 flex items-center justify-center bg-gray-900 rounded-xl">
                            <i class="fas fa-gem text-8xl text-yellow-500/70 transform rotate-12"></i>
                        </div>
                    </div>
                    <div class="absolute -top-5 -left-5 w-full h-full border-2 border-gray-700 rounded-2xl -z-0"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Philosophy Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-yellow-500 text-sm font-semibold uppercase tracking-wider">Our Design Philosophy</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">
                Where <span class="text-yellow-500">Precision</span> Meets Passion
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $philosophies = [
                    ['icon' => 'fa-cube', 'title' => 'Precision Geometry', 'desc' => 'Every angle is calculated, every line intentional. Clothing with the mathematical precision of architecture.'],
                    ['icon' => 'fa-leaf', 'title' => 'Sustainable Luxury', 'desc' => 'Ethical materials and zero-waste patterns. Luxury that doesn\'t cost the planet.'],
                    ['icon' => 'fa-hands', 'title' => 'Artisan Craft', 'desc' => 'Hand-finished by master tailors. Traditional techniques meet futuristic design.'],
                    ['icon' => 'fa-infinity', 'title' => 'Timeless Innovation', 'desc' => 'Pieces that defy trends, becoming heirlooms of the future.']
                ];
            @endphp
            @foreach($philosophies as $item)
                <div class="group bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-yellow-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/10">
                    <div class="w-14 h-14 mb-5 bg-yellow-500/10 rounded-2xl flex items-center justify-center group-hover:bg-yellow-500/20 transition-colors">
                        <i class="fas {{ $item['icon'] }} text-2xl text-yellow-500"></i>
                    </div>
                    <h3 class="text-white font-semibold text-xl mb-2 group-hover:text-yellow-500 transition-colors">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-gray-400 leading-relaxed">
                        {{ $item['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <div class="inline-block bg-black/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 max-w-2xl">
                <p class="text-gray-300 italic text-lg mb-3">
                    "Fashion is architecture: it is a matter of proportions. At BUGGXIT, we build clothing
                    that becomes a second skin of structured beauty."
                </p>
                <p class="text-yellow-500 font-semibold">— Alexei Volkov, Founder & Creative Director</p>
            </div>
        </div>
    </section>

    {{-- Craftsmanship Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-3xl p-8 md:p-12 overflow-hidden">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1 row-span-2">
                            <div class="h-64 bg-black/50 border border-gray-700 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-ruler-combined text-5xl text-yellow-500/70"></i>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="h-28 bg-black/50 border border-gray-700 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-cut text-3xl text-yellow-500/70"></i>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="h-28 bg-black/50 border border-gray-700 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-seedling text-3xl text-yellow-500/70"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <span class="text-yellow-500 text-sm font-semibold uppercase tracking-wider">The Craftsmanship</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-6">
                        127 Steps to <span class="text-yellow-500">Perfection</span>
                    </h2>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        Every BUGGXIT garment undergoes 127 precise steps from concept to completion.
                        Our atelier in Milan houses state-of-the-art laser cutting technology alongside
                        traditional tailoring stations.
                    </p>
                    <ul class="space-y-3 text-gray-300">
                        <li class="flex items-start gap-2"><span class="text-yellow-500 font-bold">✓</span> Italian wool and Japanese technical fabrics</li>
                        <li class="flex items-start gap-2"><span class="text-yellow-500 font-bold">✓</span> Laser precision cutting for perfect geometry</li>
                        <li class="flex items-start gap-2"><span class="text-yellow-500 font-bold">✓</span> Hand-finished seams and edges</li>
                        <li class="flex items-start gap-2"><span class="text-yellow-500 font-bold">✓</span> 48-hour quality inspection for each piece</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Values Section (Commitments) --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-yellow-500 text-sm font-semibold uppercase tracking-wider">Our Commitments</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">
                Built on <span class="text-yellow-500">Trust & Integrity</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $commitments = [
                    ['num' => '01', 'title' => 'Ethical Production', 'desc' => 'Direct relationships with suppliers, fair wages, and safe working conditions throughout our supply chain.'],
                    ['num' => '02', 'title' => 'Zero Waste Initiative', 'desc' => 'Pattern-making engineered to minimize fabric waste. Remaining materials repurposed or donated.'],
                    ['num' => '03', 'title' => 'Lifetime Care', 'desc' => 'Complimentary lifetime alterations and repair services. True luxury is designed to last.']
                ];
            @endphp
            @foreach($commitments as $item)
                <div class="text-center p-6 bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl hover:border-yellow-500/50 transition-all duration-300">
                    <div class="w-20 h-20 mx-auto mb-5 bg-yellow-500/10 rounded-full flex items-center justify-center border-2 border-yellow-500">
                        <span class="text-2xl font-bold text-yellow-500">{{ $item['num'] }}</span>
                    </div>
                    <h3 class="text-white font-semibold text-xl mb-3">{{ $item['title'] }}</h3>
                    <p class="text-gray-400 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-3xl p-8 md:p-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                @php
                    $stats = [
                        ['value' => '2018', 'label' => 'Year Founded'],
                        ['value' => '42+', 'label' => 'Countries Served'],
                        ['value' => '127', 'label' => 'Crafting Steps'],
                        ['value' => '98%', 'label' => 'Recycled Materials']
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-yellow-500 mb-2">{{ $stat['value'] }}</div>
                        <div class="text-gray-400 text-sm uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="relative bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-3xl p-8 md:p-12 overflow-hidden text-center">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-yellow-500/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Experience <span class="text-yellow-500">Geometric Luxury</span>
                </h2>
                <p class="text-gray-300 text-lg mb-10">
                    Join thousands who have transformed their wardrobe with architectural elegance.
                    Explore our collections and discover why precision never goes out of style.
                </p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-bold rounded-full hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 shadow-2xl shadow-yellow-500/25">
                        Explore Collections
                        <svg class="w-5 h-5 ml-3 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="group inline-flex items-center justify-center px-8 py-4 bg-gray-800/50 backdrop-blur-sm border border-gray-700 text-gray-300 font-bold rounded-full hover:bg-gray-800 hover:text-white hover:border-yellow-500/50 transition-all duration-300">
                        View New Arrivals
                        <svg class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection