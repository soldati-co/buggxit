@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' – BUGGXIT Couture')

@section('content')
    <div class="container-wide px-4 sm:px-6 lg:px-8 py-12 mx-auto">
        <div class="mb-6">
            <a href="{{ route('orders.index') }}"
                class="inline-flex items-center text-bone-dim hover:text-gold transition-colors group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
        </div>

        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-bone">Order <span class="font-mono text-gold-bright">{{ $order->order_number }}</span></h1>
                    <p class="text-bone-dim mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <span
                        class="px-4 py-2 rounded-full text-sm font-medium
                    @if ($order->status == 'pending') bg-gold/10 text-gold-bright border border-gold/30
                    @elseif($order->status == 'processing') bg-info/10 text-info border border-info/30
                    @elseif($order->status == 'completed') bg-good/10 text-good border border-good/30
                    @else bg-bone-faint/10 text-bone-dim border border-bone-faint/30 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span
                        class="px-4 py-2 rounded-full text-sm font-medium
                    @if ($order->payment_status == 'paid') bg-good/10 text-good border border-good/30
                    @else bg-gold/10 text-gold-bright border border-gold/30 @endif">
                        Payment: {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="mb-8">
                <a href="{{ route('orders.receipt', $order) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-ink-raised2/50 border border-line rounded-lg text-bone-dim hover:text-gold hover:border-gold/50 transition-colors text-sm">
                    <i class="fas fa-file-pdf mr-2 text-gold"></i> Download Receipt
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-bone font-semibold mb-3 flex items-center">
                        <i class="fas fa-truck text-gold mr-2"></i> Shipping Address
                    </h3>
                    <address class="not-italic text-bone-dim text-sm border border-line rounded-lg p-4 bg-ink-raised2/30">
                        {{ $order->shippingAddress->address_line1 }}<br>
                        @if ($order->shippingAddress->address_line2)
                            {{ $order->shippingAddress->address_line2 }}<br>
                        @endif
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                        {{ $order->shippingAddress->postal_code }}<br>
                        {{ $order->shippingAddress->country }}<br>
                        <span class="text-bone-dim">Phone: {{ $order->shippingAddress->phone }}</span>
                    </address>
                </div>
                <div>
                    <h3 class="text-bone font-semibold mb-3 flex items-center">
                        <i class="fas fa-credit-card text-gold mr-2"></i> Payment Method
                    </h3>
                    <div class="text-bone-dim text-sm border border-line rounded-lg p-4 bg-ink-raised2/30">
                        {{ match ($order->payment_method) { 'eft' => 'Bank Transfer (EFT)', 'cash_on_delivery' => 'Cash on Delivery', 'payfast' => 'PayFast', default => ucfirst($order->payment_method ?? 'Unknown') } }}
                    </div>
                </div>
            </div>

            <h3 class="text-bone font-semibold mb-4 flex items-center">
                <i class="fas fa-shopping-bag text-gold mr-2"></i> Order Items
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line/50">
                    <thead class="bg-ink-raised2/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-bone-dim uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-bone-dim uppercase">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-bone-dim uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-bone-dim uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line/50">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        @if ($item->dress && $item->dress->main_image_url)
                                            <img src="{{ $item->dress->main_image_url }}" alt="{{ $item->dress->name }}"
                                                class="w-12 h-12 rounded-lg object-contain bg-ink-raised2/50 mr-3">
                                        @endif
                                        <div>
                                            <span class="text-bone">{{ $item->dress->name ?? 'Product unavailable' }}</span>
                                            @if ($item->attributes['size'] ?? $item->attributes['color'] ?? null)
                                                <div class="text-xs text-bone-faint mt-0.5">
                                                    @if ($item->attributes['size'] ?? null)
                                                        Size {{ $item->attributes['size'] }}
                                                    @endif
                                                    @if (($item->attributes['size'] ?? null) && ($item->attributes['color'] ?? null))
                                                        &middot;
                                                    @endif
                                                    @if ($item->attributes['color'] ?? null)
                                                        {{ ucfirst($item->attributes['color']) }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-bone-dim font-numeric">R{{ number_format($item->price, 0) }}</td>
                                <td class="px-4 py-4 text-bone-dim font-numeric">{{ $item->quantity }}</td>
                                <td class="px-4 py-4 text-gold font-semibold font-numeric">
                                    R{{ number_format($item->price * $item->quantity, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-line/50">
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-right text-bone-dim">Subtotal</td>
                            <td class="px-4 py-4 text-bone font-numeric">R{{ number_format($order->subtotal, 0) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-right text-bone-dim">Shipping</td>
                            <td class="px-4 py-4 text-bone font-numeric">R{{ number_format($order->shipping_cost, 0) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-right text-bone-dim font-bold">Total</td>
                            <td class="px-4 py-4 text-gold font-bold font-numeric text-lg">R{{ number_format($order->total, 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($order->notes)
                <div class="mt-6 pt-6 border-t border-line/50">
                    <h4 class="text-bone font-medium mb-2">Order Notes</h4>
                    <p class="text-bone-dim text-sm">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
