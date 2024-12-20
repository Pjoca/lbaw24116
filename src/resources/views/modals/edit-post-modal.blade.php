<div id="edit-post-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center overflow-y-auto z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96 max-h-screen overflow-y-auto">
        <h2 class="text-xl font-semibold mb-4">Edit Post</h2>

        <!-- Post Edit Form -->
        <form id="edit-post-form" method="POST">
            @csrf
            @method('PUT')

            <fieldset>
                <legend class="text-lg font-medium text-gray-700 mb-4">Post Details</legend>

                <!-- Post Content -->
                <div class="mb-4">
                    <label for="edit-post-content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea 
                        id="edit-post-content" 
                        name="content" 
                        rows="4" 
                        required 
                        placeholder="Edit the content of your post" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        title="Edit the content of your post. Keep it clear and concise to engage readers."
                    ></textarea>
                    <small class="text-gray-500">Modify the post content to ensure your message is clear and concise.</small>
                </div>

                <!-- Visibility -->
                <div class="mb-4">
                    <label for="edit-post-visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                    <select 
                        id="edit-post-visibility" 
                        name="visibility" 
                        required 
                        size="2" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        title="Select if this post will be public (visible to everyone) or private (visible only to specific members)."
                    >
                        <option value="public" title="This post will be visible to all users.">Public</option>
                        <option value="private" title="This post will be visible only to specific members.">Private</option>
                    </select>
                    <small class="text-gray-500">Select if the post will be visible to everyone or only to specific group members.</small>
                </div>
            </fieldset>

            <!-- Update Button -->
            <button 
                type="submit" 
                id="update-post-button" 
                class="bg-teal-400 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded" 
                title="Click to save the changes made to the post."
            >
                Update Post
            </button>
        </form>

        <!-- Cancel Button -->
        <button 
            onclick="hideEditPostModal()" 
            class="mt-4 text-gray-600 underline" 
            title="Click to cancel and close the modal without saving changes."
        >
            Cancel
        </button>
    </div>
</div>
