@extends('layouts.app')

@section('content')

    <div class="relative">
        <form id="search-form" action="{{ route('search') }}" method="GET" class="flex gap-2">
            <select name="type" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="users">Users</option>
                <option value="groups">Groups</option>
                <option value="posts">Posts</option>
                <option value="comments">Comments</option>
            </select>
                <input 
                    type="text" 
                    id="search-query"
                    name="query" 
                    placeholder="Search" 
                    class="w-full bg-white text-gray-800 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none placeholder-gray-500" 
                    autocomplete="off" 
                    required>

            <!-- Date Range Fields -->
            <input 
                type="date" 
                id="start_date" 
                name="start_date" 
                value="{{ old('start_date', $startDate ?? '') }}" 
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none" 
                placeholder="Start Date">

            <input 
                type="date" 
                id="end_date" 
                name="end_date" 
                value="{{ old('end_date', $endDate ?? '') }}" 
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:outline-none" 
                placeholder="End Date">

            <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg">Search</button>
        </form>

        <div id="search-results" class="absolute top-full left-0 z-10 mt-2 w-full max-h-52 overflow-y-auto bg-white border border-gray-300 rounded-lg shadow-lg hidden">
            <!-- Dynamically loaded content -->
        </div>
    </div>
         
    <!-- Results Section -->

    @if(empty($query))
        <p class="text-gray-500">
            
        </p>
    @else
        <h2 class="text-xl font-bold mb-4">Search Results for "{{ $query }}"</h2>

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
            
        @elseif(!$posts->isEmpty())
            <div id="posts-container" class="max-w-3xl mx-auto space-y-8">
                @each('partials.post', $posts, 'post')
            </div> 

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

        @else
            <p class="text-gray-500">Nothing found matching your search.</p>
        @endif
    @endif
@endsection
