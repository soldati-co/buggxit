<!DOCTYPE html>
<html>

<head>
    <title>New Contact Message</title>
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
    </style>
</head>

<body style="font-family:'Manrope',sans-serif;background:#0a0a0a;color:#ffffff;padding:20px;">
    <div class="container" style="max-width:600px;margin:0 auto;background:#111111;border:1px solid #D4AF37;border-radius:10px;padding:20px;">
        <div style="text-align:center;margin-bottom:20px;">
            <img src="{{ asset('logo.webp') }}" alt="Buggxit Couture" width="140" style="max-width:140px;height:auto;">
        </div>
        <h2 style="color:#D4AF37;margin-top:0;">New message from {{ $data['name'] }}</h2>
        <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Email:</span> <span style="color:#ffffff;">{{ $data['email'] }}</span></p>
        <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Subject:</span> <span style="color:#ffffff;">{{ $data['subject'] }}</span></p>
        <p style="color:#ffffff;"><span style="color:#D4AF37;font-weight:bold;">Message:</span></p>
        <p style="color:#ffffff;">{!! nl2br(e($data['message'])) !!}</p>
        @if ($data['newsletter'] ?? false)
            <p style="color:#ffffff;">✅ Subscribed to newsletter</p>
        @endif
    </div>
</body>

</html>
