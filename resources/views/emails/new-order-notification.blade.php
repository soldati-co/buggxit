<!DOCTYPE html>
<html>

<head>
    <title>New Paid Order</title>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #111111;
            border: 1px solid #D4AF37;
            border-radius: 10px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        td {
            padding: 8px 0;
            border-bottom: 1px solid #222222;
            font-size: 14px;
        }
    </style>
</head>

<body style="font-family:'Manrope',sans-serif;background:#0a0a0a;color:#ffffff;padding:20px;">
    <div class="container" style="max-width:600px;margin:0 auto;background:#111111;border:1px solid #D4AF37;border-radius:10px;padding:20px;">
        <div style="text-align:center;margin-bottom:20px;">
            <img src="{{ asset('logo.webp') }}" alt="Buggxit Couture" width="140" style="max-width:140px;height:auto;">
        </div>
        <h2 style="color:#D4AF37;margin-top:0;">New paid order: {{ $order->order_number }}</h2>
        <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Customer:</span> <span style="color:#ffffff;">{{ $order->user?->name ?? $order->name ?? 'Guest (no name given)' }} ({{ $order->email ?? $order->user?->email ?? 'no email on file' }})</span></p>

        <table style="width:100%;border-collapse:collapse;margin:16px 0;">
            @foreach ($order->items as $item)
                <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #222222;font-size:14px;color:#ffffff;">
                        {{ $item->dress?->name ?? 'Item' }}
                        @if (! empty($item->attributes['size']) || ! empty($item->attributes['color']))
                            <br><span style="color:#999999;font-size:12px;">
                                {{ collect([$item->attributes['size'] ?? null ? 'Size '.$item->attributes['size'] : null, $item->attributes['color'] ?? null ? ucfirst($item->attributes['color']) : null])->filter()->implode(', ') }}
                            </span>
                        @endif
                        <br><span style="color:#999999;font-size:12px;">Qty {{ $item->quantity }}</span>
                    </td>
                    <td style="padding:8px 0;border-bottom:1px solid #222222;font-size:14px;color:#ffffff;text-align:right;">R{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="padding-top:12px;border-bottom:none;font-weight:bold;color:#D4AF37;">Total</td>
                <td style="padding-top:12px;border-bottom:none;font-weight:bold;color:#D4AF37;text-align:right;">R{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        @if ($order->shippingAddress)
            <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Shipping to:</span><br>
                <span style="color:#ffffff;">
                    {{ $order->shippingAddress->address_line1 }}@if ($order->shippingAddress->address_line2), {{ $order->shippingAddress->address_line2 }}@endif<br>
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postal_code }}<br>
                    {{ $order->shippingAddress->country }}<br>
                    Phone: {{ $order->shippingAddress->phone }}
                </span>
            </p>
        @endif

        <a href="{{ route('admin.orders.show', $order->id) }}" style="display:inline-block;margin-top:16px;padding:10px 20px;background:#D4AF37;color:#0a0a0a;text-decoration:none;border-radius:6px;font-weight:bold;">View order in admin</a>
    </div>
</body>

</html>
