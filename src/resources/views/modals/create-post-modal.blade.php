<div id="create-post-modal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-2xl p-6 relative">

        <!-- Close Button -->
        <button 
            onclick="closeModal('create-post-modal')" 
            class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition text-2xl font-bold">
            &times;
        </button>

        <!-- Modal Title -->
        <h2 class="text-2xl font-bold text-center mb-6">Create New Post</h2>

        <!-- Create Post Form -->
        <form id="create-post-form" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">

            @csrf

            <!-- Post Content -->
            <fieldset class="mb-4">
                <legend class="block text-lg font-medium text-gray-700 mb-2">Post Content</legend>
                <label for="text-content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="text-content" name="content" rows="4" required
                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </fieldset>

            <!-- Visibility -->
            <fieldset class="mb-4" id="visibility-container">
                <legend class="block text-lg font-medium text-gray-700 mb-2">Visibility</legend>
                <label for="visibility" class="block text-sm font-medium text-gray-700">Who can see this post?</label>
                <select id="visibility" name="visibility" required size="2"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </fieldset>

            <input type="hidden" id="id_group" name="id_group" value="">

            <!-- Image Upload -->
            <fieldset class="mb-4">
                <legend class="block text-lg font-medium text-gray-700 mb-2">Image Upload</legend>
                <label for="image" class="block text-sm font-medium text-gray-700">Image (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </fieldset>

            <!-- Preview -->
            <fieldset id="preview-container" class="mb-4 hidden">
                <legend class="block text-lg font-medium text-gray-700 mb-2">Image Preview</legend>
                <label class="block text-sm font-medium text-gray-700">Preview:</label>
                <img id="preview-image" src="#" alt="Image Preview" class="w-full h-auto rounded-md shadow-md mt-2">
            </fieldset>

            <!-- Submit Button -->
            <button type="submit" 
                    class="bg-teal-400 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded">
                Create Post
            </button>
        </form>
    </div>
</div>
