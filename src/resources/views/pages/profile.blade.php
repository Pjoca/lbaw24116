<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <h1>User Profile</h1>
    </header>

    <main>
        <h2>{{ $user['name'] }}'s Profile</h2>
        <p><strong>Username:</strong> {{ $user['username'] }}</p>
        <p><strong>Last Login:</strong> {{ $user['last_login'] }}</p>

        @if (isset($user['email']))
            <p><strong>Email:</strong> {{ $user['email'] }}</p>
        @endif

        <a href="{{ route('home') }}">Back to Home</a>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} My Application. All rights reserved.</p>
    </footer>
</body>
</html>
