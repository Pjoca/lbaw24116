<div id="edit-post-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Editar Post</h2>
        <form id="edit-post-form" method="POST">
            @csrf
            @method('PUT')

            <fieldset>
                <legend class="text-lg font-medium text-gray-700 mb-4">Detalhes do Post</legend>

                <!-- Post Content -->
                <div class="mb-4">
                    <label for="edit-post-content" class="block text-sm font-medium text-gray-700">Conteúdo</label>
                    <textarea id="edit-post-content" name="content" rows="4" required
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Visibility -->
                <div class="mb-4">
                    <label for="edit-post-visibility" class="block text-sm font-medium text-gray-700">Visibilidade</label>
                    <select id="edit-post-visibility" name="visibility" required size="2"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="public">Público</option>
                        <option value="private">Privado</option>
                    </select>
                </div>
            </fieldset>

            <!-- Update Button -->
            <button type="submit" id="update-post-button" class="bg-teal-400 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded">
                Atualizar Post
            </button>
        </form>
        <!-- Close Modal -->
        <button onclick="hideEditPostModal()" class="mt-4 text-gray-600 underline">Cancelar</button>
    </div>
</div>
