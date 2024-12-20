<div id="edit-group-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative">
        <h2 class="text-2xl font-bold mb-4">Edit Group</h2>

        <!-- Close Modal Button -->
        <button 
            onclick="closeModal('edit-group-modal')" 
            class="absolute top-4 right-4 text-gray-600 hover:text-gray-800"
            title="Click here to close the modal without saving any changes."
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Form -->
        <form id="edit-group-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Group Details -->
            <fieldset class="mb-6">
                <legend class="text-lg font-semibold mb-2">Group Details</legend>

                <!-- Name -->
                <div class="mb-4">
                    <label for="edit-group-name" class="block font-bold mb-2">Group Name</label>
                    <input 
                        type="text" 
                        id="edit-group-name" 
                        name="name" 
                        required 
                        placeholder="Enter a unique name for the group" 
                        class="w-full border border-gray-300 rounded p-2" 
                        title="This is the name of the group. It should be unique and recognizable."
                    >
                    <small class="text-gray-500">Choose a unique name that represents the purpose of the group.</small>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="edit-group-description" class="block font-bold mb-2">Description</label>
                    <textarea 
                        id="edit-group-description" 
                        name="description" 
                        required 
                        placeholder="Provide a brief description of the group's purpose" 
                        class="w-full border border-gray-300 rounded p-2" 
                        title="Provide a clear description to help members understand the group's purpose."
                    ></textarea>
                    <small class="text-gray-500">This description helps potential members understand the group's purpose.</small>
                </div>
            </fieldset>

            <!-- Group Settings -->
            <fieldset class="mb-6">
                <legend class="text-lg font-semibold mb-2">Group Settings</legend>

                <!-- Visibility -->
                <div class="mb-4">
                    <label for="edit-group-visibility" class="block font-bold mb-2">Visibility</label>
                    <select 
                        id="edit-group-visibility" 
                        name="visibility" 
                        size="2" 
                        required 
                        class="w-full border border-gray-300 rounded p-2" 
                        title="Select if the group will be visible to everyone (public) or only to invited members (private)."
                    >
                        <option value="public" title="This group will be visible to everyone.">Public</option>
                        <option value="private" title="Only invited members will see this group.">Private</option>
                    </select>
                    <small class="text-gray-500">Public groups are visible to everyone. Private groups are visible only to invited members.</small>
                </div>

                <!-- Group Image -->
                <div class="mb-4">
                    <label for="image" class="block font-bold mb-2">Group Image</label>

                    <!-- Image Input -->
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*" 
                        class="w-full border border-gray-300 rounded p-2" 
                        title="Upload an image to visually represent the group."
                    >
                    <small class="text-gray-500">Upload a new image to update the group's icon. This will help members recognize the group more easily.</small>

                    <!-- Image Preview Container -->
                    <div id="preview-container" class="mt-4 hidden">
                        <p class="font-semibold mb-2">Image Preview</p>
                        <img 
                            id="preview-image" 
                            src="#" 
                            alt="Preview of the uploaded group image" 
                            class="w-32 h-32 rounded-full border border-gray-200 object-cover"
                            title="Preview of the uploaded image. Ensure the image is clear and recognizable."
                        >
                    </div>
                </div>
            </fieldset>

            <!-- Submit Button -->
            <div class="text-right">
                <button 
                    type="submit" 
                    class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600" 
                    title="Click here to save the changes made to the group."
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
