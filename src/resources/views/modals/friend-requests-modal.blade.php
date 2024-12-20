<div id="friend-requests-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-screen">
        <button 
            onclick="closeModal('friend-requests-modal')" 
            class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition text-2xl font-bold">
            &times;
        </button>

        <h2 class="text-2xl font-semibold text-center mb-6">Friend Requests</h2>

        <h3 class="text-lg font-medium mb-2">Received Requests</h3>
        <div class="max-h-40 overflow-y-auto pr-2">
            <ul id="friend-requests-list" class="space-y-2"></ul>
        </div>
        <div class="load-more-received-container mt-2 mb-4 text-center">
        </div>

        <h3 class="text-lg font-medium mb-2">Sent Requests</h3>
        <div class="max-h-40 overflow-y-auto pr-2">
            <ul id="sent-requests-list" class="space-y-2"></ul>
        </div>
        <div class="load-more-sent-container mt-2 text-center">
        </div>
    </div>
</div>
