<!-- Create Group Modal -->
<div id="create-group-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg w-11/12 sm:w-2/3 md:w-1/2 lg:w-1/3 p-6 max-h-screen overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">Create Group</h2>

        <!-- Group Creation Form -->
        <form id="create-group-form" method="POST" action="{{ route('groups.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Group Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">Group Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    required 
                    placeholder="Enter the name of your group (e.g., 'Frontend Wizards')" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none"
                    title="The group name should be unique and memorable for its members."
                >
                <small class="text-gray-500">Choose a unique name that represents the purpose of the group.</small>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold mb-2">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    required 
                    placeholder="Describe the purpose and goal of the group (e.g., 'A group to share frontend development techniques.')" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none" 
                    rows="4"
                    title="Provide a brief explanation of the group's mission, purpose, or activities."
                ></textarea>
                <small class="text-gray-500">This description helps potential members understand the group's purpose.</small>
            </div>

            <!-- Visibility -->
            <div class="mb-4">
                <label for="visibility" class="block text-sm font-bold mb-2">Visibility</label>
                <select 
                    id="visibility" 
                    name="visibility" 
                    required 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none"
                    title="Public groups are visible to everyone, while private groups are only visible to invited members."
                >
                    <option value="public" title="This group will be visible to everyone.">Public</option>
                    <option value="private" title="Only invited members will see and access this group.">Private</option>
                </select>
                <small class="text-gray-500">Choose if you want the group to be visible to everyone or only invited members.</small>
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label for="image" class="block font-bold mb-2">Group Image</label>

                <!-- Image Input -->
                <input 
                    type="file" 
                    id="image" 
                    name="image" 
                    accept="image/*" 
                    class="w-full border border-gray-300 rounded p-2" 
                    title="Upload an image to visually represent your group."
                >
                <small class="text-gray-500">Upload an image to help users recognize your group more easily.</small>

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

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
                <button 
                    type="button" 
                    onclick="closeModal('create-group-modal')" 
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow-md"
                    title="Cancel and close this form without saving any information."
                >
                    Cancel
                </button>

                <button 
                    type="submit" 
                    class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded shadow-md"
                    title="Submit the form to create the group with the information you provided."
                >
                    Create Group
                </button>
            </div>
        </form>
    </div>
</div>
