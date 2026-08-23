@extends('layouts.app')

@section('title', 'Checkout – BUGGXIT Couture')

@section('content')
    <div class="container-wide px-4 sm:px-6 lg:px-8 py-12 mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-bone mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Checkout Form (left column) --}}
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                    @csrf

                    {{-- Shipping Address --}}
                    <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                        <h2 class="text-xl font-semibold text-bone mb-4 flex items-center">
                            <i class="fas fa-truck text-gold mr-2"></i>
                            Shipping Address
                        </h2>

                        @if (auth()->check() && $addresses->count() > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-bone-dim mb-2">Select saved address</label>
                                <select name="shipping_address_id"
                                    class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                    <option value="">Use a new address</option>
                                    @foreach ($addresses as $address)
                                        <option value="{{ $address->id }}" @selected(old('shipping_address_id') == $address->id)>
                                            {{ $address->address_line1 }}, {{ $address->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="border-t border-line/50 my-4"></div>
                        @endif

                        <div id="new-address-fields">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-bone-dim mb-1">Address Line 1 *</label>
                                    <input type="text" name="address_line1" value="{{ old('address_line1') }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone focus:border-gold">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-bone-dim mb-1">Address Line 2
                                        (optional)</label>
                                    <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-bone-dim mb-1">City *</label>
                                    <input type="text" name="city" value="{{ old('city') }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-bone-dim mb-1">State/Province</label>
                                    <input type="text" name="state" value="{{ old('state') }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-bone-dim mb-1">Postal Code *</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-bone-dim mb-1">Country *</label>
                                    <input type="text" name="country" value="{{ old('country', 'South Africa') }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-bone-dim mb-1">Phone *</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-bone-dim mb-1">Email *</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}"
                                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">
                                    <p class="text-xs text-bone-faint mt-1">We'll send your order confirmation and payment receipt here.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                        <h2 class="text-xl font-semibold text-bone mb-4 flex items-center">
                            <i class="fas fa-credit-card text-gold mr-2"></i>
                            Payment Method
                        </h2>
                        <input type="hidden" name="payment_method" value="payfast">
                        <div class="flex items-center p-3 border border-gold/50 bg-gold/5 rounded-lg">
                            <i class="fas fa-shield-alt text-gold mr-3 text-lg"></i>
                            <div>
                                <span class="block text-bone font-medium">Pay Now via PayFast</span>
                                <span class="block text-bone-dim text-sm">Secure card payment. You'll be redirected to PayFast to complete your payment.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Billing Address --}}
                    <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                        <h2 class="text-xl font-semibold text-bone mb-4 flex items-center">
                            <i class="fas fa-file-invoice text-gold mr-2"></i>
                            Billing Address
                        </h2>
                        <label
                            class="flex items-center p-3 border border-line rounded-lg cursor-pointer hover:border-gold/50 transition-colors">
                            <input type="checkbox" name="same_as_shipping" value="1"
                                class="h-4 w-4 text-gold rounded" checked>
                            <span class="ml-3 text-bone">Same as shipping address</span>
                        </label>
                        {{-- You could add separate billing address fields here if needed --}}
                    </div>

                    {{-- Order Notes --}}
                    <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-bone mb-4 flex items-center">
                            <i class="fas fa-pen text-gold mr-2"></i>
                            Additional Notes (Optional)
                        </h2>
                        <textarea name="notes" rows="3"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone">{{ old('notes') }}</textarea>
                    </div>
                </form>
            </div>

            {{-- Order Summary (right column) --}}
            <div class="lg:col-span-1">
                <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                        <i class="fas fa-shopping-bag text-gold mr-2"></i>
                        Your Order
                    </h3>

                    <div class="space-y-3 max-h-80 overflow-y-auto mb-4 pr-2">
                        @foreach ($items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-bone-dim">
                                    {{ $item['dress']->name }}
                                    @if ($item['size'] || $item['color'])
                                        <span class="text-bone-faint">({{ collect([$item['size'] ? "Size {$item['size']}" : null, $item['color'] ? ucfirst($item['color']) : null])->filter()->implode(', ') }})</span>
                                    @endif
                                    <span class="text-bone-faint">x{{ $item['quantity'] }}</span>
                                </span>
                                <span class="text-bone font-medium font-numeric">R{{ number_format($item['subtotal'], 0) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-line/50 pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-bone-dim">Subtotal</span>
                            <span class="text-bone font-numeric">R{{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-bone-dim">Shipping</span>
                            <span class="text-bone-dim">Calculated at delivery</span>
                        </div>
                    </div>

                    <div class="border-t border-line/50 mt-4 pt-4 flex justify-between text-lg font-bold">
                        <span class="text-bone">Total</span>
                        <span class="text-gold font-numeric">R{{ number_format($subtotal, 0) }}</span>
                    </div>

                    <button type="submit" form="checkout-form"
                        class="w-full mt-6 py-3.5 bg-gradient-to-r from-gold to-gold-dim text-ink font-bold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300">
                        Place Order
                    </button>

                    <p class="text-xs text-bone-faint text-center mt-4">
                        <i class="fas fa-lock mr-1"></i> Your information is secure
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
