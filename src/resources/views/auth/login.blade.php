<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }}">
    <meta property="og:description" content="Description of your page">
    <meta property="og:image" content="{{ asset('path/to/image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')
    <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
</head>
<body class="flex items-center justify-center h-screen bg-blue-100 font-roboto overflow-y-auto">
    
    <!-- Logo -->
    <a href="{{ route('login') }}" class="absolute top-12 left-16 transform transition-transform hover:scale-110">
        <img src="{{ asset('images/LOGO_CONNECT_FEUP.png') }}" alt="Logo" class="w-40 h-40 rounded-full">
    </a>

    <!-- Form Container -->
    <div class="w-full max-w-md bg-gradient-to-r from-white to-blue-50 border-2 border-teal-400 rounded-lg p-8 shadow-lg relative">
        
        <!-- Form Header -->
        <div class="flex justify-center items-center w-20 h-20 bg-white rounded-full mx-auto -mt-16 shadow-lg border-4 border-white">
            <img src="{{ asset('images/LOGO_CONNECT_FEUP.png') }}" alt="Logo" class="w-full h-full object-cover rounded-full">
        </div>

        <form method="POST" action="{{ route('login') }}" class="mt-6 text-center">
            {{ csrf_field() }}

            <!-- User Credentials Fieldset -->
            <fieldset class="mb-6">
                <legend class="text-lg font-bold text-teal-500 mb-4">User Credentials</legend>

                <!-- Email Field -->
                <div class="mb-4 text-left">
                    <label for="email" class="block text-sm font-bold text-teal-500 mb-2">E-mail</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        required 
                        autofocus
                        class="w-full px-4 py-3 border rounded-lg shadow-inner bg-blue-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:bg-white"
                    >
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-4 text-left">
                    <label for="password" class="block text-sm font-bold text-teal-500 mb-2">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border rounded-lg shadow-inner bg-blue-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:bg-white"
                    >
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Forgot Password Link -->
                <div class="mb-4 text-right">
                    <a href="{{ route('password.request') }}" class="text-sm text-teal-500 hover:underline">Forgot Password?</a>
                </div>
            </fieldset>

            <!-- Action Buttons Fieldset -->
            <fieldset>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-teal-400 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-teal-500 transition"
                >
                    Login
                </button>

                <!-- Register Link -->
                <a href="{{ route('register') }}" 
                    class="block w-full mt-4 text-teal-400 font-bold border border-teal-400 py-3 rounded-lg hover:bg-teal-400 hover:text-white transition"
                >
                    Register
                </a>

                <!-- Continue as Guest -->
                <a href="{{ route('timeline.public') }}" 
                    class="block w-full mt-4 bg-gray-200 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-300 transition"
                >
                    Continue as a Guest
                </a>
            </fieldset>

            <!-- Success Message -->
            @if (session('success'))
                <p class="text-teal-500 text-sm mt-4">{{ session('success') }}</p>
            @endif
        </form>
    </div>
</body>
</html>
