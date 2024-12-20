<div id="friend-list-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto"
     data-next-page-url="">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative max-h-screen overflow-y-auto">

        <!-- Close Button -->
        <button 
            onclick="closeModal('friend-list-modal')" 
            class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition text-2xl font-bold">
            &times;
        </button>

        <h2 class="text-2xl font-semibold mb-6">Friends</h2>

        <!-- Friends List -->
        <div class="max-h-64 overflow-y-auto">
            <ul id="friend-list" class="space-y-4">
                <!-- Friends will be dynamically loaded here -->
            </ul>
        </div>

        <!-- Load More Button Container -->
        <div class="mt-4">
        </div>
    </div>
</div>
