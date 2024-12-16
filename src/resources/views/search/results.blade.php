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
            id="search-query" 
            name="query" 
            placeholder="Search" 
            class="w-64 border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-teal-400 focus:outline-none placeholder-gray-500" 
            autocomplete="off" required>

        <!-- Date Inputs -->
        <div id="date-filters" class="hidden">
        <input 
            type="date" 
            id="search-date1" 
            name="start_date" 
            value="{{ old('start_date', $startDate ?? '') }}" 
            class="w-36 border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-teal-400 focus:outline-none">

        <input 
            type="date" 
            id="search-date2" 
            name="end_date" 
            value="{{ old('end_date', $endDate ?? '') }}" 
            class="w-36 border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-teal-400 focus:outline-none">

        <!-- Search Button -->
        <button 
            type="submit" 
            class="px-4 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600 focus:ring-2 focus:ring-teal-400 focus:outline-none">
            Search
        </button>
    </form>
</div>


<!-- Empty State Message -->
@if(empty($query))
    <p class="mt-8 text-gray-500 text-center italic">
        No search query entered. Please use the search bar above.
    </p>
@else
    <!-- Results Section -->
    <div class="max-w-3xl mx-auto mt-8 space-y-6">
        <h2 class="text-xl font-bold text-gray-700 border-b border-gray-200 pb-2">
            Search Results for "{{ $query }}"
        </h2>

        @if(!$users->isEmpty())
            <!-- User Results -->
            <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2">
                User Results
            </h3>
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <li class="py-4 flex items-center justify-between">
                        <span class="font-medium">{{ $user->name }}</span>
                        <a href="{{ route('profile.show', $user->id) }}" class="text-teal-600 hover:underline">
                            View Profile
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(!$groups->isEmpty())
            <!-- Group Results -->
            <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2">
                Group Results
            </h3>
            <ul class="divide-y divide-gray-200">
                @foreach($groups as $group)
                    <li class="py-4 flex items-center justify-between">
                        <span class="font-medium">{{ $group->name }}</span>
                        <a href="{{ route('groups.show', $group->id) }}" class="text-teal-600 hover:underline">
                            View Group
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(!$posts->isEmpty())
            <!-- Post Results -->
            <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2">
                Post Results
            </h3>
            <div class="space-y-6">
                @each('partials.post', $posts, 'post')
            </div>
        @endif

        @if(!$comments->isEmpty())
            <!-- Comment Results -->
            <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchType = document.getElementById('search-type');
    const dateFilters = document.getElementById('date-filters');

    // Function to toggle visibility of date inputs
    function toggleDateInputs() {
        const selectedType = searchType.value;
        if (selectedType === 'groups' || selectedType === 'posts') {
            dateFilters.classList.remove('hidden');
        } else {
            dateFilters.classList.add('hidden');
        }
    }

    // Initialize visibility on page load
    toggleDateInputs();

    // Add event listener to the dropdown
    searchType.addEventListener('change', toggleDateInputs);
});
</script>
