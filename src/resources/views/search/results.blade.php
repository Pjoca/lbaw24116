@extends('layouts.app')

@section('content')

<!-- Search Container -->
<div class="max-w-4xl mx-auto mt-8 p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
    <form id="search-form" action="{{ route('search') }}" method="GET" class="flex flex-wrap items-center gap-4 justify-center">
        <!-- Dropdown -->
        <select 
            id="search-type" 
            name="type" 
            class="w-28 border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-teal-400 focus:outline-none">
            <option value="users">Users</option>
            <option value="groups">Groups</option>
            <option value="posts">Posts</option>
            <option value="comments">Comments</option>
        </select>

        <!-- Search Bar -->
        <input 
            type="text" 
            id="search-bar" 
            name="query" 
            placeholder="Search" 
            class="w-64 border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-teal-400 focus:outline-none placeholder-gray-500" 
            autocomplete="off" required>

        <!-- Date Inputs -->
        <div id="date-filters" class="hidden flex gap-2"></div>
        <input 
            type="date" 
            id="start-date" 
            name="start_date" 
            value="{{ old('start_date', $startDate ?? '') }}" 
            class="w-36 border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-teal-400 focus:outline-none">

        <input 
            type="date" 
            id="end-date" 
            name="end_date" 
            value="{{ old('end_date', $endDate ?? '') }}" 
            class="w-36 border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-teal-400 focus:outline-none">

        <!-- Search Button -->
        <button 
            type="submit" 
            id="search-button" 
            class="px-4 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600 focus:ring-2 focus:ring-teal-400 focus:outline-none">
            Search
        </button>
    </form>

    <div id="search-results" class="absolute top-full left-0 z-10 mt-2 w-full max-h-52 overflow-y-auto bg-white border border-gray-300 rounded-lg shadow-lg hidden">
        <!-- Dynamically loaded content -->
    </div>

</div>

<!-- Empty State Message -->
@if(empty($query))
    <p class="mt-8 text-gray-500 text-center italic">
        No search query entered. Please use the search bar above.
    </p>
@else
    <!-- Results Section -->
    <div class="max-w-3xl mx-auto mt-8 space-y-6">
        <h2 class="text-xl font-bold block text-teal-600">
        

            Search Results for "{{ $query }}"
        </h2>

        @if(!$users->isEmpty())
            <!-- User Results -->
            <h3 class="text-lg font-semibold block text-teal-600">
                User Results
            </h3>
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <li class="py-4 flex items-center justify-between">
                        <span class="block text-teal-600">{{ $user->name }}</span>
                        <a href="{{ route('profile.show', $user->id) }}" class="block text-teal-600 hover:text-teal-800 font-medium transition">
                            View Profile
                        </a>
                    </li>
                @endforeach
            </ul>

    <!-- Pagination for Users -->
    <div class="mt-4">
    @if ($users->hasPages())
    <nav class="flex justify-between items-center mt-4">
        {{-- First Page and Previous Page --}}
        <div>
            @if (!$users->onFirstPage())
                <a href="{{ $users->url(1) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">First</a>
                <a href="{{ $users->previousPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Previous</a>
            @endif
        </div>

        {{-- Page Links (Center) --}}
        <div class="flex space-x-2">
            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if ($page == $users->currentPage())
                    <span class="px-3 py-2 border rounded bg-teal-500 text-white">{{ $page }}</span>
                @elseif(abs($page - $users->currentPage()) <= 2) {{-- Show 2 pages around current --}}
                    <a href="{{ $url }}" 
                       class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">{{ $page }}</a>
                @endif
            @endforeach
        </div>

        {{-- Next Page and Last Page --}}
        <div>
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Next</a>
                <a href="{{ $users->url($users->lastPage()) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Last</a>
            @endif
        </div>
    </nav>
@endif

    </div>
        @endif

        @if(!$groups->isEmpty())
            <!-- Group Results -->
            <h3 class="text-lg font-semibold block text-teal-600">
                Group Results
            </h3>
            <ul class="divide-y divide-gray-200">
                @foreach($groups as $group)
                    <li class="py-4 flex items-center justify-between">
                        <span class="block text-teal-600">{{ $group->name }}</span>
                        <a href="{{ route('groups.show', $group->id) }}" class="block text-teal-600 hover:text-teal-800 font-medium transition">
                            View Group
                        </a>
                    </li>
                @endforeach
            </ul>

        <!-- Pagination for Groups -->
        <div class="mt-4">
        @if ($groups->hasPages())
    <nav class="flex justify-between items-center mt-4">
        {{-- First Page and Previous Page --}}
        <div>
            @if (!$groups->onFirstPage())
                <a href="{{ $groups->url(1) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">First</a>
                <a href="{{ $groups->previousPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Previous</a>
            @endif
        </div>

        {{-- Page Links (Center) --}}
        <div class="flex space-x-2">
            @foreach ($groups->getUrlRange(1, $groups->lastPage()) as $page => $url)
                @if ($page == $groups->currentPage())
                    <span class="px-3 py-2 border rounded bg-teal-500 text-white">{{ $page }}</span>
                @elseif(abs($page - $groups->currentPage()) <= 2) {{-- Show 2 pages around current --}}
                    <a href="{{ $url }}" 
                       class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">{{ $page }}</a>
                @endif
            @endforeach
        </div>

        {{-- Next Page and Last Page --}}
        <div>
            @if ($groups->hasMorePages())
                <a href="{{ $groups->nextPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Next</a>
                <a href="{{ $groups->url($groups->lastPage()) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Last</a>
            @endif
        </div>
    </nav>
@endif

        </div>

        @endif

        @if(!$posts->isEmpty())
            <!-- Post Results -->
            <h3 class="text-lg font-semibold block text-teal-600">
                Post Results
            </h3>
            <div class="space-y-6">
                @each('partials.post', $posts, 'post')
            </div>

        <!-- Pagination for Posts -->
        <div class="mt-4">
        @if ($posts->hasPages())
    <nav class="flex justify-between items-center mt-4">
        {{-- First Page and Previous Page --}}
        <div>
            @if (!$posts->onFirstPage())
                <a href="{{ $posts->url(1) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">First</a>
                <a href="{{ $posts->previousPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Previous</a>
            @endif
        </div>

        {{-- Page Links (Center) --}}
        <div class="flex space-x-2">
            @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                @if ($page == $posts->currentPage())
                    <span class="px-3 py-2 border rounded bg-teal-500 text-white">{{ $page }}</span>
                @elseif(abs($page - $posts->currentPage()) <= 2) {{-- Show 2 pages around current --}}
                    <a href="{{ $url }}" 
                       class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">{{ $page }}</a>
                @endif
            @endforeach
        </div>

        {{-- Next Page and Last Page --}}
        <div>
            @if ($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Next</a>
                <a href="{{ $posts->url($posts->lastPage()) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Last</a>
            @endif
        </div>
    </nav>
@endif

        </div>

        @endif

        @if(!$comments->isEmpty())
            <!-- Comment Results -->
            <h3 class="text-lg font-semibold block text-teal-600">
                Comment Results
            </h3>
            <div class="space-y-6">
                @foreach($comments as $comment)
                    <div class="border border-gray-200 rounded-lg p-4 shadow">
                        <div class="mb-2">
                            <h3 class="font-bold text-teal-600">Comment</h3>
                            <p class="text-gray-700">{{ $comment->content }}</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <h4 class="font-semibold text-teal-600">Associated Post:</h4>
                            @include('partials.post', ['post' => $comment->post])
                        </div>
                    </div>
                @endforeach
            </div>

        <!-- Pagination for Comments -->
        <div class="mt-4">
        @if ($comments->hasPages())
    <nav class="flex justify-between items-center mt-4">
        {{-- First Page and Previous Page --}}
        <div>
            @if (!$comments->onFirstPage())
                <a href="{{ $comments->url(1) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">First</a>
                <a href="{{ $comments->previousPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Previous</a>
            @endif
        </div>

        {{-- Page Links (Center) --}}
        <div class="flex space-x-2">
            @foreach ($comments->getUrlRange(1, $comments->lastPage()) as $page => $url)
                @if ($page == $comments->currentPage())
                    <span class="px-3 py-2 border rounded bg-teal-500 text-white">{{ $page }}</span>
                @elseif(abs($page - $comments->currentPage()) <= 2) {{-- Show 2 pages around current --}}
                    <a href="{{ $url }}" 
                       class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">{{ $page }}</a>
                @endif
            @endforeach
        </div>

        {{-- Next Page and Last Page --}}
        <div>
            @if ($comments->hasMorePages())
                <a href="{{ $comments->nextPageUrl() }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Next</a>
                <a href="{{ $comments->url($comments->lastPage()) }}" 
                   class="px-3 py-2 border rounded bg-gray-100 text-gray-600 hover:bg-gray-200">Last</a>
            @endif
        </div>
    </nav>
@endif

        </div>
        @endif

        @if($users->isEmpty() && $groups->isEmpty() && $posts->isEmpty() && $comments->isEmpty())
            <!-- No Results -->
            <p class="text-gray-500 text-center mt-6">
                Nothing found matching your search.
            </p>
        @endif
    </div>
@endif

@endsection
