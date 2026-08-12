@extends('layouts.app')

@section('title', 'All Dresses – BUGGXIT Couture')

@section('content')
    <div x-data="productsPage(@json($newArrivals ?? false))" x-init="init()" x-cloak class="space-y-12">
        <section class="relative mb-20 overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-yellow-500/5 rounded-full blur-3xl"></div>
            <div class="relative z-10 py-16 md:py-24">
                <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 mb-4 backdrop-blur-sm">
                            <i class="fas fa-tshirt mr-2"></i>
                            <span x-text="resultText"></span>
                        </span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                            <template x-if="newArrivals">New Arrivals</template>
                            <template x-if="!newArrivals">Our <span class="text-yellow-500">Collections</span></template>
                        </h1>
                        <p class="text-lg text-gray-300 max-w-2xl">
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
                    <h2 class="text-white font-semibold text-lg flex items-center">
                        <i class="fas fa-filter mr-2 text-yellow-500"></i>
                        Explore all collections
                    </h2>
                    <template x-if="category">
                        <p class="text-gray-400 text-sm mt-2">Filtering by category: <span class="text-white" x-text="category"></span></p>
                    </template>
                </div>
                <button type="button" @click="refresh()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800/80 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:border-yellow-500/50 transition">
                    <i class="fas fa-sync-alt text-yellow-500"></i>
                    Refresh
                </button>
            </div>
        </section>

        <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-16">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <p class="text-gray-400 text-sm">
                    Showing <span class="text-white font-medium" x-text="pagination.from || 0"></span>
                    – <span class="text-white font-medium" x-text="pagination.to || 0"></span>
                    of <span class="text-yellow-500 font-medium" x-text="pagination.total || 0"></span> dresses
                </p>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:border-yellow-500/50 transition-colors text-sm">
                        <i class="fas fa-sort-amount-down text-yellow-500"></i>
                        <span>Sort by: Latest</span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-2 w-48 bg-black/90 backdrop-blur-sm border border-gray-800 rounded-lg shadow-xl z-50 overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-yellow-500/30 to-transparent"></div>
                        <a href="#" class="block px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800/50 hover:text-yellow-500 transition-colors">Latest</a>
                        <a href="#" class="block px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800/50 hover:text-yellow-500 transition-colors">Price: Low to High</a>
                        <a href="#" class="block px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800/50 hover:text-yellow-500 transition-colors">Price: High to Low</a>
                    </div>
                </div>
            </div>

            <template x-if="loading">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="n in 8" :key="n">
                        <div class="space-y-4">
                            <div class="h-72 rounded-3xl bg-gray-900/90 animate-pulse"></div>
                            <div class="h-8 rounded-xl bg-gray-900/90 animate-pulse"></div>
                            <div class="h-6 rounded-xl bg-gray-900/90 animate-pulse"></div>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="!loading && dresses.length">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="dress in dresses" :key="dress.id">
                        <div class="group bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl overflow-hidden hover:border-yellow-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/10">
                            <div class="relative h-72 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-10"></div>
                                <img :src="dress.main_image_url" :alt="dress.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <template x-if="dress.is_featured">
                                    <span class="absolute top-4 left-4 z-20 px-3 py-1.5 text-xs font-medium bg-yellow-500/90 text-gray-900 rounded-full backdrop-blur-sm"><i class="fas fa-star mr-1"></i> Featured</span>
                                </template>
                            </div>
                            <div class="p-5">
                                <div class="text-xs text-yellow-500 uppercase tracking-wider mb-1">Traditional Dress</div>
                                <a :href="getProductUrl(dress)" class="block">
                                    <h3 class="font-semibold text-white text-lg mb-2 line-clamp-1 hover:text-yellow-500 transition-colors" x-text="dress.name"></h3>
                                </a>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-yellow-500">R<span x-text="dress.price.toLocaleString()"></span></span>
                                    <button type="button" @click.prevent="addToCart(dress.id)" class="p-3 bg-yellow-500/10 rounded-xl text-yellow-500 hover:bg-yellow-500 hover:text-gray-900 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="!loading && !dresses.length">
                <div class="text-center py-16 bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl">
                    <svg class="w-20 h-20 mx-auto text-gray-700 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" /></svg>
                    <h3 class="text-2xl font-bold text-white mb-2">No dresses found</h3>
                    <p class="text-gray-400 mb-6">Try adjusting your filter or check back later.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300"><i class="fas fa-times-circle mr-2"></i>Clear filter</a>
                </div>
            </template>

            <template x-if="!loading && pagination.last_page > 1">
                <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-gray-400 text-sm">Page <span class="text-white" x-text="pagination.current_page"></span> of <span class="text-white" x-text="pagination.last_page"></span></div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="px-4 py-2 rounded-lg border border-gray-700 bg-gray-900 text-gray-300 hover:bg-gray-800 disabled:opacity-50">Previous</button>
                        <button type="button" @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="px-4 py-2 rounded-lg border border-gray-700 bg-gray-900 text-gray-300 hover:bg-gray-800 disabled:opacity-50">Next</button>
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
                dresses: [],
                pagination: {},
                resultText: 'Loading dresses...',

                init() {
                    const params = new URLSearchParams(window.location.search);
                    this.category = params.get('category');
                    this.fetchProducts();
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
                    } catch (error) {
                        console.error(error);
                    }
                },

                getProductUrl(dress) {
                    return `/products/${encodeURIComponent(dress.slug || dress.id)}`;
                },
            };
        }
    </script>
@endpush
