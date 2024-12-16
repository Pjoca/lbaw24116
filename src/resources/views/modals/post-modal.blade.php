<div id="post-view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center">
        <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg relative">
            <!-- Botão para Fechar -->
            <button 
                id="close-post-view-modal" 
                class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <!-- Conteúdo do Modal -->
            <div id="post-view-content" class="max-h-[80vh] overflow-y-auto">
                <!-- Este conteúdo será preenchido dinamicamente -->
            </div>
        </div>
    </div>