@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Group Details -->
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 mb-8">
        <!-- Group Info -->
        <h1 class="text-3xl font-bold mb-4">{{ $group->name }}</h1>
        <p class="mb-2"><strong>Description:</strong> {{ $group->description }}</p>
        <p class="mb-2"><strong>Visibility:</strong> {{ ucfirst($group->visibility) }}</p>
        <p class="mb-4"><strong>Owner:</strong> {{ $group->owner->name }}</p>

        <!-- Membership Actions -->
        @auth
            <div class="mt-6">
                @if ($isMember)
                    <!-- Leave Group Button -->
                    <button 
                        type="button" 
                        data-group-id="{{ $group->id }}" 
                        onclick="leaveGroup({{ $group->id }})" 
                        class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600 transition">
                        Leave Group
                    </button>
                @elseif ($hasRequestedMembership)
                    <!-- Cancel Membership Request Button -->
                    <button 
                        type="button" 
                        data-group-id="{{ $group->id }}" 
                        onclick="cancelMembershipRequest({{ $group->id }})" 
                        class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
                        Cancel Membership Request
                    </button>
                @else
                    <!-- Request to Join Button -->
                    <button 
                        type="button" 
                        data-group-id="{{ $group->id }}" 
                        onclick="joinGroup({{ $group->id }})" 
                        class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 transition">
                        Request to Join
                    </button>
                @endif
            </div>
        @endauth
    </div>

    <!-- Group Posts Timeline -->
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Timeline</h2>
        @if (!$isMember)
            <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                <p class="text-yellow-600 font-semibold">
                    You must be a member of this group to view posts.
                </p>
            </div>
        @elseif ($posts->isEmpty())
            <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg text-center">
                <p class="text-gray-500">
                    No posts have been shared in this group yet.
                </p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($posts as $post)
                    @include('partials.post', ['post' => $post])
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Include Modals -->
@include('modals.edit-post-modal')

<!-- Include JavaScript -->
@vite('resources/js/group.js')
@endsection
