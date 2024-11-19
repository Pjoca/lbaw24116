<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Add styles if needed -->
</head>
<body>
    <header>
        <h1>Welcome to the Application</h1>
    </header>

    <nav>
        <ul>
            @auth
                <li><a href="{{ route('cards') }}">My Cards</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endauth
        </ul>
    </nav>

    <main>
        <p>
            @auth
                Welcome back, {{ Auth::user()->name }}!
            @else
                Please log in or register to access all features of the application.
            @endauth
        </p>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} My Application. All rights reserved.</p>
    </footer>
</body>
</html>
