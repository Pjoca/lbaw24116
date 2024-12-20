<div id="edit-user-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Edit User</h2>

        <!-- User Edit Form -->
        <form id="edit-user-form" method="POST" action="">
            @csrf
            @method('PUT')

            <fieldset>
                <legend class="text-lg font-medium mb-2">User Information</legend>

                <!-- Name -->
                <div class="mb-4">
                    <label for="edit-name" class="block text-sm font-medium">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit-name" 
                        placeholder="Enter the user's full name" 
                        class="w-full border rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Enter the full name of the user. This will be displayed on their profile." 
                        required
                    >
                    <small class="text-gray-500">Provide the user's full name as it will be displayed on their profile.</small>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="edit-email" class="block text-sm font-medium">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="edit-email" 
                        placeholder="Enter the user's email address" 
                        class="w-full border rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Provide a valid email address for the user. It will be used for login and notifications." 
                        required
                    >
                    <small class="text-gray-500">Enter a valid email address. This will be used for login and account-related notifications.</small>
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="edit-username" class="block text-sm font-medium">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="edit-username" 
                        placeholder="Enter a unique username for the user" 
                        class="w-full border rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Enter a unique username for the user. This is used for login and mentions." 
                        required
                    >
                    <small class="text-gray-500">The username must be unique and will be used for login and mentions.</small>
                </div>

                <!-- Visibility -->
                <div class="mb-4">
                    <label for="edit-visibility" class="block text-sm font-medium">Visibility</label>
                    <select 
                        name="visibility" 
                        id="edit-visibility" 
                        class="w-full border rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Choose whether the user's profile will be visible to everyone (public) or only to certain members (private)." 
                        required
                    >
                        <option value="public" title="This user profile will be visible to everyone.">Public</option>
                        <option value="private" title="This user profile will be visible only to certain members.">Private</option>
                    </select>
                    <small class="text-gray-500">Set the visibility of the user's profile as public or private.</small>
                </div>

                <!-- Account Status -->
                <div class="mb-4">
                    <label for="edit-account-status" class="block text-sm font-medium">Account Status</label>
                    <select 
                        name="account_status" 
                        id="edit-account-status" 
                        class="w-full border rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Set the user's account status. Options include Active, Offline, Deleted, or Blocked." 
                        required
                    >
                        <option value="active" title="The user account is active and fully functional.">Active</option>
                        <option value="offline" title="The user is currently offline.">Offline</option>
                        <option value="deleted" title="The user account is marked as deleted.">Deleted</option>
                        <option value="blocked" title="The user account is blocked and cannot be accessed.">Blocked</option>
                    </select>
                    <small class="text-gray-500">Select the current status of the user's account. Status options include Active, Offline, Deleted, or Blocked.</small>
                </div>
            </fieldset>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600 transition" 
                title="Click to save the changes made to the user's information."
            >
                Save Changes
            </button>
        </form>

        <!-- Cancel Button -->
        <button 
            onclick="hideEditUserModal()" 
            class="mt-4 text-gray-600 underline" 
            title="Click to close the modal without saving any changes."
        >
            Cancel
        </button>
    </div>
</div>
