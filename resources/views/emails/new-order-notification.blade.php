<!DOCTYPE html>
<html>

<head>
    <title>New Paid Order</title>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background: #0a0a0a;
            color: #fff;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #111;
            border: 1px solid #D4AF37;
            border-radius: 10px;
            padding: 20px;
        }

        h2 {
            color: #D4AF37;
        }

        .label {
            font-weight: bold;
            color: #D4AF37;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        td {
            padding: 8px 0;
            border-bottom: 1px solid #222;
            font-size: 14px;
        }

        .total-row td {
            border-bottom: none;
            font-weight: bold;
            color: #D4AF37;
            padding-top: 12px;
        }

        .button {
            display: inline-block;
            margin-top: 16px;
            padding: 10px 20px;
            background: #D4AF37;
            color: #0a0a0a;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>New paid order: {{ $order->order_number }}</h2>
        <p><span class="label">Customer:</span> {{ $order->user?->name ?? 'Guest' }} ({{ $order->email ?? $order->user?->email ?? 'no email on file' }})</p>

        <table>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        {{ $item->dress?->name ?? 'Item' }}
                        @if (! empty($item->attributes['size']) || ! empty($item->attributes['color']))
                            <br><span style="color:#888;font-size:12px;">
                                {{ collect([$item->attributes['size'] ?? null ? 'Size '.$item->attributes['size'] : null, $item->attributes['color'] ?? null ? ucfirst($item->attributes['color']) : null])->filter()->implode(', ') }}
                            </span>
                        @endif
                        <br><span style="color:#888;font-size:12px;">Qty {{ $item->quantity }}</span>
                    </td>
                    <td style="text-align:right;">R{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Total</td>
                <td style="text-align:right;">R{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        @if ($order->shippingAddress)
            <p><span class="label">Shipping to:</span><br>
                {{ $order->shippingAddress->address_line1 }}@if ($order->shippingAddress->address_line2), {{ $order->shippingAddress->address_line2 }}@endif<br>
                {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postal_code }}<br>
                {{ $order->shippingAddress->country }}<br>
                Phone: {{ $order->shippingAddress->phone }}
            </p>
        @endif

        <a class="button" href="{{ route('admin.orders.show', $order->id) }}">View order in admin</a>
    </div>
</body>

</html>
