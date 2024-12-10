@extends('layouts.app')

@section('content')

    <div class="container mx-auto py-8 relative">
        <!-- Seção para criar novo post -->
        @if (Auth::check())
            <div class="bg-teal-100 border-l-4 border-teal-500 text-teal-700 p-6 rounded-lg shadow-md mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">TIMELINE POSTS!</h2>
                        <p class="text-sm mt-2">Share your thoughts with our community.</p>
                    </div>
                    <a href="{{ route('posts.create') }}" 
                       class="bg-teal-400 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create a new post
                        </span>
                    </a>
                </div>
            </div>
        @endif

        <!-- Posts Container -->
        <div id="posts-container" class="max-w-3xl mx-auto space-y-8">
            @each('partials.post', $posts, 'post')
        </div>

        <!-- Loader -->
        <div id="loader" class="hidden text-center py-4">
            <div class="flex justify-center items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-teal-500"></div>
                <span class="ml-4 text-gray-600 font-medium">Loading...</span>
            </div>
        </div>
    </div>

    @include('modals.edit-post-modal')
    <!-- Include Infinite Scroll Script -->
    @vite('resources/js/infinite-scroll.js')
@endsection
