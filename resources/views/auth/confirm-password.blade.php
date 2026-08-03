<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirm Password</title>
</head>
<body>
    <h1>Confirm Password</h1>
    <form method="POST" action="{{ url('confirm-password') }}">
        @csrf
        <input type="password" name="password" placeholder="Password" />
        <button type="submit">Confirm</button>
    </form>
</body>
</html>
