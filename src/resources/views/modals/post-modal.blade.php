<div id="post-view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center">
    <div class="bg-white w-full max-w-5xl h-[90vh] rounded-lg shadow-lg flex relative">
        <!-- Botão para Fechar -->
        <button 
            id="close-post-view-modal" 
            class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <!-- Imagem do Post -->
        <div class="flex-1 bg-gray-100 rounded-l-lg overflow-hidden">
            <img id="modal-post-image" src="" alt="Post Image" class="w-full h-full object-contain">
        </div>

        <!-- Conteúdo do Post -->
        <div class="w-2/5 flex flex-col bg-white p-6 rounded-r-lg overflow-y-auto">
            <!-- Autor -->
            <div class="flex items-center mb-4">
                <img id="modal-user-image" src="" alt="User Image" class="w-10 h-10 rounded-full mr-4">
                <div>
                    <p id="modal-user-name" class="text-lg font-semibold text-gray-800"></p>
                    <p id="modal-created-at" class="text-sm text-gray-500"></p>
                </div>
            </div>

            <!-- Conteúdo do Post -->
            <p id="modal-post-content" class="text-gray-800 mb-4"></p>

            <!-- Likes -->
            <div class="flex items-center mb-4">
                <i class="fas fa-heart text-red-500 mr-2"></i>
                <span id="modal-likes-count" class="text-gray-600"></span>
            </div>

            <!-- Comentários -->
            <div class="flex-1 overflow-y-auto">
                <h3 class="text-lg font-semibold mb-2">Comentários</h3>
                <div id="modal-comments" class="space-y-2">
                    <!-- Comentários serão carregados aqui -->
                </div>
            </div>

            <!-- Formulário de Comentário -->
            @auth
                <form id="modal-comment-form" class="mt-4 flex">
                    @csrf
                    <input type="text" id="modal-comment-input" placeholder="Adicione um comentário..."
                        class="flex-1 border-gray-300 rounded-l-md p-2 focus:ring-teal-500 focus:border-teal-500">
                    <button type="submit" 
                        class="bg-teal-500 text-white px-4 rounded-r-md hover:bg-teal-600 transition">
                        Enviar
                    </button>
                </form>
            @endauth
        </div>
    </div>
</div>
