@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 relative">
        <div class="flex justify-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800 text-center">Timeline Posts</h1>
        </div>

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

        <!-- Button to Create New Post -->
        @if (Auth::check())
            <a href="{{ route('posts.create') }}" 
               class="fixed bottom-8 right-8 bg-teal-400 hover:bg-teal-600 text-white font-bold py-4 px-4 rounded-full shadow-lg transform hover:scale-105 transition duration-300">
                <span class="text-3xl leading-none">+</span>
            </a>
        @endif
    </div>

    @include('modals.edit-post-modal')
    <!-- Include Infinite Scroll Script -->
    @vite('resources/js/infinite-scroll.js')
@endsection