<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ request()->route('token') ?? '' }}" />
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" />
        <input type="password" name="password" placeholder="New password" />
        <input type="password" name="password_confirmation" placeholder="Confirm password" />
        <button type="submit">Reset</button>
    </form>
</body>
</html>
