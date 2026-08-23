<!DOCTYPE html>
<html>

<head>
    <title>New Contact Message</title>
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
    </style>
</head>

<body>
    <div class="container">
        <div style="text-align:center;margin-bottom:20px;">
            <img src="{{ asset('logo.webp') }}" alt="Buggxit Couture" width="140" style="max-width:140px;height:auto;">
        </div>
        <h2>New message from {{ $data['name'] }}</h2>
        <p><span class="label">Email:</span> {{ $data['email'] }}</p>
        <p><span class="label">Subject:</span> {{ $data['subject'] }}</p>
        <p><span class="label">Message:</span></p>
        <p>{!! nl2br(e($data['message'])) !!}</p>
        @if ($data['newsletter'] ?? false)
            <p>✅ Subscribed to newsletter</p>
        @endif
    </div>
</body>

</html>
