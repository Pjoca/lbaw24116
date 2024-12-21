<div id="post-view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center max-h-screen" data-post-id="">
    <div id="main-div-modal" class="bg-white w-full max-w-5xl h-[90vh] rounded-lg shadow-lg flex relative max-h-screen">
        <!-- Botão para Fechar -->
        <button 
            id="close-post-view-modal" 
            onclick="hidePostViewModal()" 
            class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition z-50"
        >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        </button>

        <!-- Imagem do Post -->
        <div class="flex-1 bg-gray-100 rounded-l-lg overflow-hidden">
            <img id="modal-post-image" src="1" alt="Post Image" class="w-full h-full object-contain">
        </div>

        <!-- Conteúdo do Post -->
        <div id="div-post-content" class="w-2/5 flex flex-col bg-white p-6 rounded-r-lg overflow-y-auto">
            <!-- Autor -->
            <div class="flex items-center mb-4">
                <img id="modal-user-image" src="1" alt="User Image" class="w-10 h-10 rounded-full mr-4">
                <div>
                    <p id="modal-user-name" class="text-lg font-semibold text-gray-800"></p>
                    <p id="modal-created-at" class="text-sm text-gray-500"></p>
                </div>
            </div>

            <!-- Conteúdo do Post -->
            <p id="modal-post-content" class="text-gray-800 mb-4"></p>

            <!-- Likes -->
            <div class="flex items-center mb-4">
                <button id="modal-like-button" class="flex items-center like-button mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
                <span id="modal-likes-count" class="text-gray-600">0 Likes</span>
            </div>

            <!-- Comentários -->
            <div class="flex-1 overflow-y-auto">
                <h3 class="text-lg font-semibold mb-2">Comentários</h3>
                <div id="modal-comments" class="space-y-2">
                    <!-- Comentários serão carregados aqui via JS -->
                </div>
            </div>

            <!-- Formulário de Comentário -->
            @auth
                <form id="modal-comment-form" class="mt-4">
                    @csrf
                    <input type="text" name="content" id="modal-comment-input" placeholder="Adicione um comentário..."
                        class="flex-1 border-gray-300 rounded-l-md p-2 focus:ring-teal-500 focus:border-teal-500 w-full">
                    <button type="submit" 
                        class="bg-teal-500 text-white px-4 rounded-md hover:bg-teal-600 transition mt-2">
                        Enviar
                    </button>
                </form>
            @endauth
        </div>
    </div>
</div>
