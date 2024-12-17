
<li id="{{ $type === 'received' ? 'received-request-' . $request->sender->id : 'sent-request-' . $request->receiver->id }}" class="flex items-center justify-between">
        <div>
            @if ($type === 'received')
                <p class="font-medium">{{ $request->sender->name }}</p>
                <p class="text-sm text-gray-500">{{ $request->sender->email }}</p>
            @else
                <p class="font-medium">{{ $request->receiver->name }}</p>
                <p class="text-sm text-gray-500">{{ $request->receiver->email }}</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            @if ($type === 'received')
                <!-- Accept / Reject buttons for received requests -->
                <button 
                    onclick="acceptFriendRequest({{ $request->sender->id }})" 
                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                    Accept
                </button>
                <button 
                    onclick="rejectFriendRequest({{ $request->sender->id }})" 
                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                    Reject
                </button>
            @elseif ($type === 'sent')
                <!-- Cancel button for sent requests -->
                <button 
                    onclick="cancelFriendRequest({{ $request->receiver->id }})" 
                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                    Cancel
                </button>
            @endif
        </div>
    </li>
