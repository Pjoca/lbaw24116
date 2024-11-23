@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <!-- User Profile Section -->
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 mb-8 text-center">
            <!-- User Profile Image -->
            <div class="mb-4">
                <img src="{{ asset('storage/' . $user['image_url']) }}" 
                     alt="{{ $user['name'] }}" 
                     class="w-24 h-24 mx-auto rounded-full shadow-md">
            </div>

            <!-- User Info -->
            <h2 class="text-2xl font-bold mb-4">{{ $user['name'] }}</h2>
            <p><strong>Username:</strong> {{ $user['username'] }}</p>
            <p><strong>Last Login:</strong> {{ $user['last_login'] }}</p>
            @if (isset($user['email']))
                <p><strong>Email:</strong> {{ $user['email'] }}</p>
            @endif
        </div>

        <!-- User Posts Section -->
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 text-center">
            <h3 class="text-xl font-bold mb-4">Posts by {{ $user['name'] }}</h3>
            @if ($posts->isEmpty())
                <p>No posts available.</p>
                @if (Auth::id() === $user['id']) <!-- Only show for the profile owner -->
                    <div class="mt-4">
                        <a href="{{ route('posts.create') }}" 
                           class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                            Add Your First Post
                        </a>
                    </div>
                @endif
            @else
                <div class="space-y-4">
                    @foreach ($posts as $post)
                        @include('partials.post', ['post' => $post])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
