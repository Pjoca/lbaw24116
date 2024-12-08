<div id="invites-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
     data-next-page-url="">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative">
        <button 
            onclick="closeModal('invites-modal')" 
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 transition text-xl">
            &times;
        </button>
        <h2 class="text-xl font-semibold mb-4">Pending Invites</h2>
        <div class="max-h-64 overflow-y-auto pr-4">
            <ul id="invites-list" class="space-y-2">
                <!-- Invites dynamically loaded here -->
            </ul>
        </div>
        <div class="mt-4">
        </div>
    </div>
</div>
