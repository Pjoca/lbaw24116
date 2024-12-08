<div id="invites-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
     data-next-page-url="{{ $hasMoreInvites ? route('groups.getInvites', ['groupId' => $group->id, 'page' => 2]) : '' }}">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative">
        <!-- Close Button -->
        <button 
            onclick="closeModal('invites-modal')" 
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 transition text-xl">
            &times;
        </button>

        <!-- Modal Content -->
        <h2 class="text-xl font-semibold mb-4">Pending Invites</h2>

        <!-- Scrollable List Container -->
        <div class="max-h-64 overflow-y-auto pr-4">
            <ul id="invites-list" class="space-y-2">
                @foreach ($invites as $invite)
                    @include('partials.invite-item', ['invite' => $invite])
                @endforeach
            </ul>
        </div>

        <!-- Load More Button -->
        @if ($hasMoreInvites)
            <button 
                id="load-more-invites" 
                onclick="loadMoreInvites({{ $group->id }})" 
                class="mt-4 w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition">
                Load More
            </button>
        @endif

        <!-- Send Invites Section -->
        <div class="p-4 border-t mt-4">
            <h4 class="text-md font-semibold mb-2">Send Invites</h4>
            <div>
                <input type="text" id="invite-username" placeholder="Enter username or email"
                       class="w-full px-3 py-2 border rounded mb-2" />
                <button onclick="sendGroupInvite({{ $group->id }})" 
                        class="w-full bg-teal-400 text-white px-3 py-2 rounded hover:bg-teal-600 transition">
                    Send Invite
                </button>
            </div>
        </div>
    </div>
</div>
