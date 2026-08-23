@extends('layouts.admin')

@section('title', 'Record Manual Order - BUGGXIT Admin')
@section('page-title', 'Record Manual Order')
@section('page-description', 'For orders placed via WhatsApp, phone, or in person')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="manualOrderForm(@json($dresses))">
        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf

            {{-- Customer --}}
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-bone mb-4">Customer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Full Name <span class="text-gold">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        @error('name')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Email <span class="text-bone-faint">(for receipt)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        @error('email')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-bone mb-4">Shipping Address</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-bone-dim mb-2">Address Line 1 <span class="text-gold">*</span></label>
                        <input type="text" name="address_line1" value="{{ old('address_line1') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        @error('address_line1')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-bone-dim mb-2">Address Line 2 (optional)</label>
                        <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">City <span class="text-gold">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        @error('city')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Province</label>
                        <select name="state"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                            <option value="">Select a province</option>
                            @foreach ([
                                'Eastern Cape', 'Free State', 'Gauteng', 'KwaZulu-Natal', 'Limpopo',
                                'Mpumalanga', 'Northern Cape', 'North West', 'Western Cape',
                            ] as $province)
                                <option value="{{ $province }}" @selected(old('state') === $province)>{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Postal Code <span class="text-gold">*</span></label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        @error('postal_code')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Country <span class="text-gold">*</span></label>
                        <input type="text" name="country" value="{{ old('country', 'South Africa') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        @error('country')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Phone <span class="text-gold">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        @error('phone')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-bone mb-4">Items</h3>

                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4 pb-4 border-b border-line/50 last:border-0">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-bone-dim mb-1">Dress</label>
                            <select :name="'items[' + index + '][dress_id]'" x-model="item.dress_id" @change="onDressChange(item)" required
                                class="w-full px-3 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                                <option value="">Select a dress</option>
                                <template x-for="dress in dresses" :key="dress.id">
                                    <option :value="dress.id" x-text="dress.name + ' - R' + dress.price"></option>
                                </template>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-bone-dim mb-1">Size</label>
                            <select :name="'items[' + index + '][size]'" x-model="item.size"
                                class="w-full px-3 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                                <option value="">-</option>
                                <template x-for="size in dressFor(item.dress_id)?.sizes || []" :key="size">
                                    <option :value="size" x-text="size"></option>
                                </template>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-bone-dim mb-1">Color</label>
                            <select :name="'items[' + index + '][color]'" x-model="item.color"
                                class="w-full px-3 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                                <option value="">-</option>
                                <template x-for="color in dressFor(item.dress_id)?.colors || []" :key="color">
                                    <option :value="color" x-text="color"></option>
                                </template>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-bone-dim mb-1">Qty</label>
                            <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" min="1" required
                                class="w-full px-3 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-bone-dim mb-1">Price (each)</label>
                            <input type="number" step="0.01" :name="'items[' + index + '][price]'" x-model.number="item.price" min="0" required
                                class="w-full px-3 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                        </div>
                        <div class="md:col-span-1 flex items-end">
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                class="w-full px-3 py-2 text-bad hover:bg-bad/10 rounded-lg transition-colors text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <button type="button" @click="addItem()"
                    class="mt-2 inline-flex items-center px-4 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone-dim hover:text-gold hover:border-gold/50 transition-colors text-sm">
                    <i class="fas fa-plus mr-2"></i> Add Another Item
                </button>

                <div class="mt-6 pt-4 border-t border-line/50 flex justify-end">
                    <div class="text-right">
                        <span class="text-bone-dim text-sm">Order Total</span>
                        <div class="text-2xl font-bold text-gold font-numeric" x-text="'R' + total.toFixed(2)"></div>
                    </div>
                </div>
                @error('items')<p class="mt-2 text-sm text-bad">{{ $message }}</p>@enderror
            </div>

            {{-- Payment & Status --}}
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-bone mb-4">Payment &amp; Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Payment Method <span class="text-gold">*</span></label>
                        <select name="payment_method" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                            <option value="whatsapp" @selected(old('payment_method') === 'whatsapp')>WhatsApp Order</option>
                            <option value="eft" @selected(old('payment_method') === 'eft')>Bank Transfer (EFT)</option>
                            <option value="cash_on_delivery" @selected(old('payment_method') === 'cash_on_delivery')>Cash on Delivery</option>
                            <option value="other" @selected(old('payment_method') === 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Payment Status <span class="text-gold">*</span></label>
                        <select name="payment_status" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                            <option value="paid" @selected(old('payment_status', 'paid') === 'paid')>Paid</option>
                            <option value="pending" @selected(old('payment_status') === 'pending')>Pending</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-bone-dim mb-2">Order Status <span class="text-gold">*</span></label>
                        <select name="status" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">
                            <option value="processing" @selected(old('status', 'processing') === 'processing')>Processing</option>
                            <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                            <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                        </select>
                    </div>
                </div>
                <p class="mt-3 text-xs text-bone-faint">If marked "Paid" and an email is given, a confirmation email with the receipt is sent immediately.</p>
            </div>

            {{-- Notes --}}
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-bone mb-4">Notes (Optional)</h3>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.orders.index') }}"
                    class="px-6 py-3 border border-line rounded-lg text-bone-dim hover:text-bone hover:bg-ink-raised2/50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 shadow-lg shadow-gold/20">
                    Record Order
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function manualOrderForm(dresses) {
            return {
                dresses: dresses,
                items: [
                    { dress_id: '', size: '', color: '', quantity: 1, price: 0 },
                ],
                dressFor(id) {
                    return this.dresses.find(d => d.id === id);
                },
                onDressChange(item) {
                    const dress = this.dressFor(item.dress_id);
                    item.price = dress ? Number(dress.price) : 0;
                    item.size = '';
                    item.color = '';
                },
                addItem() {
                    this.items.push({ dress_id: '', size: '', color: '', quantity: 1, price: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                get total() {
                    return this.items.reduce((sum, item) => sum + (Number(item.price || 0) * Number(item.quantity || 0)), 0);
                },
            };
        }
    </script>
@endpush
