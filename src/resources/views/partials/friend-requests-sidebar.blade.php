<div id="friend-requests-sidebar" 
     class="fixed top-0 left-0 z-50 h-full w-80 bg-white shadow-lg transform -translate-x-full transition-transform duration-300">
    <!-- Close Button -->
    <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-lg font-bold">Friend Requests</h3>
        <button id="close-sidebar" 
                onclick="toggleRequestsSidebar()" 
                class="text-teal-400 hover:text-teal-600 transition">
            &times;
        </button>
    </div>

    <!-- Friend Requests List -->
    <div class="p-4">
        @if ($friendRequests->isEmpty())
            <p class="text-gray-500">No friend requests received.</p>
        @else
            <ul class="space-y-2">
                @foreach ($friendRequests as $request)
                    @include('partials.friend-request', ['request' => $request])
                @endforeach
            </ul>
        @endif
    </div>
</div>
