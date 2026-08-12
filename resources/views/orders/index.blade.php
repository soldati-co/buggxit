@extends('layouts.app')

@section('title', 'My Orders – BUGGXIT Couture')

@section('content')
    <div class="container-wide px-4 sm:px-6 lg:px-8 py-12 mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-bone mb-8">My Orders</h1>

        @if ($orders->count() > 0)
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-line/50">
                        <thead class="bg-ink-raised2/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Order #</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Items</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Payment</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line/50">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-ink-raised2/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono text-bone">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4 text-sm text-bone-dim">{{ $order->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-bone-dim">{{ $order->items->count() }} item(s)</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gold font-numeric">
                                        R{{ number_format($order->total, 0) }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if ($order->status == 'pending') bg-gold/10 text-gold-bright border border-gold/30
                                        @elseif($order->status == 'processing') bg-info/10 text-info border border-info/30
                                        @elseif($order->status == 'completed') bg-good/10 text-good border border-good/30
                                        @else bg-bone-faint/10 text-bone-dim border border-bone-faint/30 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if ($order->payment_status == 'paid') bg-good/10 text-good border border-good/30
                                        @else bg-gold/10 text-gold-bright border border-gold/30 @endif">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="text-gold hover:text-gold-bright transition-colors">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-line/50">
                    {{ $orders->links('vendor.pagination.tailwind-dark') }}
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl">
                <svg class="w-20 h-20 mx-auto text-bone-faint mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-2xl font-bold text-bone mb-2">No orders yet</h2>
                <p class="text-bone-dim mb-6">Looks like you haven't placed any orders.</p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300">
                    <i class="fas fa-tshirt mr-2"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>
@endsection
