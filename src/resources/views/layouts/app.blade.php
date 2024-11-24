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
            <header class="flex flex-col md:flex-row justify-between items-center bg-teal-400 text-white p-4 rounded-lg mb-6">
                <h1>
                    <a href="{{ url('/timeline/public') }}" class="text-xl font-bold uppercase hover:text-teal-600 transition">
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
                                    placeholder="Search" 
                                    class="flex-1 border border-gray-300 rounded p-2 text-sm" 
                                    style="color: #000; border: 1px solid #ccc; padding: 8px; border-radius: 4px;" 
                                    required>
                            </form>
                            <div id="search-results" class="absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-md hidden"></div>
                        </div>
                        </li>
                        @if (Auth::check())
                            <li>
                                <a href="{{ url('/matchmaking') }}" class="text-sm hover:bg-teal-600 hover:text-white py-2 px-3 rounded transition">
                                    Matchmaking
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/messages') }}" class="text-sm hover:bg-teal-600 hover:text-white py-2 px-3 rounded transition">
                                    Messages
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
                @if (Auth::check())
                    <div class="flex items-center gap-4 mt-4 md:mt-0">
                        <a href="{{ route('profile.show', ['id' => Auth::id()]) }}" class="text-sm font-bold hover:text-yellow-500 transition">
                            {{ Auth::user()->name }}
                        </a>
                        <a href="{{ url('/logout') }}" class="bg-red-500 text-sm text-white py-2 px-3 rounded hover:bg-red-600 transition">
                            Logout
                        </a>
                    </div>
                @else
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('login') }}" class="bg-teal-500 text-sm text-white py-2 px-4 rounded hover:bg-teal-600 transition">
                            Login
                        </a>
                    </div>
                @endif

                <!-- Search bar for login page -->
                @if (Request::is('login'))
                    <div class="flex gap-2 mt-4 w-full md:w-auto">
                        <input type="text" placeholder="Search in feed..." class="flex-1 border border-gray-300 rounded p-2 text-sm">
                        <button type="submit" class="bg-teal-600 text-white py-2 px-4 rounded hover:bg-teal-600 transition">
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

        <style>

/* Style the search input field */
#search-bar {
    background-color: #fff; /* White background */
    color: #333; /* Dark text color for better readability */
    padding: 10px 15px;
    border: 1px solid #ddd; /* Light border for subtle separation */
    border-radius: 8px; /* Rounded corners */
    font-size: 16px; /* Readable font size */
    width: 100%; /* Ensure it spans full width of the container */
}

/* Placeholder styling */
#search-bar::placeholder {
    color: #888; /* Lighter color for placeholder text */
}

/* Ensure the search results dropdown has the right styling */
#search-results {
    position: absolute; /* To prevent it from interfering with other elements */
    top: 100%; /* Position it directly below the search bar */
    left: 0;
    z-index: 10; /* Ensure it's above other content */
    max-height: 200px; /* Limit the height */
    overflow-y: auto; /* Allow scrolling if necessary */
    background-color: white; /* White background for contrast */
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

#search-results ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

#search-results li {
    padding: 10px 15px; /* Add padding to make text less cramped */
    font-size: 14px; /* Slightly increase the font size for better readability */
    color: #333; /* Darker text for better contrast */
    cursor: pointer;
}

#search-results li:hover {
    background-color: #f0f0f0; /* Light background color on hover */
    color: #000; /* Darker text on hover */
}

#search-results li a {
    text-decoration: none;
    color: inherit; /* Inherit color from li element */
}

#search-results li a:hover {
    text-decoration: underline; /* Underline on hover for links */
}

        </style>


<script>
document.getElementById('search-query').addEventListener('input', function () {
    const query = this.value;
    const resultsDiv = document.getElementById('search-results');

    if (query.length > 0) { 

        fetch(`/search/users?query=${encodeURIComponent(query)}&limit=3`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network error');
                }
                return response.text();
            })
            .then(data => {
                resultsDiv.innerHTML = data;
                resultsDiv.classList.remove('hidden');
            })
            .catch(err => {console.error('Error:', err);});

    } else {
        resultsDiv.classList.add('hidden'); 
        resultsDiv.innerHTML = ''; 
    }
});

document.addEventListener('click', function (event) {
    const searchBox = document.getElementById('search-query');
    const resultsDiv = document.getElementById('search-results');

    if (!searchBox.contains(event.target) && !resultsDiv.contains(event.target)) {
        resultsDiv.classList.add('hidden');
    }
});
</script>

    </body>
</html>
