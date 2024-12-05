<head>
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>


<article class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105" data-id="{{ $post->id }}">
    <header class="flex items-center justify-between mb-4">
        <!-- Avatar and User Info -->
        <div class="flex items-center">
            <a href="{{ route('profile.show', ['id' => $post->id_user]) }}" class="mr-4">
                <img src="{{ asset('storage/' . ($post->user->image_url ?? 'users/images/default.png')) }}" 
                     alt="{{ $post->user->name }}" 
                     class="w-12 h-12 rounded-full shadow-md hover:opacity-80">
            </a>
            <div class="flex flex-col">
                <a href="{{ route('profile.show', ['id' => $post->id_user]) }}" class="text-lg font-semibold text-gray-800 hover:text-blue-500">
                    {{ $post->user->name }}
                </a>
                <p class="text-sm text-gray-500">{{ $post->created_at->toFormattedDateString() }}</p>
            </div>
        </div>

        <!-- Dropdown Menu -->
        @if (Auth::check() && Auth::user()->id == $post->id_user)
            <div class="relative">
                <button class="text-gray-500 hover:text-gray-700 focus:outline-none" id="dropdownMenuButton-{{ $post->id }}" aria-haspopup="true" aria-expanded="false">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 4a2 2 0 100-4 2 2 0 000 4zM10 10a2 2 0 100-4 2 2 0 000 4zM10 16a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                </button>
                <div id="dropdownMenu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                    <!-- Trigger Modal Instead of Redirect -->
                    <button 
                        onclick="showEditPostModal(
                            {{ $post->id }}, 
                            {{ json_encode($post->content) }}, 
                            {{ json_encode($post->visibility) }}
                        )"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                        Edit
                    </button>
                    <!-- Form for delete action -->
                    <form action="{{ route('post.delete', $post->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este post?');" class="block px-4 py-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 w-full text-left">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </header>


    <!-- Imagem -->
    @if ($post->image_url)
        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image" class="w-full h-auto rounded-lg object-cover">
    @endif

    <!-- Conteúdo -->
    <p class="text-lg text-gray-800 mb-1">{{ $post->content }}</p>

    <!-- LIKE -->
   <!-- Botão de Like/Unlike -->
<div class="flex items-center">
    <button onclick="toggleLike({{ $post->id }})" class="flex items-center like-button" id="like-button-{{ $post->id }}">
        @if($post->likes()->where('id_user', Auth::id())->exists())
            <i class="fas fa-heart text-2xl mr-2 text-red-500"></i>
            <span>Remove like</span>
        @else
            <i class="far fa-heart text-2xl mr-2 text-gray-500"></i>
            <span>Like</span>
        @endif
    </button>
    <!-- Contagem de Likes -->
    <span class="ml-4 text-gray-600" id="like-count-{{ $post->id }}">
        {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
    </span>
</div>


    <!-- Seção de Comentários com rolagem infinita -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800">Comments</h3>

        <div id="comments-section-{{ $post->id }}" class="mt-4 overflow-y-auto max-h-[400px] p-2">
            <!-- Comentários já carregados -->
            @foreach($post->comments as $comment)
    <div class="bg-gray-100 p-4 rounded-lg mb-2" id="comment-{{ $comment->id }}">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img src="{{ asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')) }}" class="w-8 h-8 rounded-full mr-3">
                <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
            </div>

            @if(Auth::check() && Auth::id() === $comment->user->id)
                <div class="flex space-x-2">
                    <!-- Editar -->
                    <button type="button" onclick="openInlineEdit({{ $comment->id }}, '{{ addslashes($comment->content) }}')" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>

                    <!-- Excluir -->
                    <button onclick="deleteComment({{ $comment->id }})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash-alt"></i> Excluir
                    </button>

                </div>
            @endif
        </div>

        <!-- Comentário original -->
        <p class="mt-2 text-gray-700" id="commentContent-{{ $comment->id }}">{{ $comment->content }}</p>

        <!-- Formulário de edição oculto -->
        <form action="{{ route('comments.update', $comment->id) }}" method="POST" id="editForm-{{ $comment->id }}" class="hidden mt-2">
            @csrf
            @method('PUT')
            <textarea name="content" class="w-full p-2 border rounded-lg" id="editTextArea-{{ $comment->id }}"></textarea>
            <div class="flex justify-end mt-2">
                <button type="button" onclick="cancelInlineEdit({{ $comment->id }})" class="mr-2 text-gray-600">Cancelar</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Salvar</button>
            </div>
        </form>
    </div>
@endforeach

        </div>

        <!-- Formulário para adicionar um comentário -->
        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-4">
                @csrf
                <textarea name="content" class="w-full p-3 border rounded-lg" placeholder="Escreva um comentário..." required></textarea>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg mt-2">Comentar</button>
            </form>
        @endauth
    </div>



</article>

<script>
    function openInlineEdit(commentId, content) {
    // Esconde o texto do comentário e mostra o formulário de edição
    document.getElementById(`commentContent-${commentId}`).classList.add('hidden');
    document.getElementById(`editForm-${commentId}`).classList.remove('hidden');
    document.getElementById(`editTextArea-${commentId}`).value = content; // Preenche com o conteúdo atual
}

function cancelInlineEdit(commentId) {
    // Esconde o formulário e mostra novamente o texto do comentário
    document.getElementById(`editForm-${commentId}`).classList.add('hidden');
    document.getElementById(`commentContent-${commentId}`).classList.remove('hidden');
}

function toggleLike(postId) {
    // Seleciona o botão e o contador de likes
    const likeButton = document.getElementById(`like-button-${postId}`);
    const likeCount = document.getElementById(`like-count-${postId}`);

    fetch(`/posts/${postId}/like-toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Necessário para segurança CSRF
        },
    })
    .then(response => response.json())
    .then(data => {
        // Atualiza o botão e a contagem com base na resposta
        if (data.liked) {
            likeButton.innerHTML = '<i class="fas fa-heart text-2xl mr-2 text-red-500"></i> Remove like';
        } else {
            likeButton.innerHTML = '<i class="far fa-heart text-2xl mr-2 text-gray-500"></i> Like';
        }

        likeCount.textContent = `${data.likesCount} ${data.likesCount === 1 ? 'like' : 'likes'}`;
    })
    .catch(error => console.error('Erro ao processar like:', error));
}
function deleteComment(commentId) {
    // Confirm deletion with the user
    if (!confirm('Tem certeza que deseja excluir este comentário?')) {
        return;
    }

    fetch(`/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  // CSRF protection
        },
    })
    .then(response => response.json())  // Convert response to JSON
    .then(data => {
        if (data.success) {
            // Remove the comment element from the DOM
            document.getElementById(`comment-${commentId}`).remove();
        } else {
            alert('Falha ao excluir o comentário.');
        }
    })
    .catch(error => {
        console.error('Erro ao excluir o comentário:', error);
        alert('Ocorreu um erro ao excluir o comentário. Tente novamente.');
    });
}


function saveInlineEdit(commentId) {
    const textarea = document.getElementById(`editTextArea-${commentId}`);
    const newContent = textarea.value;
    
    fetch(`/comments/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ content: newContent }) // Envia o novo conteúdo
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualiza o conteúdo do comentário sem a necessidade de refresh
            document.getElementById(`commentContent-${commentId}`).textContent = newContent;
            cancelInlineEdit(commentId);  // Fecha o formulário de edição
        } else {
            alert('Falha ao salvar a edição.');
        }
    })
    .catch(error => {
        console.error('Erro ao salvar a edição:', error);
        alert('Ocorreu um erro ao editar o comentário. Tente novamente.');
    });
}
</script>