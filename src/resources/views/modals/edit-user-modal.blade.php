<div id="edit-user-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Edit User</h2>
        <form id="edit-user-form" method="POST" action="">
            @csrf
            @method('PUT')
            <fieldset>
                <legend class="text-lg font-medium mb-2">User Information</legend>
                <div>
                    <label for="edit-name" class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" id="edit-name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label for="edit-email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" id="edit-email" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label for="edit-username" class="block text-sm font-medium">Username</label>
                    <input type="text" name="username" id="edit-username" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label for="edit-visibility" class="block text-sm font-medium">Visibility</label>
                    <select name="visibility" id="edit-visibility" class="w-full border rounded p-2" required>
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="edit-account-status" class="block text-sm font-medium">Account Status</label>
                    <select name="account_status" id="edit-account-status" class="w-full border rounded p-2" required>
                        <option value="active">Active</option>
                        <option value="offline">Offline</option>
                        <option value="deleted">Deleted</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
            </fieldset>
            <button type="submit" class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600 transition">
                Save Changes
            </button>
        </form>
        <button onclick="hideEditUserModal()" class="mt-4 text-gray-600 underline">Cancel</button>
    </div>
</div>
