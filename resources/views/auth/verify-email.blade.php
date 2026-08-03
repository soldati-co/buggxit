<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Email</title>
</head>
<body>
    <h1>Please verify your email address</h1>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>
</body>
</html>
