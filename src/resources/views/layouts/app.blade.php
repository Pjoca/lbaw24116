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
        @if(Auth::check())
        @vite('resources/js/notifications.js')
        @endif
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
                            <a href="{{ route('search') }}" class="text-sm hover:bg-teal-600 py-2 px-3 rounded-lg transition">
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
                        @endif
                    </ul>
                </nav>
                @if (Auth::check())
                    <div class="flex items-center gap-4 mt-4 md:mt-0">
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button id="notification-bell" class="flex items-center relative focus:outline-none">
                                <!-- Notification Bell Icon -->
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11c0-3.866-2.239-7-5-7S8 7.134 8 11v3.159c0 .538-.214 1.055-.595 1.436L6 17h9z" />
                                    </svg>

                                    <!-- Notification Count -->
                                    @php 
                                        $notificationCount = Auth::user()->notifications->count();
                                    @endphp
                                    @if ($notificationCount > 0)
                                        <span id="notification-count" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold">
                                            {{ $notificationCount }}
                                        </span>
                                    @elseif ($notificationCount === 0)
                                        <span id="notification-count" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold hidden">
                                            {{ $notificationCount }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Dropdown Arrow -->
                                <svg xmlns="http://www.w3.org/2000/svg" id="notification-arrow" class="h-4 w-4 transform transition-transform ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.939l3.71-3.72a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div id="notification-dropdown" class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden">
                                @include('partials.notifications', ['notifications' => Auth::user()->notifications])
                            </div>
                        </div>
                        <!-- Messages -->
                        <a href="{{ route('messages.index') }}" class="relative flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                            <span id="message-count" class="absolute top-0 right-0 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center hidden">0</span>
                        </a>

                        <div class="relative">
                        <button id="profile-dropdown-toggle" onclick="toggleProfileDropdown()" class="flex items-center gap-2 text-sm font-bold hover:text-yellow-300 transition focus:outline-none ml-4">
                            <img src="{{ asset('storage/' . (Auth::user()->image_url ?? 'users/images/default.png')) }}" alt="User Image" class="w-8 h-8 rounded-full border-2 border-white">
                            <span>{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform" id="dropdown-arrow" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.939l3.71-3.72a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 010-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                            <div id="profile-dropdown" class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden">
                                <ul class="text-gray-800">
                                    <li>
                                        <a href="{{ route('profile.show', Auth::user()) }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                            Profile
                                        </a>
                                    </li>
                                    @if (Auth::user()->isAdmin())
                                        <li>
                                            <a href="{{ route('admin.panel') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
                                                Admin Panel
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ url('/logout') }}" class="block px-4 py-2 text-red-500 hover:bg-red-100 transition">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
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
            <section id="content" class="bg-white p-6 rounded-lg shadow-md">
                @yield('content')
            </section>
        </main>
    </body>
</html>
