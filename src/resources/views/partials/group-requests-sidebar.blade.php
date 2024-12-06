<div id="sidebar" 
     class="fixed top-0 left-0 z-50 h-full w-80 bg-white shadow-lg transform -translate-x-full transition-transform duration-300">
    <!-- Close Button -->
    <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-lg font-bold">Group Management</h3>
        <button id="close-sidebar" 
                onclick="toggleRequestsSidebar()" 
                class="text-teal-400 hover:text-teal-600 transition">
            &times;
        </button>
    </div>

    <!-- Join Requests Section -->
    <div class="p-4">
        <h4 class="text-md font-semibold mb-2">Join Requests</h4>
        @if ($joinRequests->isEmpty())
            <p class="text-gray-500">No join requests received.</p>
        @endif
        <ul id="join-requests-list" class="space-y-2">
            @foreach ($joinRequests as $request)
                @include('partials.group-request', ['request' => $request])
            @endforeach
        </ul>
    </div>

    <!-- Invites Section -->
    <div class="p-4 border-t">
        <h4 class="text-md font-semibold mb-2">Pending Invites</h4>
        @if ($invites->isEmpty())
            <p class="text-gray-500">No invites sent.</p>
        @endif
        <ul id="invites-list" class="space-y-2">
            @foreach ($invites as $invite)
                @include('partials.group-invite', ['invite' => $invite])
            @endforeach
        </ul>
    </div>

    <!-- Invite Users Section -->
    <div class="p-4 border-t">
        <h4 class="text-md font-semibold mb-2">Send Invites</h4>
        <div>
            <input type="text" id="invite-username" placeholder="Enter username or email"
                   class="w-full px-3 py-2 border rounded mb-2" />
            <button onclick="sendGroupInvite({{ $group->id }})" 
                    class="w-full bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition">
                Send Invite
            </button>
        </div>
    </div>

    <!-- Manage Members Section -->
    <div class="p-4 border-t">
        <h4 class="text-md font-semibold mb-2">Manage Members</h4>
        @if ($members->isEmpty())
            <p class="text-gray-500">No members found.</p>
        @else
            <ul id="members-list" class="space-y-2">
                @foreach ($members as $member)
                    @include('partials.group-member', ['member' => $member])
                @endforeach
            </ul>
        @endif
    </div>
</div>
