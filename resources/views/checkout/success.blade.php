@extends('layouts.app')

@section('title', 'Order Confirmed – BUGGXIT Couture')

@section('content')
    <div class="container-wide px-4 sm:px-6 lg:px-8 py-16 mx-auto text-center">
        <div class="max-w-2xl mx-auto bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl p-8 md:p-12">
            <div
                class="w-20 h-20 mx-auto mb-6 bg-good/20 rounded-full flex items-center justify-center border border-good/30">
                <i class="fas fa-check text-4xl text-good"></i>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-bone mb-4">Thank You for Your Order!</h1>
            <p class="text-bone-dim mb-6">Your order has been placed successfully.</p>

            <div class="bg-ink-raised2/30 border border-line rounded-lg p-6 mb-8 text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-bone-dim">Order Number</span>
                    <span class="text-bone font-mono">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-bone-dim">Date</span>
                    <span class="text-bone">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-bone-dim">Total</span>
                    <span class="text-gold font-bold font-numeric">R{{ number_format($order->total, 0) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-bone-dim">Payment Method</span>
                    <span
                        class="text-bone">{{ $order->payment_method == 'eft' ? 'Bank Transfer (EFT)' : 'Cash on Delivery' }}</span>
                </div>
            </div>

            <p class="text-bone-dim mb-8">We'll send you a confirmation email with order details and tracking information
                once your dress is ready.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-ink-raised2/50 border border-line text-bone rounded-lg hover:bg-ink-raised2 transition-colors">
                    <i class="fas fa-tshirt mr-2"></i> Continue Shopping
                </a>
                @auth
                    <a href="{{ route('orders.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300">
                        <i class="fas fa-history mr-2"></i> View My Orders
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection
