@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 relative">
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
                        <button 
                            type="button" 
                            id="group-action-button"
                            data-group-id="{{ $group->id }}" 
                            class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600 transition"
                            onclick="leaveGroup({{ $group->id }})">
                            Leave Group
                        </button>
                    @elseif ($hasRequestedMembership)
                        <button 
                            type="button" 
                            id="group-action-button"
                            data-group-id="{{ $group->id }}" 
                            class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition"
                            onclick="cancelMembershipRequest({{ $group->id }})">
                            Cancel Membership Request
                        </button>
                    @else
                        <button 
                            type="button" 
                            id="group-action-button"
                            data-group-id="{{ $group->id }}" 
                            class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 transition"
                            onclick="joinGroup({{ $group->id }})">
                            Request to Join
                        </button>
                    @endif
                </div>
            @endauth

            <!-- Manage Modals Buttons (Visible to Owner Only) -->
            @if (Auth::id() === $group->owner->id)
                @include('modals.join-requests-modal', ['joinRequests' => $joinRequests, 'hasMoreJoinRequests' => $hasMoreJoinRequests])
                @include('modals.members-modal', ['members' => $members, 'hasMoreMembers' => $hasMoreMembers])
                @include('modals.invites-modal', ['invites' => $invites, 'hasMoreInvites' => $hasMoreInvites])

                <button 
                    type="button" 
                    onclick="openModal('join-requests-modal')" 
                    class="mt-6 bg-teal-500 text-white px-4 py-2 rounded shadow hover:bg-teal-600 transition">
                    Manage Join Requests
                </button>

                <button 
                    type="button" 
                    onclick="openModal('invites-modal')" 
                    class="mt-6 bg-teal-500 text-white px-4 py-2 rounded shadow hover:bg-teal-600 transition">
                    Manage Invites
                </button>

                <button 
                    onclick="openModal('members-modal')" 
                    class="mt-6 bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600 transition">
                    Manage Members
                </button>
            @endif
        </div>

        <!-- Group Posts Timeline -->
        @if (!$isMember)
            <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-center max-w-4xl mx-auto">
                <p class="text-yellow-600 font-semibold">You must be a member of this group to view posts.</p>
            </div>
        @else
            <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Timeline</h2>
                <div id="posts-container" class="space-y-6">
                    @if ($posts->isEmpty())
                        <p class="text-center text-gray-500">No posts have been shared in this group yet.</p>
                    @else
                        @each('partials.post', $posts, 'post')
                    @endif
                </div>
                <div id="loader" class="hidden text-center py-4">
                    <div class="flex justify-center items-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-teal-500"></div>
                        <span class="ml-4 text-gray-600 font-medium">Loading...</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Button to Create New Post -->
        @if (Auth::check() && $isMember)
            <a href="{{ route('posts.create') }}" 
               class="fixed bottom-8 right-8 bg-teal-400 hover:bg-teal-600 text-white font-bold py-4 px-4 rounded-full shadow-lg transform hover:scale-105 transition duration-300">
                <span class="text-3xl leading-none">+</span>
            </a>
        @endif
    </div>

    <!-- Include Modals -->
    @include('modals.edit-post-modal')

    <!-- Include JavaScript -->
    @vite('resources/js/group.js')
@endsection
