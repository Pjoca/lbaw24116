<li id="invite-{{ $invite->id }}" class="flex items-center justify-between">
    <div>
        <p class="font-medium">{{ $invite->user->name }}</p>
        <p class="text-sm text-gray-500">{{ $invite->user->email }}</p>
    </div>
    <div class="flex gap-2">
        <button 
            onclick="cancelInvite({{ $group->id }}, {{ $invite->id }})" 
            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
            Cancel
        </button>
    </div>
</li>
