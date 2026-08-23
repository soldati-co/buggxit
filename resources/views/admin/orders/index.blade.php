@extends('layouts.admin')

@section('title', 'Manage Orders - BUGGXIT Admin')
@section('page-title', 'Orders')
@section('page-description', 'Manage customer orders')

@section('content')
    <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line/50">
                <thead class="bg-ink-raised2/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Order #</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/50">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-ink-raised2/30">
                            <td class="px-6 py-4 text-sm font-mono text-bone">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-bone-dim">
                                {{ $order->user->name ?? $order->name ?? 'Guest' }}<br>
                                <span class="text-xs text-bone-faint">{{ $order->user->email ?? $order->email ?? 'No email' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-bone-dim">{{ $order->created_at->format('d M Y') }}</td>
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
                                <a href="{{ route('admin.orders.show', $order) }}"
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
@endsection
