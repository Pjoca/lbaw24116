@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 relative">
        
        <!-- Group Details -->
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 mb-8 relative">
            
            <!-- Edit Group Button (only for group owner) -->
            @if (Auth::id() === $group->owner->id)
                <button 
                    class="absolute top-4 right-4 bg-teal-500 text-white px-4 py-2 rounded shadow hover:bg-teal-600 transition"
                    onclick="openEditGroupModal({{ $group->id }})">
                    Edit Group
                </button>
            @endif

            <!-- Group Image -->
            <div class="flex justify-center mb-4">
                <img src="{{ $group->image_url ? asset('storage/' . $group->image_url) : asset('storage/images/default-group.png') }}" 
                     alt="{{ $group->name }}" 
                     class="w-24 h-24 rounded-full shadow-md">
            </div>

            <!-- Group Info -->
            <h1 class="text-2xl font-bold mb-4 text-center">{{ $group->name }}</h1>

            <div class="text-left">
                <p class="mb-2 text-center"><strong>Description:</strong> {{ $group->description }}</p>
                <p class="mb-2 text-center"><strong>Visibility:</strong> {{ ucfirst($group->visibility) }}</p>
                <p class="mb-4 text-center"><strong>Owner:</strong> {{ $group->owner->name }}</p>
            </div>

            <!-- Membership Actions -->
            @auth
                <div class="mt-4 flex justify-center">
                    @if ($isMember)
                        <button 
                            type="button" 
                            data-group-id="{{ $group->id }}"
                            id="group-action-button"
                            class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600 transition"
                            onclick="leaveGroup({{ $group->id }})">
                            Leave Group
                        </button>
                    @elseif ($hasRequestedMembership)
                        <button 
                            type="button" 
                            data-group-id="{{ $group->id }}"
                            id="group-action-button"
                            class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition7"
                            onclick="cancelMembershipRequest({{ $group->id }})">
                            Cancel Membership Request
                        </button>
                    @elseif ($hasInvite)
                        <div class="border-2 border-dashed border-gray-400 rounded-md px-5 py-3 bg-gray-50 text-center">
                            <p class="text-gray-700 mb-3 text-base font-medium">
                                You've been invited to join this group.
                            </p>
                            <div class="flex items-center justify-center space-x-4">
                                <button type="button" onclick="acceptInvite({{ $inviteId }})" 
                                        class="bg-green-500 text-white text-base px-4 py-2 rounded-md hover:bg-green-600 transition">
                                    Accept
                                </button>
                                <button type="button" onclick="rejectInvite({{ $inviteId }})" 
                                        class="bg-red-500 text-white text-base px-4 py-2 rounded-md hover:bg-red-600 transition">
                                    Reject
                                </button>
                            </div>
                        </div>
                    @else
                        <button 
                            type="button" 
                            data-group-id="{{ $group->id }}"
                            id="group-action-button"
                            class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 transition"
                            onclick="joinGroup({{ $group->id }})">
                            {{ $group->visibility === 'public' ? 'Join Group' : 'Request to Join' }}
                        </button>
                    @endif
                </div>
            @endauth

            <!-- Admin Management Buttons (Visible to Owner Only) -->
            @if (Auth::id() === $group->owner->id)
                <div class="mt-6">
                    @include('modals.join-requests-modal')
                    @include('modals.invites-modal')
                    @include('modals.edit-group-modal')

                    <div class="flex justify-center">
                        <button 
                            onclick="openModal('join-requests-modal'); loadInitialJoinRequests({{ $group->id }})" 
                            class="bg-teal-500 text-white px-4 py-2 rounded shadow hover:bg-teal-600 transition">
                            Manage Join Requests
                        </button>

                        <button 
                            onclick="openModal('invites-modal'); loadInitialInvites({{ $group->id }})" 
                            class="ml-4 bg-teal-500 text-white px-4 py-2 rounded shadow hover:bg-teal-600 transition">
                            Manage Invites
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tab Navigation Bar -->
        <div class="max-w-4xl mx-auto shadow-lg mb-6">
            <div class="flex justify-center">
                <!-- Timeline Button -->
                <button 
                    id="timeline-tab" 
                    onclick="showSection('timeline')" 
                    class="{{ $isMember || Auth::id() === $group->owner->id ? 'w-1/2' : 'w-full' }} text-center text-lg font-bold py-3 border-b-4 border-teal-500">
                    Timeline
                </button>

                @if ($isMember || Auth::id() === $group->owner->id)
                    <!-- Members Button (Only shown for members or owners) -->
                    <button 
                        id="members-tab" 
                        onclick="showSection('members'); loadInitialMembers({{ $group->id }})" 
                        class="w-1/2 text-center text-lg font-bold py-3 border-b-4 border-gray-300 hover:border-teal-500">
                        Members
                    </button>
                @endif
            </div>
        </div>

        <!-- Group Posts Timeline Section -->
        <div id="timeline-section" class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
            @if (!$isMember && Auth::id() !== $group->owner->id)
                <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                    <p class="text-yellow-600 font-semibold">You must be a member of this group to view posts.</p>
                </div>
            @else
                <div id="posts-container" class="space-y-6">
                    @if ($posts->isEmpty())
                        <p class="text-center text-gray-500">No posts have been shared in this group yet.</p>
                    @else
                        @each('partials.post', $posts, 'post')
                    @endif
                </div>
            @endif
        </div>

        @if ($isMember || Auth::id() === $group->owner->id)
            <!-- Group Members Section -->
            <div id="members-section" class="hidden max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
                <ul id="members-list" class="space-y-2"></ul>

                <!-- Loader -->
                <div id="members-loader" class="hidden text-center py-4">
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

    <!-- Include JavaScript -->
    @vite('resources/js/group.js')
@endsection
