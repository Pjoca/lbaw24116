<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite('resources/css/app.css')
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/layout.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
    </head>
    <body>
        <main>
        <header>
    <h1><a href="{{ url('/home') }}">ConnectFeup</a></h1>
    <nav>
        <ul>
            <li><a href="{{ url('/home') }}">Homepage</a></li>
            <li><a href="{{ url('/matchmaking') }}">Matchmaking</a></li>
            <li><a href="{{ url('/search') }}">Search</a></li>
            <li><a href="{{ url('/messages') }}">Messages</a></li>
            <li><a href="{{ url('/post') }}">Post</a></li>
        </ul>
    </nav>
    
    @if (Auth::check())
        <div class="user-info">
            <span>{{ Auth::user()->name }}</span>
            <a class="button" href="{{ url('/logout') }}">Logout</a>
        </div>
    @endif

    {{-- Condicional para exibir elementos específicos por página --}}
    @if (Request::is('login'))
        <div class="search-bar">
            <input type="text" placeholder="Search in feed...">
            <button type="submit">Search</button>
        </div>
    @endif
</header>

            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>
