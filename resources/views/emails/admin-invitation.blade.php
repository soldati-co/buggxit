<!DOCTYPE html>
<html>

<head>
    <title>Admin Invitation</title>
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
            <img src="{{ asset('logo-removebg-preview.png') }}" alt="Buggxit Couture" width="140" style="max-width:140px;height:auto;">
        </div>
        <h2 style="color:#D4AF37;margin-top:0;">You've been invited as an admin</h2>
        <p style="color:#ffffff;">
            {{ $inviterName ? $inviterName.' has invited' : "You've been invited" }} you to join the BUGGXIT
            Couture admin dashboard, with full access to manage products, orders, and site settings.
        </p>

        <p style="text-align:center;margin:28px 0;">
            <a href="{{ $acceptUrl }}"
                style="display:inline-block;background:#D4AF37;color:#0a0a0a;font-weight:bold;padding:14px 28px;border-radius:8px;text-decoration:none;">
                Create Your Admin Account
            </a>
        </p>

        <p style="color:#999999;font-size:13px;">
            This invitation is for <span style="color:#ffffff;">{{ $email }}</span> and expires in 7 days.
            If the button above doesn't work, copy and paste this link into your browser:
        </p>
        <p style="color:#D4AF37;font-size:12px;word-break:break-all;">{{ $acceptUrl }}</p>

        <p style="color:#999999;font-size:13px;">If you weren't expecting this invitation, you can safely ignore this email.</p>
    </div>
</body>

</html>
