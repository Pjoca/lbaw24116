@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6">Criar Novo Post</h2>
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf

                <!-- Conteúdo do Post -->
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Conteúdo</label>
                    <textarea id="content" name="content" rows="4" required
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Visibilidade -->
                <div class="mb-4">
                    <label for="visibility" class="block text-sm font-medium text-gray-700">Visibilidade</label>
                    <select id="visibility" name="visibility" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="public">Público</option>
                        <option value="private">Privado</option>
                        <option value="group">Grupo</option>
                    </select>
                </div>

                <!-- ID do Grupo (Opcional) -->
                <div class="mb-4">
                    <label for="id_group" class="block text-sm font-medium text-gray-700">Grupo (opcional)</label>
                    <input type="number" id="id_group" name="id_group"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Upload de Imagem -->
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Imagem (opcional)</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Botão para Submeter -->
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Criar Post
                </button>
            </form>
        </div>
    </div>
@endsection
