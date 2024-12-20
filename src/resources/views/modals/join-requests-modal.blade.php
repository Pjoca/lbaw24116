<div id="join-requests-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto"
     data-next-page-url="">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative max-h-screen overflow-y-auto">
        <button 
            onclick="closeModal('join-requests-modal')" 
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 transition text-xl">
            &times;
        </button>
        <h2 class="text-xl font-semibold mb-4">Manage Join Requests</h2>
        <div class="max-h-64 overflow-y-auto pr-4">
            <ul id="join-requests-list" class="space-y-2">
                <!-- Join Requests dynamically loaded here -->
            </ul>
        </div>
        <div class="mt-4">
        </div>
    </div>
</div>
