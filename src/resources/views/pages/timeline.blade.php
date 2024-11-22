@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 relative">
        <div class="flex justify-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800 text-center">Timeline Posts</h1>
        </div>

        <div class="max-w-3xl mx-auto space-y-8">
            @each('partials.post', $posts, 'post')
        </div>

        <a href="{{ route('posts.userPosts') }}" 
           class="fixed bottom-28 right-8 bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-full shadow-lg transform hover:scale-105 transition duration-300">
            ✏️
        </a>

        <!-- Botão de Criar um Post -->
        <a href="{{ route('posts.create') }}" 
           class="fixed bottom-8 right-8 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-full shadow-lg transform hover:scale-105 transition duration-300">
            <span class="text-3xl leading-none">+</span>
        </a>
    </div>
@endsection
