<ul>
    @foreach ($friends as $friend)
        <li id="friend-{{ $friend->id }}" class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center">
                <img src="{{ asset('storage/' . ($friend->image_url ?? 'users/images/default.png')) }}" 
                    alt="{{ $friend->name }}" 
                    class="w-10 h-10 rounded-full">
                <div class="ml-4">
                    <p class="font-medium">{{ $friend->name }}</p>
                    <p class="text-sm text-gray-500">{{ $friend->email }}</p>
                </div>
            </div>

            <!-- Remove Friend Button -->
            <div class="flex gap-2">
                <button type="button" 
                        data-user-id="{{ $friend->id }}" 
                        onclick="removeFriend({{ $friend->id }})" 
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                    Remove
                </button>
            </div>
        </li>
    @endforeach
</ul>

@if ($friends->isEmpty())
    <p class="text-gray-500 text-center">No friends to display.</p>
@endif
