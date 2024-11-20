@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Timeline</h1>
            <a href="{{ route('posts.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Criar Post
            </a>
        </div>
        <div class="max-w-3xl mx-auto space-y-8">
            @each('partials.post', $posts, 'post')
        </div>
    </div>
@endsection
