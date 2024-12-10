<div id="invites-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
     data-next-page-url="">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative">
        <!-- Close Modal Button -->
        <button 
            onclick="closeModal('invites-modal')" 
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 transition text-xl">
            &times;
        </button>

        <!-- Modal Title -->
        <h2 class="text-xl font-semibold mb-4">Pending Invites</h2>

        <!-- Invite Form -->
        <form id="invite-form" onsubmit="event.preventDefault(); sendGroupInvite({{ $group->id }});" class="mb-4">
            <label for="invite-username" class="block text-sm font-medium text-gray-700 mb-2">Invite by Username or Email</label>
            <div class="flex">
                <input 
                    type="text" 
                    id="invite-username" 
                    name="usernameOrEmail" 
                    placeholder="Enter username or email" 
                    class="w-full border border-gray-300 rounded-l-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-r-lg transition"
                >
                    Invite
                </button>
            </div>
        </form>

        <!-- Feedback Message -->
        <p id="invite-feedback" class="mt-2 text-sm text-red-500 hidden"></p>

        <!-- Pending Invites List -->
        <div class="max-h-64 overflow-y-auto pr-4">
            <ul id="invites-list" class="space-y-2">
                <!-- Invites dynamically loaded here -->
            </ul>
        </div>

    </div>
</div>
