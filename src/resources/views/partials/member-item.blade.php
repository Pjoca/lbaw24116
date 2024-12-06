<li id="member-{{ $member->user->id }}" class="flex items-center justify-between">
    <div>
        <p class="font-medium">{{ $member->user->name }}</p>
        <p class="text-sm text-gray-500">{{ $member->user->email }}</p>
    </div>
    <button 
        onclick="removeMember({{ $group->id }}, {{ $member->user->id }})" 
        class="text-red-500 hover:text-red-700 transition">
        Remove
    </button>
</li>
