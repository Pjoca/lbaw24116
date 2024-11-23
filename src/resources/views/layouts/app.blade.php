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
    </head>
    <body class="bg-gray-100 text-gray-800 font-sans">
        @vite('resources/js/app.js')
        <main class="p-4">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-center bg-blue-600 text-white p-4 rounded-lg mb-6">
                <h1>
                    <a href="{{ url('/timeline/public') }}" class="text-xl font-bold uppercase hover:text-yellow-500 transition">
                        ConnectFeup
                    </a>
                </h1>
                <nav>
                    <ul class="flex gap-4 mt-4 md:mt-0">
                        <li>
                            <a href="{{ url('/matchmaking') }}" class="text-sm hover:bg-yellow-500 hover:text-white py-2 px-3 rounded transition">
                                Matchmaking
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/search') }}" class="text-sm hover:bg-yellow-500 hover:text-white py-2 px-3 rounded transition">
                                Search
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/messages') }}" class="text-sm hover:bg-yellow-500 hover:text-white py-2 px-3 rounded transition">
                                Messages
                            </a>
                        </li>
                        <!-- Menu Suspenso para o "Post" -->
                        <!-- <li class="relative group">
                            <a href="#" class="text-sm hover:bg-yellow-500 hover:text-white py-2 px-3 rounded transition">
                                Post
                            </a>
                            Dropdown com opções de Criar ou Editar 
                            <div class="absolute left-0 hidden bg-white shadow-lg rounded-lg mt-2 group-hover:block">
                                <a href="{{ route('posts.create') }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-100">Criar Post</a>
                                Você pode adicionar lógica para o ID do post aqui 
                                <a href="{{ route('posts.edit', 1) }}" class="block px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-100">Editar Post</a>
                            </div>
                        </li> -->
                    </ul>
                </nav>
                @if (Auth::check())
                    <div class="flex items-center gap-4 mt-4 md:mt-0">
                        <span class="text-sm font-bold">{{ Auth::user()->name }}</span>
                        <a href="{{ url('/logout') }}" class="bg-red-500 text-sm text-white py-2 px-3 rounded hover:bg-red-600 transition">
                            Logout
                        </a>
                    </div>
                @endif

                <!-- Search bar for login page -->
                @if (Request::is('login'))
                    <div class="flex gap-2 mt-4 w-full md:w-auto">
                        <input type="text" placeholder="Search in feed..." class="flex-1 border border-gray-300 rounded p-2 text-sm">
                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-800 transition">
                            Search
                        </button>
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
