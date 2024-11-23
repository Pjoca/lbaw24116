@extends('layouts.app')

@section('content')
    <h2 class="text-xl font-bold mb-4">Search Results for "{{ $query }}"</h2>

    @if($users->isEmpty())
        <p>No users found matching your search.</p>
    @else
        <ul class="divide-y divide-gray-200">
            @foreach($users as $user)
                <li class="py-4 flex items-center justify-between">
                    <span class="font-medium">{{ $user->name }}</span>
                    <a href="{{ route('profile.show', $user->id) }}" class="text-blue-600 hover:underline">
                        View Profile
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
