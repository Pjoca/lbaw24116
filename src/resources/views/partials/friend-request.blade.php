<li class="flex items-center justify-between">
    <span>{{ $request->sender->name }}</span>
    <div class="space-x-2">
        <button onclick="acceptFriendRequest({{ $request->sender->id }})" 
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
            Accept
        </button>
        <button onclick="rejectFriendRequest({{ $request->sender->id }})" 
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
            Reject
        </button>
    </div>
</li>
