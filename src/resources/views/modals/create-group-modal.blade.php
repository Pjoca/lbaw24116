<!-- Create Group Modal -->
<div id="create-group-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg w-11/12 sm:w-2/3 md:w-1/2 lg:w-1/3 p-6">
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
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none"
                    placeholder="Enter group name"
                >
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold mb-2">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    required 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none" 
                    placeholder="Enter group description"
                    rows="4"
                ></textarea>
            </div>

            <!-- Visibility -->
            <div class="mb-4">
                <label for="visibility" class="block text-sm font-bold mb-2">Visibility</label>
                <select 
                    id="visibility" 
                    name="visibility" 
                    required 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none"
                >
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label for="image" class="block font-bold mb-2">Group Image</label>

                <!-- Image Input -->
                <input type="file" id="image" name="image" accept="image/*" 
                       class="w-full border border-gray-300 rounded p-2">
                
                <!-- Image Preview Container -->
                <div id="preview-container" class="mt-4 hidden">
                    <p class="font-semibold mb-2">Image Preview</p>
                    <img id="preview-image" src="#" 
                         class="w-32 h-32 rounded-full border border-gray-200 object-cover">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
                <button 
                    type="button" 
                    onclick="closeModal('create-group-modal')" 
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow-md"
                >
                    Cancel
                </button>

                <button 
                    type="submit" 
                    class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded shadow-md"
                >
                    Create Group
                </button>
            </div>
        </form>
    </div>
</div>