@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">

        <!-- User Profile Section -->
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 mb-8 text-center relative">
            <!-- Friend Requests Button (top-right corner) -->
            @if (Auth::id() === $user['id']) <!-- Only show for the profile owner -->
                <!-- Friend Requests Button (top-right corner of the profile info section) -->
                <button id="friend-requests-toggle" 
                        onclick="toggleRequestsSidebar()" 
                        class="absolute top-4 right-4 bg-teal-500 text-white px-4 py-2 rounded shadow hover:bg-teal-600 transition">
                    View Friend Requests
                </button>

                @include('partials.friend-requests-sidebar')
            @endif

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

            <!-- Action Buttons for Friends -->
            <div class="mt-4">
                @if (Auth::id() !== $user['id'] && Auth::check())
                    @if ($isFriend)
                        <!-- Remove Friend Button -->
                        <button type="button" data-user-id="{{ $user['id'] }}" onclick="removeFriend({{ $user['id'] }})" 
                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">
                            Remove Friend
                        </button>
                    @elseif (!$isFriend && !$hasRequestedFriendship && !$hasIncomingFriendRequest)
                        <!-- Add Friend Button -->
                        <button type="button" data-user-id="{{ $user['id'] }}" onclick="addFriend({{ $user['id'] }})" 
                                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                            Add Friend
                        </button>
                    @elseif ($hasRequestedFriendship)
                        <!-- Cancel Friend Request Button -->
                        <button type="button" data-user-id="{{ $user['id'] }}" onclick="cancelFriendRequest({{ $user['id'] }})" 
                                class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 transition">
                            Cancel Friend Request
                        </button>
                    @elseif ($hasIncomingFriendRequest)
                        <!-- Accept/Reject Friend Request -->
                        <div class="border-2 border-dashed border-gray-400 rounded-md px-5 py-3 bg-gray-50 text-center">
                            <p class="text-gray-700 mb-3 text-base font-medium">
                                This user has sent you a friend request.
                            </p>
                            <div class="flex items-center justify-center space-x-4">
                                <button type="button" onclick="acceptFriendRequest({{ $user['id'] }})" 
                                        class="bg-green-500 text-white text-base px-4 py-2 rounded-md hover:bg-green-600 transition">
                                    Accept
                                </button>
                                <button type="button" onclick="rejectFriendRequest({{ $user['id'] }})" 
                                        class="bg-red-500 text-white text-base px-4 py-2 rounded-md hover:bg-red-600 transition">
                                    Reject
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Edit Profile Button (Visible only to the profile owner) -->
            @if (Auth::id() === $user['id'])
                <div class="mt-4 flex gap-4 justify-center">
                    <button onclick="showEditProfileModal({{ json_encode($user) }})" 
                            class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600 transition">
                        Edit Profile
                    </button>

                    <!-- Delete Profile Button -->
                    <form action="{{ route('profile.delete', ['id' => $user['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your profile? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">
                            Delete Profile
                        </button>
                    </form>
                </div>
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

    <!-- Modals -->
    @include('modals.edit-post-modal')
    @include('modals.edit-profile-modal')
@endsection
