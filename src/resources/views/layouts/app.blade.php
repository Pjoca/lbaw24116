<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{Auth::id()}}">
        @vite('resources/css/app.css')
        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body class="bg-gray-100 text-gray-800 font-sans">
        @vite('resources/js/app.js')
        @vite('resources/js/notifications.js')
        @include('components.alert')
        <main class="p-4">
            <!--  Header -->
            <header class="flex flex-col md:flex-row justify-between items-center bg-teal-400 text-white p-4 rounded-lg mb-6">
                <h1>
                    <a href="{{ url('/timeline') }}" class="text-xl font-bold uppercase hover:text-teal-300 transition">
                        ConnectFeup
                    </a>
                </h1>
                <nav>
                    <ul class="flex gap-4 mt-4 md:mt-0">
                        <li>
                            <div class="relative">
                                <form id="search-form" action="{{ route('users.search') }}" method="GET" class="flex gap-2">
                                    <input 
                                        type="text" 
                                        id="search-query"
                                        name="query" 
                                        placeholder="Quick Search" 
                                        class="w-full bg-white text-gray-800 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none placeholder-gray-500" 
                                        autocomplete="off" 
                                        required>
                                </form>
                                <div id="search-results" class="absolute top-full left-0 z-10 mt-2 w-full max-h-52 overflow-y-auto bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                                    <!-- Dynamically loaded content -->
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('users.search') }}" class="text-sm hover:bg-teal-600 py-2 px-3 rounded-lg transition">
                                Search
                            </a>
                        </li>
                        @if (Auth::check())
                            <li>
                                <a href="{{ route('groups.index') }}" class="text-sm hover:bg-teal-600 py-2 px-3 rounded-lg transition">
                                    Groups
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('matchmaking.index') }}" class="text-sm hover:bg-teal-600 py-2 px-3 rounded-lg transition">
                                    Matchmaking
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('messages.index') }}" class="text-sm hover:bg-teal-600 py-2 px-3 rounded-lg transition">
                                    Messages
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
                @if (Auth::check())
                    <div class="flex items-center gap-4 mt-4 md:mt-0">
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button id="notification-bell" class="relative focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11c0-3.866-2.239-7-5-7S8 7.134 8 11v3.159c0 .538-.214 1.055-.595 1.436L6 17h9z" />
                                </svg>
                                <span id="notification-count" class="absolute top-0 right-0 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center hidden">0</span>
                            </button>
                            <div id="notification-dropdown" class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden">
                                @include('partials.notifications', ['notifications' => Auth::user()->notifications])
                            </div>
                        </div>

                        <a href="{{ route('profile.show', ['id' => Auth::id()]) }}" class="text-sm font-bold hover:text-yellow-300 transition">
                            {{ Auth::user()->name }}
                        </a>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.panel') }}" class="bg-yellow-400 text-sm text-white py-2 px-4 rounded-lg hover:bg-yellow-500 transition">
                                Admin Panel
                            </a>
                        @endif
                        <a href="{{ url('/logout') }}" class="bg-red-500 text-sm text-white py-2 px-3 rounded-lg hover:bg-red-600 transition">
                            Logout
                        </a>
                    </div>
                @else
                    <div class="flex gap-4 mt-4 md:mt-0">
                        <a href="{{ route('login') }}" class="bg-teal-600 text-sm text-white py-2 px-4 rounded-lg hover:bg-teal-700 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-teal-600 text-sm text-white py-2 px-4 rounded-lg hover:bg-teal-700 transition">
                            Register
                        </a>
                    </div>
                @endif
            </header>

            <!-- Content Section -->
            <section id="content" class="bg-white p-6 rounded-lg shadow-md">
                @yield('content')
            </section>
        </main>
    </body>
</html>
