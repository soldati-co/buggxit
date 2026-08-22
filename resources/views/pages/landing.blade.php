@extends('layouts.app')

@section('title', 'Buggxit Couture | Traditional Ceremony Wear for the Modern African Woman')

@section('meta_description', 'Shop ready-made Makoti dresses, Shweshwe designs, and African ceremony wear. Crafted with cultural pride and delivered nationwide across South Africa.')

@section('content')
    @include('components.popup-banner')

    @include('partials.hero')

    @include('components.trust-strip')

    <div x-data="landingPage()" x-init="init()" x-cloak>
        <template x-if="loading">
            <div class="space-y-8">
                <div class="h-[60vh] rounded-3xl bg-ink-raised2/90 animate-pulse"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="n in 4" :key="n">
                        <div class="h-44 rounded-3xl bg-ink-raised2/90 animate-pulse"></div>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="error">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto py-16 text-center text-bad bg-ink-raised/80 border border-bad/20 rounded-3xl">
                <p x-text="error"></p>
            </div>
        </template>

        <template x-if="!loading && !error">
          <div>
            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mt-5 mb-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <p class="text-gold text-sm font-semibold uppercase tracking-wider">Collections</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-bone mt-1">
                            Find <span class="text-gold">Your</span> silhouette.
                        </h2>
                        <p class="text-bone-dim text-lg mt-2">Every collection is designed for a moment. Find the one that fits yours.</p>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="inline-block px-8 py-3 mt-4 md:mt-0 bg-gold text-ink font-semibold rounded-lg hover:bg-gold-bright transition">
                        View All Categories
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-6">
                    <template x-if="categories.length">
                        <template x-for="category in categories" :key="category.id">
                            <a :href="`/products?category=${encodeURIComponent(category.slug)}`"
                                class="group relative bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl p-6 hover:border-gold/50 transition-all duration-500 hover:shadow-2xl hover:shadow-gold/10 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-gold/0 to-gold/0 group-hover:from-gold/5 group-hover:to-transparent transition-all duration-700"></div>
                                <div class="relative z-10">
                                    <div class="w-14 h-14 mb-4 bg-gold/10 rounded-2xl flex items-center justify-center group-hover:bg-gold/20 transition-colors">
                                        <i :class="category.icon || 'fas fa-tshirt'" class="text-2xl text-gold"></i>
                                    </div>
                                    <h3 class="text-bone font-semibold text-lg mb-1 group-hover:text-gold transition-colors" x-text="category.name"></h3>
                                    <p class="text-bone-dim text-sm" x-text="category.description || ''"></p>
                                    <p class="text-bone-faint text-sm mt-1">
                                        <span x-text="category.dresses_count || 0"></span>
                                        <span x-text="category.dresses_count === 1 ? 'dress' : 'dresses'"></span>
                                    </p>
                                </div>
                            </a>
                        </template>
                    </template>
                    <template x-if="!categories.length">
                        <div class="col-span-full text-center py-12 text-bone-faint">
                            <i class="fas fa-tshirt text-4xl mb-4 text-bone-faint"></i>
                            <p>Collections coming soon.</p>
                        </div>
                    </template>
                </div>
            </section>

            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-bone">
                            ✦ <span class="text-gold">Buggz's Favourites</span>
                        </h2>
                        <p class="text-bone-dim text-lg mt-2">Handpicked by our founder.</p>
                        <p class="text-bone-faint text-sm mt-1">These are the pieces Siyasanga reaches for first.</p>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="hidden md:flex items-center text-gold hover:text-gold-bright font-medium group">
                        Browse All Pieces
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="dress in featured" :key="dress.id">
                        <div class="group bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl overflow-hidden hover:border-gold/50 transition-all duration-500 hover:shadow-2xl hover:shadow-gold/10">
                            <a :href="getProductUrl(dress)" class="block">
                                <div class="relative aspect-square overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-t from-ink-raised/80 via-transparent to-transparent z-10"></div>
                                    <img :src="dress.card_image_url" :alt="dress.name" class="w-full h-full object-contain">
                                    <template x-if="dress.is_featured">
                                        <span class="absolute top-4 left-4 z-20 px-3 py-1.5 text-xs font-medium bg-gold/90 text-ink rounded-full backdrop-blur-sm">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                    </template>
                                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-20"></div>
                                </div>
                                <div class="px-5 pt-5">
                                    <div class="text-xs text-gold uppercase tracking-wider mb-1" x-text="dress.categories?.map(c => c.name).join(', ') || 'Collection'"></div>
                                    <h3 class="font-semibold text-bone text-lg mb-2 line-clamp-1 group-hover:text-gold transition-colors" x-text="dress.name"></h3>
                                </div>
                            </a>
                            <div class="px-5 pb-5">
                                <span class="text-2xl font-bold font-numeric text-gold">R<span x-text="dress.price.toLocaleString()"></span></span>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
                <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
                        <div>
                            <span class="text-gold text-sm font-semibold uppercase tracking-wider">Fresh off the needle</span>
                            <h2 class="text-3xl md:text-4xl font-bold text-bone mt-2">New Arrivals</h2>
                        </div>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center text-gold hover:text-gold-bright font-medium mt-4 md:mt-0 group">
                            View all new dresses
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <template x-for="dress in newArrivals" :key="dress.id">
                            <a :href="getProductUrl(dress)" class="group">
                                <div class="relative aspect-square rounded-xl overflow-hidden border border-line group-hover:border-gold/50 transition-colors mb-3">
                                    <img :src="dress.card_image_new_arrivals_url" :alt="dress.name" class="w-full h-full object-contain">
                                </div>
                                <h4 class="text-bone font-medium group-hover:text-gold transition-colors" x-text="dress.name"></h4>
                                <p class="text-gold font-bold font-numeric mt-1">R<span x-text="dress.price.toLocaleString()"></span></p>
                            </a>
                        </template>
                    </div>
                </div>
            </section>

            <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
                <div class="relative bg-gradient-to-br from-ink-raised2 to-ink-raised border border-line rounded-3xl p-8 md:p-12 overflow-hidden">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-gold/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 max-w-4xl mx-auto text-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-gold/10 text-gold border border-gold/30 mb-6">
                            <i class="fas fa-ruler-combined mr-2"></i> Limited Custom Orders
                        </span>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-bone mb-6">Something <span class="text-gold">Special</span> in Mind?</h2>
                        <p class="text-bone-dim text-lg mb-10 max-w-2xl mx-auto">For milestone celebrations, or a vision that is entirely your own. Hand-cut, hand-sewn, built around your exact measurements. We take a maximum of 2 custom clients per month.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                            <div class="flex flex-col items-center p-5">
                                <div class="w-16 h-16 mb-4 bg-gold/10 rounded-full flex items-center justify-center border border-gold/30">
                                    <i class="fas fa-comments text-2xl text-gold"></i>
                                </div>
                                <h4 class="text-bone font-semibold text-lg">1. Consult</h4>
                                <p class="text-bone-dim text-sm text-center">Tell us your vision, fabric, and occasion.</p>
                            </div>
                            <div class="flex flex-col items-center p-5">
                                <div class="w-16 h-16 mb-4 bg-gold/10 rounded-full flex items-center justify-center border border-gold/30">
                                    <i class="fas fa-cut text-2xl text-gold"></i>
                                </div>
                                <h4 class="text-bone font-semibold text-lg">2. Craft</h4>
                                <p class="text-bone-dim text-sm text-center">Hand‑cut patterns. Every stitch intentional.</p>
                            </div>
                            <div class="flex flex-col items-center p-5">
                                <div class="w-16 h-16 mb-4 bg-gold/10 rounded-full flex items-center justify-center border border-gold/30">
                                    <i class="fas fa-truck text-2xl text-gold"></i>
                                </div>
                                <h4 class="text-bone font-semibold text-lg">3. Deliver</h4>
                                <p class="text-bone-dim text-sm text-center">Quality-checked and with you within 6 weeks.</p>
                            </div>
                        </div>
                        <div class="mt-12">
                            <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gold to-gold-dim text-ink font-bold rounded-full hover:from-gold-bright hover:to-gold transition-all duration-300 shadow-lg">
                                Enquire About Custom
                                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
          </div>
        </template>
    </div>

    @include('components.follow-along')
@endsection

@push('scripts')
    <script>
        function landingPage() {
            return {
                loading: true,
                error: null,
                categories: [],
                featured: [],
                newArrivals: [],

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
                        this.categories = json.categories || [];
                        this.featured = json.featured || [];
                        this.newArrivals = json.new_arrivals || [];
                    } catch (error) {
                        this.error = error.message || 'Something went wrong loading homepage content.';
                    } finally {
                        this.loading = false;
                    }
                },

                getProductUrl(dress) {
                    return `/products/${encodeURIComponent(dress.slug || dress.id)}`;
                },
            };
        }
    </script>
@endpush