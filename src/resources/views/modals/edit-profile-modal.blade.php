<div id="edit-profile-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative max-h-screen overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">Edit Profile</h2>

        <!-- Close Modal Button -->
        <button 
            onclick="hideEditProfileModal()" 
            class="absolute top-4 right-4 text-gray-600 hover:text-gray-800" 
            title="Click to close this window without saving changes."
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Display Success Message -->
        <div id="edit-profile-success" class="hidden bg-green-100 text-green-700 p-3 rounded mb-4">
            Profile updated successfully!
        </div>

        <!-- Display Error Messages -->
        <div id="edit-profile-errors" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4"></div>

        <!-- Form -->
        <form id="edit-profile-form" method="POST" enctype="multipart/form-data" 
              action="{{ route('profile.update', ['id' => data_get($user, 'id')]) }}">
            @csrf
            @method('PUT')

            <fieldset>
                <legend class="text-lg font-bold mb-4">Personal Information</legend>

                <!-- Name -->
                <div class="mb-4">
                    <label for="edit-name" class="block font-bold mb-2">Name:</label>
                    <input 
                        type="text" 
                        id="edit-name" 
                        name="name" 
                        value="{{ data_get($user, 'name') }}" 
                        placeholder="Enter your full name" 
                        class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Enter your full name." 
                        required
                    >
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="edit-email" class="block font-bold mb-2">Email:</label>
                    <input 
                        type="email" 
                        id="edit-email" 
                        name="email" 
                        value="{{ data_get($user, 'email') }}" 
                        placeholder="Enter your email address" 
                        class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Enter a valid email address." 
                        required
                    >
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="edit-username" class="block font-bold mb-2">Username:</label>
                    <input 
                        type="text" 
                        id="edit-username" 
                        name="username" 
                        value="{{ data_get($user, 'username') }}" 
                        placeholder="Enter a unique username" 
                        class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Enter a unique username that will identify your profile." 
                        required
                    >
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-lg font-bold mb-4">Security</legend>

                <!-- Password -->
                <div class="mb-4">
                    <label for="edit-password" class="block font-bold mb-2">New Password (Optional):</label>
                    <input 
                        type="password" 
                        id="edit-password" 
                        name="password" 
                        placeholder="Enter a new password" 
                        class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Leave this field empty if you do not want to change your password."
                    >
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="edit-password-confirmation" class="block font-bold mb-2">Confirm Password:</label>
                    <input 
                        type="password" 
                        id="edit-password-confirmation" 
                        name="password_confirmation" 
                        placeholder="Confirm your new password" 
                        class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Re-enter the new password to confirm it matches." 
                    >
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-lg font-bold mb-4">Profile Settings</legend>

                <!-- Profile Image -->
                <div class="mb-4">
                    <label for="edit-image" class="block font-bold mb-2">Profile Image:</label>
                    <input 
                        type="file" 
                        id="edit-image" 
                        name="image" 
                        class="w-full" 
                        title="Upload a new profile picture to update your current image." 
                    >
                    @if (data_get($user, 'image_url'))
                        <img 
                            src="{{ asset('storage/' . data_get($user, 'image_url')) }}" 
                            alt="Current Profile Image" 
                            class="mt-2 w-24 h-24 rounded-full" 
                            title="This is your current profile image."
                        >
                    @endif
                </div>

                <!-- Visibility -->
                <div class="mb-4">
                    <label for="edit-visibility" class="block font-bold mb-2">Visibility:</label>
                    <select 
                        id="edit-visibility" 
                        name="visibility" 
                        class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" 
                        title="Set your profile visibility as public or private." 
                        required
                    >
                        <option value="public" {{ data_get($user, 'visibility') === 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ data_get($user, 'visibility') === 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>
            </fieldset>

            <!-- Submit Button -->
            <div class="text-right">
                <button 
                    type="submit" 
                    class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600 transition" 
                    title="Click to save all changes to your profile."
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
