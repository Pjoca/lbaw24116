<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }}">
    <meta property="og:description" content="Description of your page">
    <meta property="og:image" content="{{ asset('path/to/image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
    <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
</head>

<body class="min-h-screen bg-blue-100 font-roboto">

    <div class="min-h-full flex justify-center items-center py-8">

        <!-- Logo -->
        <a href="{{ route('login') }}" class="absolute top-12 left-16 transform transition-transform hover:scale-110">
            <img src="{{ asset('images/LOGO_CONNECT_FEUP.png') }}" alt="Logo" class="w-40 h-40 rounded-full">
        </a>

        <!-- Main Form Wrapper -->
        <div class="w-full max-w-md bg-gradient-to-r from-white to-blue-50 border-2 border-teal-400 rounded-lg p-8 shadow-lg">
            
            <!-- Form Header -->
            <div class="flex justify-center items-center w-20 h-20 bg-white rounded-full mx-auto -mt-16 shadow-lg border-4 border-white">
                <img src="{{ asset('images/LOGO_CONNECT_FEUP.png') }}" alt="Logo" class="w-full h-full object-cover rounded-full">
            </div>

            <form method="POST" action="{{ route('register') }}" class="mt-6 text-center">
                {{ csrf_field() }}

                <!-- User Information Fieldset -->
                <fieldset class="mb-6">
                    <legend class="text-lg font-bold text-teal-500 mb-4">User Information</legend>

                    <!-- Name Field -->
                    <div class="mb-4 text-left">
                        <label for="name" class="block text-sm font-bold text-teal-500 mb-2">Name</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            placeholder="Your full name" 
                            class="w-full px-4 py-3 border rounded-lg shadow-inner bg-blue-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:bg-white"
                            title="Enter your full name."
                            aria-describedby="name-help"
                        >
                        <small id="name-help" class="text-gray-500">This should be your full name.</small>
                        @if ($errors->has('name'))
                            <span class="text-sm text-red-500">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <!-- Username Field -->
                    <div class="mb-4 text-left">
                        <label for="username" class="block text-sm font-bold text-teal-500 mb-2">Username</label>
                        <input 
                            id="username" 
                            type="text" 
                            name="username" 
                            value="{{ old('username') }}" 
                            required 
                            placeholder="Choose a unique username" 
                            class="w-full px-4 py-3 border rounded-lg shadow-inner bg-blue-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:bg-white"
                            title="Choose a unique username that will identify you on the platform."
                            aria-describedby="username-help"
                        >
                        <small id="username-help" class="text-gray-500">Your username should be unique and memorable.</small>
                        @if ($errors->has('username'))
                            <span class="text-sm text-red-500">{{ $errors->first('username') }}</span>
                        @endif
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4 text-left">
                        <label for="email" class="block text-sm font-bold text-teal-500 mb-2">E-Mail Address</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="Enter a valid email address" 
                            class="w-full px-4 py-3 border rounded-lg shadow-inner bg-blue-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:bg-white"
                            title="Provide a valid email address."
                            aria-describedby="email-help"
                        >
                        <small id="email-help" class="text-gray-500">We'll never share your email with anyone else.</small>
                        @if ($errors->has('email'))
                            <span class="text-sm text-red-500">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4 text-left">
                        <label for="password" class="block text-sm font-bold text-teal-500 mb-2">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="Choose a strong password" 
                            class="w-full px-4 py-3 border rounded-lg shadow-inner bg-blue-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:bg-white"
                            title="Choose a strong password with at least 8 characters, including letters and numbers."
                            aria-describedby="password-help"
                        >
                        <small id="password-help" class="text-gray-500">Your password should have at least 8 characters and include letters and numbers.</small>
                        @if ($errors->has('password'))
                            <span class="text-sm text-red-500">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6 text-left">
                        <label for="password-confirm" class="block text-sm font-bold text-teal-500 mb-2">Confirm Password</label>
                        <input 
                            id="password-confirm" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            placeholder="Re-enter your password" 
                            class="w-full px-4 py-3 border rounded-lg shadow-inner bg-blue-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:bg-white"
                            title="Re-enter your password to confirm it matches the one above."
                            aria-describedby="password-confirm-help"
                        >
                        <small id="password-confirm-help" class="text-gray-500">Re-enter the password to ensure they match.</small>
                    </div>
                </fieldset>

                <!-- Action Buttons Fieldset -->
                <fieldset>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-teal-400 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-teal-500 transition"
                        title="Click to register your new account."
                    >
                        Register
                    </button>

                    <!-- Login Link -->
                    <a href="{{ route('login') }}" 
                        class="block w-full mt-4 text-teal-400 font-bold border border-teal-400 py-3 rounded-lg hover:bg-teal-400 hover:text-white transition"
                        title="Already have an account? Click to login."
                    >
                        Login
                    </a>
                </fieldset>
            </form>
        </div>
    </div>

</body>
</html>