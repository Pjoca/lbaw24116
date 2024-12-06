<li class="flex items-center justify-between" data-invite-id="{{ $invite->id }}">
    <span>{{ $invite->user->name ?? 'Unknown User' }}</span>
    <div class="space-x-2">
        <button onclick="cancelInvite({{ $invite->id }})" 
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
            Cancel Invite
        </button>
    </div>
</li>
