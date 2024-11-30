<li class="flex items-center justify-between" data-request-id="{{ $request->id }}">
    <span>{{ $request->user->name ?? 'Unknown User' }}</span>
    <div class="space-x-2">
        <button onclick="acceptMembershipRequest({{ $request->id }})" 
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
            Accept
        </button>
        <button onclick="rejectMembershipRequest({{ $request->id }})" 
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
            Reject
        </button>
    </div>
</li>
