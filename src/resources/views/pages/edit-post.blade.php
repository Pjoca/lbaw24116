<form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
    @csrf
    @method('PUT')
    <h2 class="text-xl font-bold mb-4">Editar Post</h2>

    <div class="mb-4">
        <label for="content" class="block text-sm font-medium">Conteúdo</label>
        <textarea id="content" name="content" rows="4" required class="w-full border-gray-300 rounded-md">{{ old('content', $post->content) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="visibility" class="block text-sm font-medium">Visibilidade</label>
        <select id="visibility" name="visibility" required class="w-full border-gray-300 rounded-md">
            <option value="public" {{ $post->visibility == 'public' ? 'selected' : '' }}>Público</option>
            <option value="private" {{ $post->visibility == 'private' ? 'selected' : '' }}>Privado</option>
            <option value="group" {{ $post->visibility == 'group' ? 'selected' : '' }}>Grupo</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="image" class="block text-sm font-medium">Imagem (opcional)</label>
        <input type="file" id="image" name="image" class="w-full border-gray-300 rounded-md">
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Salvar Alterações</button>
</form>
