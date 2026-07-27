@extends('layouts.app')

@section('title', 'Buggxit Couture')

@section('content')
    <div x-data="landingPage()" x-init="init()" x-cloak class="space-y-20">
        <template x-if="loading">
            <div class="space-y-8">
                <div class="h-[60vh] rounded-3xl bg-gray-900/90 animate-pulse"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="n in 4" :key="n">
                        <div class="h-44 rounded-3xl bg-gray-900/90 animate-pulse"></div>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="error">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto py-16 text-center text-red-400 bg-black/80 border border-red-500/20 rounded-3xl">
                <p x-text="error"></p>
            </div>
        </template>

        <template x-if="!loading && !error">
            <section class="relative w-full min-h-[60vh] overflow-hidden rounded-3xl bg-gray-900/90">
                <template x-if="heroSlides.length">
                    <div class="relative">
                        <template x-for="(slide, index) in heroSlides" :key="slide.id">
                            <div x-show="activeSlide === index" x-transition class="absolute inset-0 w-full h-full">
                                <img :src="slide.image_url" :alt="slide.alt_text"
                                    class="absolute inset-0 w-full h-full object-cover"
                                    loading="lazy">
                                <div class="absolute inset-0 bg-black/50"></div>
                                <div class="relative z-10 flex h-full items-center max-w-6xl mx-auto px-6 lg:px-16">
                                    <div class="text-white max-w-2xl">
                                        <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-4" x-text="slide.headline || slide.title"></h1>
                                        <p class="text-lg md:text-xl text-gray-200 mb-8" x-text="slide.subheading"></p>
                                        <a x-show="slide.cta_text && slide.cta_url" :href="slide.cta_url"
                                            class="inline-flex items-center px-8 py-3 bg-yellow-500 text-gray-900 font-semibold rounded-lg hover:bg-yellow-400 transition">
                                            <span x-text="slide.cta_text"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="absolute inset-x-0 bottom-6 flex justify-center space-x-2 z-20">
                            <template x-for="(slide, index) in heroSlides" :key="slide.id">
                                <button type="button" @click="goToHero(index)"
                                    :class="activeSlide === index ? 'bg-yellow-500' : 'bg-white/50'"
                                    class="w-3 h-3 rounded-full transition-colors"></button>
                            </template>
                        </div>

                        <button @click="prevHero()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-black/50 text-white hover:bg-black/70 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="nextHero()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-black/50 text-white hover:bg-black/70 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </template>
            </section>

            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mt-5 mb-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <p class="text-yellow-500 text-sm font-semibold uppercase tracking-wider">Collections</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mt-1">
                            Find <span class="text-yellow-500">Your</span> silhouette.
                        </h2>
                        <p class="text-gray-400 text-lg mt-2">Every collection is designed for a moment. Find the one that fits yours.</p>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="inline-block px-8 py-3 mt-4 md:mt-0 bg-yellow-500 text-gray-900 font-semibold rounded-lg hover:bg-yellow-400 transition">
                        View All Categories
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-6">
                    <template x-if="categories.length">
                        <template x-for="category in categories" :key="category.id">
                            <a :href="`/products?category=${encodeURIComponent(category.slug)}`"
                                class="group relative bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-yellow-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/10 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/0 to-yellow-500/0 group-hover:from-yellow-500/5 group-hover:to-transparent transition-all duration-700"></div>
                                <div class="relative z-10">
                                    <div class="w-14 h-14 mb-4 bg-yellow-500/10 rounded-2xl flex items-center justify-center group-hover:bg-yellow-500/20 transition-colors">
                                        <i :class="category.icon || 'fas fa-tshirt'" class="text-2xl text-yellow-500"></i>
                                    </div>
                                    <h3 class="text-white font-semibold text-lg mb-1 group-hover:text-yellow-500 transition-colors" x-text="category.name"></h3>
                                    <p class="text-gray-400 text-sm" x-text="category.description || ''"></p>
                                    <p class="text-gray-500 text-sm mt-1">
                                        <span x-text="category.dresses_count || 0"></span>
                                        <span x-text="category.dresses_count === 1 ? 'dress' : 'dresses'"></span>
                                    </p>
                                </div>
                            </a>
                        </template>
                    </template>
                    <template x-if="!categories.length">
                        <div class="col-span-full text-center py-12 text-gray-500">
                            <i class="fas fa-tshirt text-4xl mb-4 text-gray-700"></i>
                            <p>Collections coming soon.</p>
                        </div>
                    </template>
                </div>
            </section>

            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white">
                            ✦ <span class="text-yellow-500">Buggz's Favourites</span>
                        </h2>
                        <p class="text-gray-400 text-lg mt-2">The finest of our current collection.</p>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="hidden md:flex items-center text-yellow-500 hover:text-yellow-400 font-medium group">
                        Discover all
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="dress in featured" :key="dress.id">
                        <div class="group bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl overflow-hidden hover:border-yellow-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/10">
                            <div class="relative h-72 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-10"></div>
                                <img :src="dress.main_image_url" :alt="dress.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <template x-if="dress.is_featured">
                                    <span class="absolute top-4 left-4 z-20 px-3 py-1.5 text-xs font-medium bg-yellow-500/90 text-gray-900 rounded-full backdrop-blur-sm">
                                        <i class="fas fa-star mr-1"></i> Featured
                                    </span>
                                </template>
                            </div>
                            <div class="p-5">
                                <div class="text-xs text-yellow-500 uppercase tracking-wider mb-1" x-text="dress.categories?.map(c => c.name).join(', ') || 'Collection'"></div>
                                <h3 class="font-semibold text-white text-lg mb-2 line-clamp-1" x-text="dress.name"></h3>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-yellow-500">R<span x-text="dress.price.toLocaleString()"></span></span>
                                    <button type="button" @click.prevent="addToCart(dress.id)"
                                        class="p-3 bg-yellow-500/10 rounded-xl text-yellow-500 hover:bg-yellow-500 hover:text-gray-900 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
                <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-3xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
                        <div>
                            <span class="text-yellow-500 text-sm font-semibold uppercase tracking-wider">Fresh off the needle</span>
                            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">New Arrivals</h2>
                        </div>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center text-yellow-500 hover:text-yellow-400 font-medium mt-4 md:mt-0 group">
                            View all new dresses
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <template x-for="dress in newArrivals" :key="dress.id">
                            <a :href="getProductUrl(dress)" class="group">
                                <div class="relative h-56 rounded-xl overflow-hidden border border-gray-800 group-hover:border-yellow-500/50 transition-colors mb-3">
                                    <img :src="dress.main_image_url" :alt="dress.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <h4 class="text-white font-medium group-hover:text-yellow-500 transition-colors" x-text="dress.name"></h4>
                                <p class="text-yellow-500 font-bold mt-1">R<span x-text="dress.price.toLocaleString()"></span></p>
                            </a>
                        </template>
                    </div>
                </div>
            </section>

            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
                <div class="relative bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-3xl p-8 md:p-12 overflow-hidden">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-yellow-500/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 max-w-4xl mx-auto text-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 mb-6">
                            <i class="fas fa-ruler-combined mr-2"></i> Made to measure
                        </span>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">Your vision, <span class="text-yellow-500">perfectly fitted</span></h2>
                        <p class="text-gray-300 text-lg mb-10 max-w-2xl mx-auto">From consultation to delivery – every dress is hand‑cut, hand‑sewn, and fitted to your unique measurements. No mass production, only singular pieces.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                            <div class="flex flex-col items-center p-5">
                                <div class="w-16 h-16 mb-4 bg-yellow-500/10 rounded-full flex items-center justify-center border border-yellow-500/30">
                                    <i class="fas fa-comments text-2xl text-yellow-500"></i>
                                </div>
                                <h4 class="text-white font-semibold text-lg">1. Consult</h4>
                                <p class="text-gray-400 text-sm text-center">Share your style, fabric, and event.</p>
                            </div>
                            <div class="flex flex-col items-center p-5">
                                <div class="w-16 h-16 mb-4 bg-yellow-500/10 rounded-full flex items-center justify-center border border-yellow-500/30">
                                    <i class="fas fa-cut text-2xl text-yellow-500"></i>
                                </div>
                                <h4 class="text-white font-semibold text-lg">2. Craft</h4>
                                <p class="text-gray-400 text-sm text-center">Hand‑cut patterns, hand‑stitched detail.</p>
                            </div>
                            <div class="flex flex-col items-center p-5">
                                <div class="w-16 h-16 mb-4 bg-yellow-500/10 rounded-full flex items-center justify-center border border-yellow-500/30">
                                    <i class="fas fa-truck text-2xl text-yellow-500"></i>
                                </div>
                                <h4 class="text-white font-semibold text-lg">3. Deliver</h4>
                                <p class="text-gray-400 text-sm text-center">Quality checked, sent to your courier.</p>
                            </div>
                        </div>
                        <div class="mt-12">
                            <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-bold rounded-full hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 shadow-lg">
                                Start your bespoke order
                                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </div>
@endsection

@push('scripts')
    <script>
        function landingPage() {
            return {
                loading: true,
                error: null,
                heroSlides: [],
                categories: [],
                featured: [],
                newArrivals: [],
                activeSlide: 0,

                init() {
                    this.fetchHome();
                },

                async fetchHome() {
                    try {
                        const response = await fetch('{{ route('api.home.index', [], false) }}');
                        if (!response.ok) {
                            throw new Error('Unable to load home data.');
                        }
                        const json = await response.json();
                        this.heroSlides = json.hero_slides || [];
                        this.categories = json.categories || [];
                        this.featured = json.featured || [];
                        this.newArrivals = json.new_arrivals || [];
                    } catch (error) {
                        this.error = error.message || 'Something went wrong loading homepage content.';
                    } finally {
                        this.loading = false;
                    }
                },

                nextHero() {
                    if (!this.heroSlides.length) return;
                    this.activeSlide = (this.activeSlide + 1) % this.heroSlides.length;
                },

                prevHero() {
                    if (!this.heroSlides.length) return;
                    this.activeSlide = (this.activeSlide - 1 + this.heroSlides.length) % this.heroSlides.length;
                },

                goToHero(index) {
                    this.activeSlide = index;
                },

                async addToCart(productId) {
                    try {
                        const response = await fetch('{{ route('api.cart.add', [], false) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ product_id: productId, quantity: 1 }),
                        });
                        const data = await response.json();
                        if (data.success) {
                            document.querySelectorAll('.cart-count').forEach(el => {
                                el.textContent = data.cart_count;
                                el.classList.add('scale-150');
                                setTimeout(() => el.classList.remove('scale-150'), 300);
                            });
                        }
                    } catch (err) {
                        console.error(err);
                    }
                },

                getProductUrl(dress) {
                    return `/products/${encodeURIComponent(dress.slug || dress.id)}`;
                },
            };
        }
    </script>
@endpush