<form action="{{ route('comments.update', $comment->id) }}" method="POST">
    @csrf
    @method('PUT')

    <textarea name="content" class="w-full p-3 border rounded-lg" required>{{ $comment->content }}</textarea>

    <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg mt-2">Salvar alterações</button>
</form>
