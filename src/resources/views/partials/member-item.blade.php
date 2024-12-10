<li class="flex justify-between items-center p-4 bg-gray-100 rounded-lg">
    <div>
        <p class="font-semibold">{{ $member->user->name }}</p>
        <p class="text-sm text-gray-600">{{ $member->user->email }}</p>
    </div>
    @if (Auth::id() === $group->id_owner)
        <button 
            onclick="removeMember({{ $group->id }}, {{ $member->user->id }})" 
            class="bg-red-500 text-white px-3 py-2 rounded shadow hover:bg-red-600 transition">
            Remove
        </button>
    @endif
</li>