@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6">Editar Post</h2>
            
            <form method="POST" action="{{ route('posts.update', $post->id) }}">
                @csrf
                @method('PUT') <!-- Necessário para o método PUT -->

                <!-- Conteúdo do Post -->
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Conteúdo</label>
                    <textarea id="content" name="content" rows="4" required
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $post->content }}</textarea>
                </div>

                <!-- Visibilidade -->
                <div class="mb-4">
                    <label for="visibility" class="block text-sm font-medium text-gray-700">Visibilidade</label>
                    <select id="visibility" name="visibility" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="public" {{ $post->visibility === 'public' ? 'selected' : '' }}>Público</option>
                        <option value="private" {{ $post->visibility === 'private' ? 'selected' : '' }}>Privado</option>
                        <option value="group" {{ $post->visibility === 'group' ? 'selected' : '' }}>Grupo</option>
                    </select>
                </div>

                <!-- Botão de atualização -->
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Atualizar Post
                </button>
            </form>
        </div>
    </div>
@endsection
