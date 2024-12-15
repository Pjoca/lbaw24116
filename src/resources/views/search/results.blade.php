@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <!-- Search Form -->
    <div class="relative bg-white p-6 rounded-lg shadow-md">
        <form id="search-form" action="{{ route('search') }}" method="GET" class="flex flex-wrap gap-4">
            <!-- Dropdown -->
            <select 
                name="type" 
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none">
                <option value="users">Users</option>
                <option value="groups">Groups</option>
                <option value="posts">Posts</option>
                <option value="comments">Comments</option>
            </select>

            <!-- Search Input -->
            <input 
                type="text" 
                id="search-query" 
                name="query" 
                placeholder="Search" 
                class="flex-grow bg-white text-gray-800 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none placeholder-gray-500" 
                autocomplete="off" 
                required>

            <!-- Date Range Fields -->
            <input 
                type="date" 
                id="search-date1" 
                name="start_date" 
                value="{{ old('start_date', $startDate ?? '') }}" 
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none" 
                placeholder="Start Date">

            <input 
                type="date" 
                id="search-date2" 
                name="end_date" 
                value="{{ old('end_date', $endDate ?? '') }}" 
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none" 
                placeholder="End Date">

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 focus:ring-2 focus:ring-teal-400 focus:outline-none">
                Search
            </button>
        </form>
    </div>

    <!-- Results Section -->
    <div class="mt-8">
        @if(empty($query))
            <p class="text-gray-500">No search query entered. Please use the search bar above.</p>
        @else
            <h2 class="text-xl font-bold mb-4">Search Results for "{{ $query }}"</h2>

            <!-- Users Results -->
            @if(!$users->isEmpty())
                <ul class="divide-y divide-gray-200 w-full max-w-2xl">
                    @foreach($users as $user)
                        <li class="py-4 flex items-center justify-between">
                            <span class="font-medium">{{ $user->name }}</span>
                            <a href="{{ route('profile.show', $user->id) }}" class="text-teal-600 hover:underline">
                                View Profile
                            </a>
                        </li>
                    @endforeach
                </ul>

            <!-- Groups Results -->
            @elseif(!$groups->isEmpty())
                <ul class="divide-y divide-gray-200 w-full max-w-2xl">
                    @foreach($groups as $group)
                        <li class="py-4 flex items-center justify-between">
                            <span class="font-medium">{{ $group->name }}</span>
                            <a href="{{ route('groups.show', $group->id) }}" class="text-teal-600 hover:underline">
                                View Group
                            </a>
                        </li>
                    @endforeach
                </ul>   

            <!-- Posts Results -->
            @elseif(!$posts->isEmpty())
                <div id="posts-container" class="max-w-3xl mx-auto space-y-8">
                    @each('partials.post', $posts, 'post')
                </div> 

            <!-- Comments Results -->
            @elseif(!$comments->isEmpty())
                <div id="comments-container" class="max-w-3xl mx-auto space-y-8">
                    @foreach($comments as $comment)
                        <div class="border rounded-lg p-4 shadow">
                            <div class="mb-2">
                                <h3 class="font-bold text-teal-600">Comment</h3>
                                <p class="text-gray-700">{{ $comment->content }}</p>
                            </div>
                            <div class="bg-gray-100 p-2 rounded">
                                <h4 class="font-semibold text-teal-600">Associated Post:</h4>
                                @include('partials.post', ['post' => $comment->post])
                            </div>
                        </div>
                    @endforeach
                </div>

            <!-- No Results -->
            @else
                <p class="text-gray-500">Nothing found matching your search.</p>
            @endif
        @endif
    </div>
</div>
@endsection
