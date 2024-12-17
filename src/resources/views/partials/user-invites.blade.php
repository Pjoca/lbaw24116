<li class="flex items-center justify-between" data-invite-id="{{ $invite->id }}">
    <span>
        <strong>{{ $invite->group->name }}</strong>
    </span>
    <div class="space-x-2">
        <button onclick="acceptGroupInvite({{ $invite->id }})" 
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
            Accept
        </button>
        <button onclick="rejectGroupInvite({{ $invite->id }})" 
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
            Reject
        </button>
    </div>
</li>
