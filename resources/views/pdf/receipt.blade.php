<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 36px 40px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #222;
            font-size: 12px;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            color: #b8873f;
            letter-spacing: 1px;
        }

        .tagline {
            font-size: 10px;
            color: #777;
            margin-top: 2px;
        }

        .badge {
            display: inline-block;
            font-size: 10px;
            color: #b8873f;
            border: 1px solid #b8873f;
            border-radius: 3px;
            padding: 3px 8px;
        }

        h1 {
            font-size: 16px;
            margin: 24px 0 4px;
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .meta-table td {
            padding: 2px 0;
            font-size: 11px;
        }

        .meta-label {
            color: #777;
            width: 120px;
        }

        .parties-table td {
            vertical-align: top;
            width: 50%;
            padding-top: 12px;
        }

        .party-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #b8873f;
            margin-bottom: 4px;
        }

        .items-table {
            margin-top: 20px;
        }

        .items-table th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #777;
            border-bottom: 2px solid #ddd;
            padding: 6px 0;
        }

        .items-table td {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .item-meta {
            color: #888;
            font-size: 10px;
        }

        .totals-table {
            margin-top: 8px;
            width: 260px;
            float: right;
        }

        .totals-table td {
            padding: 4px 0;
            font-size: 12px;
        }

        .totals-table .total-row td {
            border-top: 2px solid #111;
            font-weight: bold;
            font-size: 14px;
            color: #b8873f;
            padding-top: 8px;
        }

        .footer {
            clear: both;
            margin-top: 60px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #888;
            text-align: center;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="brand">BUGGXIT COUTURE</div>
                <div class="tagline">Ceremony-ready fashion. Proudly South African.</div>
            </td>
            <td class="text-right">
                <span class="badge">{{ $audience === 'store' ? 'STORE COPY' : 'RECEIPT' }}</span>
            </td>
        </tr>
    </table>

    <h1>Order Receipt</h1>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Order Number</td>
            <td>{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td class="meta-label">Date</td>
            <td>{{ $order->created_at?->format('d M Y, H:i') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Payment Method</td>
            <td>{{ match ($order->payment_method) { 'eft' => 'Bank Transfer (EFT)', 'cash_on_delivery' => 'Cash on Delivery', 'payfast' => 'PayFast', 'whatsapp' => 'WhatsApp Order', 'other' => 'Other', default => ucfirst($order->payment_method ?? '-') } }}</td>
        </tr>
        <tr>
            <td class="meta-label">Payment Status</td>
            <td>{{ ucfirst($order->payment_status) }}</td>
        </tr>
        <tr>
            <td class="meta-label">Order Status</td>
            <td>{{ ucfirst($order->status) }}</td>
        </tr>
    </table>

    <table class="parties-table">
        <tr>
            <td>
                <div class="party-title">Billed To</div>
                {{ $order->name ?? $order->user?->name ?? 'Guest' }}<br>
                @if ($order->email ?? $order->user?->email)
                    {{ $order->email ?? $order->user?->email }}<br>
                @endif
                @if ($order->shippingAddress?->phone)
                    {{ $order->shippingAddress->phone }}
                @endif
            </td>
            <td>
                <div class="party-title">Shipped To</div>
                @if ($order->shippingAddress)
                    {{ $order->shippingAddress->address_line1 }}<br>
                    @if ($order->shippingAddress->address_line2)
                        {{ $order->shippingAddress->address_line2 }}<br>
                    @endif
                    {{ $order->shippingAddress->city }}@if ($order->shippingAddress->state), {{ $order->shippingAddress->state }}@endif<br>
                    {{ $order->shippingAddress->postal_code }}, {{ $order->shippingAddress->country }}
                @else
                    No shipping address on file.
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        {{ $item->dress?->name ?? 'Item' }}
                        @if (! empty($item->attributes['size']) || ! empty($item->attributes['color']))
                            <br><span class="item-meta">
                                {{ collect([$item->attributes['size'] ?? null ? 'Size '.$item->attributes['size'] : null, $item->attributes['color'] ?? null ? ucfirst($item->attributes['color']) : null])->filter()->implode(', ') }}
                            </span>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">R{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">R{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">R{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Shipping</td>
            <td class="text-right">R{{ number_format($order->shipping_cost, 2) }}</td>
        </tr>
        @if ($order->discount_amount > 0)
            <tr>
                <td>Discount</td>
                <td class="text-right">-R{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td>Total</td>
            <td class="text-right">R{{ number_format($order->total, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        @if ($audience === 'store')
            Internal record for order {{ $order->order_number }} — view in admin at {{ route('admin.orders.show', $order->id) }}
        @else
            Thank you for shopping with Buggxit Couture. Questions? Contact us at {{ config('mail.store_notification_address') }}
        @endif
    </div>
</body>

</html>
