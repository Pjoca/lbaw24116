<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="{{ asset('css/register.css') }}" rel="stylesheet">
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
</head>
<body>
    <a class="logo-link" href="{{ route('login') }}">
        <img src="{{ asset('images/LOGO_CONNECT_FEUP.png') }}" alt="Logo" class="logo">
    </a>
    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
        @if ($errors->has('name'))
          <span class="error">
              {{ $errors->first('name') }}
          </span>
        @endif

        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required>
        @if ($errors->has('username'))
          <span class="error">
              {{ $errors->first('username') }}
          </span>
        @endif

        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
          <span class="error">
              {{ $errors->first('email') }}
          </span>
        @endif

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @if ($errors->has('password'))
          <span class="error">
              {{ $errors->first('password') }}
          </span>
        @endif

        <label for="password-confirm">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" required>

        <button type="submit" class="btn-primary">
          Register
        </button>
        <a class="button-outline" href="{{ route('login') }}">Login</a>
    </form>
</body>
</html>
