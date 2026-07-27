@extends('layouts.app')

@section('title', 'Dress details – BUGGXIT Couture')

@section('content')
    <div x-data="productPage(@json($productIdentifier))" x-init="init()" x-cloak class="space-y-10">
        <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto pt-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-gray-400 hover:text-yellow-500 transition-colors group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to all dresses
            </a>
        </div>

        <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto py-8">
            <template x-if="loading">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
                    <div class="space-y-4">
                        <div class="aspect-square rounded-2xl bg-gray-900/90 animate-pulse"></div>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="n in 4" :key="n"><div class="aspect-square rounded-lg bg-gray-900/90 animate-pulse"></div></template>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="h-12 rounded-2xl bg-gray-900/90 animate-pulse"></div>
                        <div class="h-10 rounded-2xl bg-gray-900/90 animate-pulse"></div>
                        <div class="h-32 rounded-2xl bg-gray-900/90 animate-pulse"></div>
                        <div class="h-20 rounded-2xl bg-gray-900/90 animate-pulse"></div>
                        <div class="h-20 rounded-2xl bg-gray-900/90 animate-pulse"></div>
                    </div>
                </div>
            </template>

            <template x-if="error">
                <div class="rounded-3xl border border-red-500/20 bg-black/80 p-10 text-center text-red-400">
                    <p x-text="error"></p>
                </div>
            </template>

            <template x-if="!loading && product">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
                    <div class="space-y-4">
                        <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-800 bg-black/90 backdrop-blur-sm group">
                            <img :src="selectedImage" :alt="product.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <template x-if="product.is_featured">
                                <span class="absolute top-4 left-4 z-20 px-3 py-1.5 text-xs font-medium bg-yellow-500/90 text-gray-900 rounded-full backdrop-blur-sm">
                                    <i class="fas fa-star mr-1"></i> Featured
                                </span>
                            </template>
                        </div>

                        <template x-if="product.gallery_image_urls?.length">
                            <div class="grid grid-cols-4 gap-2">
                                <template x-for="image in galleryImages" :key="image">
                                    <button type="button" @click="selectedImage = image" class="aspect-square rounded-lg overflow-hidden border border-gray-800 hover:border-yellow-500/50 transition-colors">
                                        <img :src="image" alt="Gallery thumbnail" class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold text-white" x-text="product.name"></h1>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-bold text-yellow-500">R<span x-text="formatPrice(product.price)"></span></span>
                            <template x-if="product.is_featured">
                                <span class="px-3 py-1 text-xs font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 rounded-full">
                                    <i class="fas fa-star mr-1"></i> Editor's pick
                                </span>
                            </template>
                        </div>
                        <div class="prose prose-invert max-w-none">
                            <p x-text="product.description"></p>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-3 flex items-center"><i class="fas fa-ruler-combined text-yellow-500 mr-2"></i> Available Sizes</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-if="product.sizes?.length">
                                    <template x-for="size in product.sizes" :key="size">
                                        <span class="px-4 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300 text-sm hover:border-yellow-500/30 transition-colors" x-text="`Size ${size}`"></span>
                                    </template>
                                </template>
                                <template x-if="!product.sizes?.length">
                                    <span class="text-gray-500 text-sm">Contact us for sizing</span>
                                </template>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-3 flex items-center"><i class="fas fa-palette text-yellow-500 mr-2"></i> Available Colors</h3>
                            <div class="flex flex-wrap gap-3">
                                <template x-if="product.colors?.length">
                                    <template x-for="color in product.colors" :key="color">
                                        <span class="flex items-center px-4 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300 text-sm">
                                            <span class="w-3 h-3 rounded-full mr-2" :style="color === 'multi' ? 'background: linear-gradient(90deg, #f87171, #60a5fa, #facc15)' : `background-color: ${color};`"></span>
                                            <span x-text="color === 'multi' ? 'Multi' : color.charAt(0).toUpperCase() + color.slice(1)"></span>
                                        </span>
                                    </template>
                                </template>
                                <template x-if="!product.colors?.length">
                                    <span class="text-gray-500 text-sm">Contact us for color options</span>
                                </template>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-800/50">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center flex-shrink-0"><i class="fas fa-clock text-yellow-500"></i></div>
                                <div>
                                    <p class="text-sm text-gray-400">Turnaround time</p>
                                    <p class="text-white font-medium" x-text="product.turnaround_time || 'Contact for details'"></p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center flex-shrink-0"><i class="fas fa-truck text-yellow-500"></i></div>
                                <div>
                                    <p class="text-sm text-gray-400">Expected delivery</p>
                                    <p class="text-white font-medium" x-text="product.expected_delivery || 'Contact for details'"></p>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="button" @click="addToCart(product.id)" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 shadow-2xl shadow-yellow-500/25 flex items-center justify-center space-x-3 text-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span>Add to Collection</span>
                            </button>
                        </div>
                        <div class="text-xs text-gray-500 pt-4 border-t border-gray-800/50">
                            <i class="fas fa-shield-alt mr-1"></i> Handcrafted with care in South Africa. Each piece is made to order.
                        </div>
                    </div>
                </div>
            </template>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function productPage(productIdentifier) {
            return {
                loading: true,
                error: null,
                product: null,
                selectedImage: null,
                galleryImages: [],

                init() {
                    this.fetchProduct(productIdentifier);
                },

                async fetchProduct(identifier) {
                    try {
                        const response = await fetch(`/api/products/${encodeURIComponent(identifier)}`);
                        if (!response.ok) {
                            if (response.status === 404) {
                                throw new Error('Product not found.');
                            }
                            throw new Error('Unable to load product.');
                        }
                        const json = await response.json();
                        this.product = json.data || json;
                        this.selectedImage = this.product.main_image_url;
                        this.galleryImages = this.product.gallery_image_urls || [];
                        if (!this.selectedImage && this.galleryImages.length) {
                            this.selectedImage = this.galleryImages[0];
                        }
                    } catch (error) {
                        this.error = error.message || 'Unable to load product details.';
                    } finally {
                        this.loading = false;
                    }
                },

                async addToCart(productId) {
                    try {
                        const response = await fetch('{{ route('api.cart.add') }}', {
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

                formatPrice(value) {
                    return Number(value || 0).toLocaleString('en-ZA');
                },
            };
        }
    </script>
@endpush

