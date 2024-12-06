<li class="flex justify-between items-center p-2 border rounded" id="member-{{ $member->id_user }}">
    <div>
        <p class="text-sm font-medium">{{ $member->user->name ?? 'Unknown User' }}</p>
        <p class="text-xs text-gray-500">{{ $member->user->email ?? 'No Email Provided' }}</p>
    </div>
    <button 
        onclick="removeMember({{ $member->id_user }}, {{ $group->id }})" 
        class="text-red-500 hover:text-red-700 transition text-sm">
        Remove
    </button>
</li>
