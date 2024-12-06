<div id="members-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
     data-next-page-url="{{ $hasMoreMembers ? route('groups.getMembers', ['groupId' => $group->id, 'page' => 2]) : '' }}">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative">
        <!-- Close Button -->
        <button 
            onclick="closeModal('members-modal')" 
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 transition text-xl">
            &times;
        </button>

        <!-- Modal Content -->
        <h2 class="text-xl font-semibold mb-4">Manage Members</h2>

        <!-- Scrollable List Container -->
        <div class="max-h-64 overflow-y-auto pr-4">
            <ul id="members-list" class="space-y-2">
                @foreach ($members as $member)
                    @include('partials.member-item', ['member' => $member, 'group' => $group])
                @endforeach
            </ul>
        </div>

        <!-- Load More Button -->
        @if ($hasMoreMembers)
            <button 
                id="load-more-members" 
                onclick="loadMoreMembers({{ $group->id }})" 
                class="mt-4 w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition">
                Load More
            </button>
        @endif
    </div>
</div>
