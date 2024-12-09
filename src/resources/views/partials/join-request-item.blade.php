<li id="join-request-{{ $request->id }}" class="flex items-center justify-between">
    <div>
        <p class="font-medium">{{ $request->user->name }}</p>
        <p class="text-sm text-gray-500">{{ $request->user->email }}</p>
    </div>
    <div class="flex gap-2">
        <button 
            onclick="acceptMembershipRequest({{ $request->id }})" 
            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
            Accept
        </button>
        <button 
            onclick="rejectMembershipRequest({{ $request->id }})" 
            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
            Reject
        </button>
    </div>
</li>
