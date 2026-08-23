<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmed</title>
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
            <img src="{{ asset('logo-removebg-preview.png') }}" alt="Buggxit Couture" width="140" style="max-width:140px;height:auto;">
        </div>
        <h2 style="color:#D4AF37;margin-top:0;">Thank you for your order</h2>
        <p style="color:#ffffff;">Hi{{ ($order->name ?? $order->user?->name) ? ' '.($order->name ?? $order->user->name) : '' }}, your order has been confirmed and payment received. Here's a summary:</p>

        <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Order number:</span> <span style="color:#ffffff;">{{ $order->order_number }}</span></p>

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

        @if ($order->courier_method === 'pep' && $order->pep_point)
            <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Collect from PEP:</span><br>
                <span style="color:#ffffff;">
                    {{ $order->pep_point['name'] ?? '' }}<br>
                    {{ $order->pep_point['address'] ?? '' }}
                </span>
            </p>
        @elseif ($order->shippingAddress)
            <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Shipping to:</span><br>
                <span style="color:#ffffff;">
                    {{ $order->shippingAddress->address_line1 }}@if ($order->shippingAddress->address_line2), {{ $order->shippingAddress->address_line2 }}@endif<br>
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postal_code }}<br>
                    {{ $order->shippingAddress->country }}
                </span>
            </p>
        @endif

        <p style="color:#999999;font-size:13px;">We'll be in touch with updates as your order is prepared and shipped. If you have any questions, just reply to this email.</p>
    </div>
</body>

</html>
