@extends('layouts.app')

@section('title', 'Dress details – BUGGXIT Couture')

@section('content')
    @php
        $whatsappNumber = \Illuminate\Support\Facades\Schema::hasTable('settings')
            ? \App\Models\Setting::get('whatsapp_number')
            : null;
        $whatsappDigits = $whatsappNumber ? preg_replace('/\D/', '', $whatsappNumber) : null;
    @endphp

    <div x-data='productPage(@json($productIdentifier))' x-init="init()" x-cloak class="space-y-10">
        <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto pt-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-bone-dim hover:text-gold transition-colors group">
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
                        <div class="aspect-square rounded-2xl bg-ink-raised2/90 animate-pulse"></div>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="n in 4" :key="n"><div class="aspect-square rounded-lg bg-ink-raised2/90 animate-pulse"></div></template>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="h-12 rounded-2xl bg-ink-raised2/90 animate-pulse"></div>
                        <div class="h-10 rounded-2xl bg-ink-raised2/90 animate-pulse"></div>
                        <div class="h-32 rounded-2xl bg-ink-raised2/90 animate-pulse"></div>
                        <div class="h-20 rounded-2xl bg-ink-raised2/90 animate-pulse"></div>
                        <div class="h-20 rounded-2xl bg-ink-raised2/90 animate-pulse"></div>
                    </div>
                </div>
            </template>

            <template x-if="error">
                <div class="rounded-3xl border border-bad/20 bg-ink-raised/80 p-10 text-center text-bad">
                    <p x-text="error"></p>
                </div>
            </template>

            <template x-if="!loading && product">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
                    <div class="space-y-4">
                        <div class="relative aspect-square rounded-2xl overflow-hidden border border-line bg-ink-raised/90 backdrop-blur-sm group">
                            <img :src="selectedImage" :alt="product.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <template x-if="product.is_featured">
                                <span class="absolute top-4 left-4 z-20 px-3 py-1.5 text-xs font-medium bg-gold/90 text-ink rounded-full backdrop-blur-sm">
                                    <i class="fas fa-star mr-1"></i> Featured
                                </span>
                            </template>
                        </div>

                        <template x-if="product.gallery_image_urls?.length">
                            <div class="grid grid-cols-4 gap-2">
                                <template x-for="image in galleryImages" :key="image">
                                    <button type="button" @click="selectedImage = image" class="aspect-square rounded-lg overflow-hidden border border-line hover:border-gold/50 transition-colors">
                                        <img :src="image" alt="Gallery thumbnail" class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold text-bone" x-text="product.name"></h1>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-bold font-numeric text-gold">R<span x-text="formatPrice(product.price)"></span></span>
                            <template x-if="product.is_featured">
                                <span class="px-3 py-1 text-xs font-medium bg-gold/10 text-gold border border-gold/30 rounded-full">
                                    <i class="fas fa-star mr-1"></i> Editor's pick
                                </span>
                            </template>
                        </div>
                        <div class="prose prose-invert max-w-none">
                            <p x-text="product.description"></p>
                        </div>
                        <div>
                            <h3 class="text-bone font-semibold mb-3 flex items-center"><i class="fas fa-ruler-combined text-gold mr-2"></i> Select Size</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-if="product.sizes?.length">
                                    <template x-for="size in product.sizes" :key="size">
                                        <button type="button" @click="selectedSize = size"
                                            :class="selectedSize === size ? 'border-gold bg-gold/10 text-gold' : 'border-line bg-ink-raised2/50 text-bone-dim hover:border-gold/30'"
                                            class="px-4 py-2 border rounded-lg text-sm transition-colors" x-text="`Size ${size}`"></button>
                                    </template>
                                </template>
                                <template x-if="!product.sizes?.length">
                                    <span class="text-bone-faint text-sm">Contact us for sizing</span>
                                </template>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-bone font-semibold mb-3 flex items-center"><i class="fas fa-palette text-gold mr-2"></i> Select Color</h3>
                            <div class="flex flex-wrap gap-3">
                                <template x-if="product.colors?.length">
                                    <template x-for="color in product.colors" :key="color">
                                        <button type="button" @click="selectedColor = color"
                                            :class="selectedColor === color ? 'border-gold bg-gold/10 text-gold' : 'border-line bg-ink-raised2/50 text-bone-dim hover:border-gold/30'"
                                            class="flex items-center px-4 py-2 border rounded-lg text-sm transition-colors">
                                            <span class="w-3 h-3 rounded-full mr-2" :style="color === 'multi' ? 'background: linear-gradient(90deg, #f87171, #60a5fa, #facc15)' : `background-color: ${color};`"></span>
                                            <span x-text="color === 'multi' ? 'Multi' : color.charAt(0).toUpperCase() + color.slice(1)"></span>
                                        </button>
                                    </template>
                                </template>
                                <template x-if="!product.colors?.length">
                                    <span class="text-bone-faint text-sm">Contact us for color options</span>
                                </template>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-line/50">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gold/10 flex items-center justify-center flex-shrink-0"><i class="fas fa-clock text-gold"></i></div>
                                <div>
                                    <p class="text-sm text-bone-dim">Turnaround time</p>
                                    <p class="text-bone font-medium" x-text="product.turnaround_time || 'Contact for details'"></p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gold/10 flex items-center justify-center flex-shrink-0"><i class="fas fa-truck text-gold"></i></div>
                                <div>
                                    <p class="text-sm text-bone-dim">Expected delivery</p>
                                    <p class="text-bone font-medium" x-text="product.expected_delivery || 'Contact for details'"></p>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="button" @click="addToCart()" :disabled="!canAddToCart"
                                :class="canAddToCart ? 'hover:from-gold-bright hover:to-gold' : 'opacity-50 cursor-not-allowed'"
                                class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-gold to-gold-dim text-ink font-bold rounded-xl transition-all duration-300 shadow-2xl shadow-gold/25 flex items-center justify-center space-x-3 text-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span>Add to Cart</span>
                            </button>
                            <template x-if="!canAddToCart">
                                <p class="text-bad text-sm mt-2" x-text="missingSelectionText"></p>
                            </template>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 pt-4 border-t border-line/50 text-sm text-bone-dim">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-box text-gold w-4 text-center"></i> Ships within 2 business days
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-truck text-gold w-4 text-center"></i> Nationwide delivery via The Courier Guy & PEP Paxi
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-arrows-rotate text-gold w-4 text-center"></i> 7-day exchange policy
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fab fa-whatsapp text-gold w-4 text-center"></i>
                                @if($whatsappDigits)
                                    <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors">Questions? WhatsApp us</a>
                                @else
                                    <span>Questions? <a href="{{ route('contact') }}" class="hover:text-gold transition-colors">Contact us</a></span>
                                @endif
                            </div>
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
                selectedSize: null,
                selectedColor: null,

                init() {
                    this.fetchProduct(productIdentifier);
                },

                get canAddToCart() {
                    if (!this.product) return false;
                    return !(this.product.sizes?.length && !this.selectedSize)
                        && !(this.product.colors?.length && !this.selectedColor);
                },

                get missingSelectionText() {
                    const needsSize = this.product?.sizes?.length && !this.selectedSize;
                    const needsColor = this.product?.colors?.length && !this.selectedColor;
                    if (needsSize && needsColor) return 'Please select a size and color.';
                    if (needsSize) return 'Please select a size.';
                    if (needsColor) return 'Please select a color.';
                    return '';
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

                async addToCart() {
                    if (!this.canAddToCart) {
                        window.showCartToast(this.missingSelectionText, true);
                        return;
                    }
                    try {
                        const response = await fetch('{{ route('api.cart.add', [], false) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                product_id: this.product.id,
                                quantity: 1,
                                // product.sizes comes through as JSON numbers (e.g. 34),
                                // but the API validates size as a string — stringify here
                                // rather than earlier, so the strict `selectedSize === size`
                                // comparison driving the button highlight still matches.
                                size: this.selectedSize !== null ? String(this.selectedSize) : null,
                                color: this.selectedColor,
                            }),
                        });
                        const data = await response.json();
                        if (data.success) {
                            window.updateCartBadges(data.cart_count);
                            window.showCartToast(data.message || 'Added to your cart.', data.capped === true);
                        } else {
                            window.showCartToast(data.message || 'Could not add this to your cart. Please try again.', true);
                        }
                    } catch (error) {
                        console.error(error);
                        window.showCartToast('Could not add this to your cart. Please try again.', true);
                    }
                },

                formatPrice(value) {
                    return Number(value || 0).toLocaleString('en-ZA');
                },
            };
        }
    </script>
@endpush

