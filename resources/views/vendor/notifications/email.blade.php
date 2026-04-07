<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – BUGGXIT Couture</title>
    <style>
        /* Reset styles for email clients */
        body, table, td, div, p, a {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font-family: 'Segoe UI', 'Manrope', Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #0a0a0a;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #111111;
            border-radius: 16px;
            border: 1px solid #2a2a2a;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            padding: 32px 24px;
            text-align: center;
            border-bottom: 1px solid #d4af37;
        }
        .logo {
            max-width: 140px;
            margin-bottom: 16px;
        }
        .brand-name {
            color: #d4af37;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        .tagline {
            color: #9ca3af;
            font-size: 12px;
        }
        .content {
            padding: 40px 32px;
            background-color: #111111;
        }
        .greeting {
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .message {
            color: #d1d5db;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 28px;
        }
        .button {
            display: inline-block;
            background-color: #d4af37;
            color: #0a0a0a !important;
            font-weight: 700;
            font-size: 16px;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            margin: 20px 0 24px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #c4a02a;
        }
        .reset-link {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            padding: 14px;
            margin-top: 16px;
            word-break: break-all;
            font-size: 13px;
            color: #9ca3af;
            font-family: monospace;
        }
        .footer {
            background-color: #0a0a0a;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #1f2937;
            font-size: 12px;
            color: #6b7280;
        }
        .footer a {
            color: #d4af37;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                border-radius: 0 !important;
            }
            .content {
                padding: 28px 20px !important;
            }
            .button {
                display: block !important;
                text-align: center !important;
            }
        }
    </style>
</head>
<body style="background-color: #0a0a0a; padding: 24px 16px;">
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('Buggxit_Submark_Gold.svg') }}" alt="BUGGXIT Couture" class="logo" style="max-width: 120px;">
            <div class="brand-name">Buggxit Couture</div>
            <div class="tagline">Est. 2018 • Luxury Redefined</div>
        </div>

        <!-- Body -->
        <div class="content">
            <div class="greeting">Hello {{ $name ?? 'Admin' }},</div>
            <div class="message">
                We received a request to reset the password for your admin account. Click the button below to create a new password. This link is valid for <strong>60 minutes</strong>.
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button" style="color: #0a0a0a;">Reset Password</a>
            </div>

            <div class="message" style="font-size: 14px; margin-top: 16px;">
                If the button above doesn't work, copy and paste this URL into your browser:
            </div>
            <div class="reset-link">
                {{ $resetUrl }}
            </div>

            <div class="message" style="font-size: 14px; margin-top: 24px; color: #9ca3af;">
                If you did not request a password reset, please ignore this email or contact support.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} BUGGXIT Couture. All rights reserved.<br>
            <a href="{{ url('/') }}">{{ config('app.name') }}</a> • Secure Admin Portal
        </div>
    </div>
</body>
</html>