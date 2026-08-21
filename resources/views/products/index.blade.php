@extends('layouts.app')

@section('title', 'All Dresses – BUGGXIT Couture')

@section('content')
    <div x-data="productsPage(@json($newArrivals ?? false))" x-init="init()" x-cloak class="space-y-12 pb-16">
        <section class="relative mb-20 overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="relative z-10 py-16 md:py-24">
                <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-gold/10 text-gold border border-gold/30 mb-4 backdrop-blur-sm">
                            <i class="fas fa-tshirt mr-2"></i>
                            <span x-text="resultText"></span>
                        </span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-bone mb-4">
                            <template x-if="newArrivals">New Arrivals</template>
                            <template x-if="!newArrivals">Our <span class="text-gold">Collections</span></template>
                        </h1>
                        <p class="text-lg text-bone-dim max-w-2xl">
                            Handcrafted traditional ceremony attire, made to measure.
                            Each piece is a unique expression of heritage and elegance.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-12">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-bone font-semibold text-lg flex items-center">
                        <i class="fas fa-filter mr-2 text-gold"></i>
                        Explore all collections
                    </h2>
                    <template x-if="category">
                        <p class="text-bone-dim text-sm mt-2">Filtering by category: <span class="text-bone" x-text="category"></span></p>
                    </template>
                </div>
                <button type="button" @click="refresh()" class="inline-flex items-center gap-2 px-4 py-2 bg-ink-raised2/80 border border-line rounded-lg text-bone-dim hover:text-bone hover:border-gold/50 transition">
                    <i class="fas fa-sync-alt text-gold"></i>
                    Refresh
                </button>
            </div>
        </section>

        <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-16">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <p class="text-bone-dim text-sm">
                    Showing <span class="text-bone font-medium" x-text="pagination.from || 0"></span>
                    – <span class="text-bone font-medium" x-text="pagination.to || 0"></span>
                    of <span class="text-gold font-medium" x-text="pagination.total || 0"></span> dresses
                </p>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone-dim hover:text-bone hover:border-gold/50 transition-colors text-sm">
                        <i class="fas fa-sort-amount-down text-gold"></i>
                        <span x-text="'Sort by: ' + sortLabel()"></span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-2 w-48 bg-ink-raised/90 backdrop-blur-sm border border-line rounded-lg shadow-xl z-50 overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent"></div>
                        <a href="#" @click.prevent="selectSort('latest')" class="block px-4 py-2.5 text-sm hover:bg-ink-raised2/50 hover:text-gold transition-colors" :class="sort === 'latest' ? 'text-gold' : 'text-bone-dim'">Latest</a>
                        <a href="#" @click.prevent="selectSort('price_asc')" class="block px-4 py-2.5 text-sm hover:bg-ink-raised2/50 hover:text-gold transition-colors" :class="sort === 'price_asc' ? 'text-gold' : 'text-bone-dim'">Price: Low to High</a>
                        <a href="#" @click.prevent="selectSort('price_desc')" class="block px-4 py-2.5 text-sm hover:bg-ink-raised2/50 hover:text-gold transition-colors" :class="sort === 'price_desc' ? 'text-gold' : 'text-bone-dim'">Price: High to Low</a>
                    </div>
                </div>
            </div>

            <template x-if="loading">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="n in 8" :key="n">
                        <div class="space-y-4">
                            <div class="h-72 rounded-3xl bg-ink-raised2/90 animate-pulse"></div>
                            <div class="h-8 rounded-xl bg-ink-raised2/90 animate-pulse"></div>
                            <div class="h-6 rounded-xl bg-ink-raised2/90 animate-pulse"></div>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="!loading && dresses.length">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="dress in dresses" :key="dress.id">
                        <div class="group bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl overflow-hidden hover:border-gold/50 transition-all duration-500 hover:shadow-2xl hover:shadow-gold/10">
                            <a :href="getProductUrl(dress)" class="block">
                                <div class="relative h-72 overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-t from-ink-raised/80 via-transparent to-transparent z-10"></div>
                                    <img :src="dress.main_image_url" :alt="dress.name" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
                                    <template x-if="dress.is_featured">
                                        <span class="absolute top-4 left-4 z-20 px-3 py-1.5 text-xs font-medium bg-gold/90 text-ink rounded-full backdrop-blur-sm"><i class="fas fa-star mr-1"></i> Featured</span>
                                    </template>
                                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-20"></div>
                                </div>
                                <div class="px-5 pt-5">
                                    <div class="text-xs text-gold uppercase tracking-wider mb-1">Traditional Dress</div>
                                    <h3 class="font-semibold text-bone text-lg mb-2 line-clamp-1 group-hover:text-gold transition-colors" x-text="dress.name"></h3>
                                </div>
                            </a>
                            <div class="px-5 pb-5">
                                <span class="text-2xl font-bold font-numeric text-gold">R<span x-text="dress.price.toLocaleString()"></span></span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="!loading && !dresses.length">
                <div class="text-center py-16 bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl">
                    <svg class="w-20 h-20 mx-auto text-bone-faint mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" /></svg>
                    <h3 class="text-2xl font-bold text-bone mb-2">No dresses found</h3>
                    <p class="text-bone-dim mb-6">Try adjusting your filter or check back later.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300"><i class="fas fa-times-circle mr-2"></i>Clear filter</a>
                </div>
            </template>

            <template x-if="!loading && pagination.last_page > 1">
                <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-bone-dim text-sm">Page <span class="text-bone" x-text="pagination.current_page"></span> of <span class="text-bone" x-text="pagination.last_page"></span></div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="px-4 py-2 rounded-lg border border-line bg-ink-raised2 text-bone-dim hover:bg-ink-raised2 disabled:opacity-50">Previous</button>
                        <button type="button" @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="px-4 py-2 rounded-lg border border-line bg-ink-raised2 text-bone-dim hover:bg-ink-raised2 disabled:opacity-50">Next</button>
                    </div>
                </div>
            </template>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function productsPage(newArrivals) {
            return {
                loading: true,
                newArrivals: newArrivals || false,
                category: null,
                sort: 'latest',
                dresses: [],
                pagination: {},
                resultText: 'Loading dresses...',

                init() {
                    const params = new URLSearchParams(window.location.search);
                    this.category = params.get('category');
                    this.sort = params.get('sort') || 'latest';
                    this.fetchProducts();
                },

                sortLabel() {
                    return {
                        latest: 'Latest',
                        price_asc: 'Price: Low to High',
                        price_desc: 'Price: High to Low',
                    }[this.sort] || 'Latest';
                },

                async selectSort(value) {
                    this.sort = value;
                    await this.fetchProducts(1);
                },

                async fetchProducts(page = 1) {
                    try {
                        this.loading = true;
                        this.dresses = [];
                        const params = new URLSearchParams(window.location.search);
                        let endpoint = '{{ route('api.products.index', [], false) }}';

                        if (this.newArrivals) {
                            endpoint = '{{ route('api.products.new-arrivals', [], false) }}';
                        } else {
                            params.set('page', page);
                            params.set('sort', this.sort);
                        }

                        const url = this.newArrivals ? endpoint : `${endpoint}?${params.toString()}`;
                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error('Unable to load products.');
                        }
                        const json = await response.json();
                        this.dresses = json.data || json;

                        if (json.meta) {
                            this.pagination = json.meta;
                        } else {
                            const count = Array.isArray(this.dresses) ? this.dresses.length : 0;
                            this.pagination = {
                                total: count,
                                from: count ? 1 : 0,
                                to: count,
                                current_page: 1,
                                last_page: 1,
                            };
                        }

                        this.resultText = `${this.pagination.total || 0} ${this.pagination.total === 1 ? 'design' : 'designs'} available`;
                        if (!this.newArrivals) {
                            window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                        }
                    } catch (error) {
                        console.error(error);
                        this.resultText = 'Unable to load dresses';
                    } finally {
                        this.loading = false;
                    }
                },

                async refresh() {
                    await this.fetchProducts(this.pagination.current_page || 1);
                },

                async changePage(page) {
                    if (page < 1 || page > (this.pagination.last_page || 1)) {
                        return;
                    }
                    await this.fetchProducts(page);
                },

                getProductUrl(dress) {
                    return `/products/${encodeURIComponent(dress.slug || dress.id)}`;
                },
            };
        }
    </script>
@endpush
