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
    </header>

    <!-- Image -->
    @if ($post->image_url)
        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image" class="w-full h-auto rounded-lg object-cover">
    @endif

    <!-- Content -->
    <p class="text-lg text-gray-800 mb-1">{{ $post->content }}</p>

    <!-- LIKE -->
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
        <span class="ml-4 text-gray-600" id="like-count-{{ $post->id }}">
            {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
        </span>
    </div>

    <!-- Comments Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800">Comments</h3>

        <div id="comments-section-{{ $post->id }}" class="mt-4 overflow-y-auto max-h-[400px] p-2">
            @foreach($post->comments as $comment)
                <div class="bg-gray-100 p-4 rounded-lg mb-2" id="comment-{{ $comment->id }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')) }}" class="w-8 h-8 rounded-full mr-3">
                            <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                        </div>
                        @if(Auth::check() && Auth::id() === $comment->user->id)
                            <div class="flex space-x-2">
                                <button type="button" onclick="openInlineEdit({{ $comment->id }}, '{{ addslashes($comment->content) }}')" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteComment({{ $comment->id }})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        @endif
                    </div>
                    <p class="mt-2 text-gray-700" id="commentContent-{{ $comment->id }}">{{ $comment->content }}</p>
                </div>
            @endforeach
        </div>

        <!-- Add Comment Form -->
        @auth
            <form id="comment-form-{{ $post->id }}" action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-4">
                @csrf
                <textarea name="content" class="w-full p-3 border rounded-lg" placeholder="Write a comment..." required></textarea>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg mt-2">Comment</button>
            </form>
        @endauth
    </div>
</article>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const commentForm = document.getElementById("comment-form-{{ $post->id }}");

        if (commentForm) {
            commentForm.addEventListener("submit", function (e) {
                e.preventDefault(); // Prevent page reload on submit

                const formData = new FormData(commentForm);
                const commentsSection = document.getElementById("comments-section-{{ $post->id }}");

                fetch(commentForm.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Failed to post the comment.");
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Add the new comment to the top of the comments section
                            const newComment = document.createElement("div");
                            newComment.className = "bg-gray-100 p-4 rounded-lg mb-2";
                            newComment.id = `comment-${data.comment.id}`;
                            newComment.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="${data.comment.user_image}" class="w-8 h-8 rounded-full mr-3">
                                        <span class="font-semibold text-gray-800">${data.comment.user_name}</span>
                                    </div>
                                    ${
                                        data.is_owner
                                            ? `
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="openInlineEdit(${data.comment.id}, '${data.comment.content.replace(/'/g, "\\'")}')" class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button onclick="deleteComment(${data.comment.id})" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    `
                                            : ""
                                    }
                                </div>
                                <p class="mt-2 text-gray-700" id="commentContent-${data.comment.id}">${data.comment.content}</p>
                            `;

                            commentsSection.appendChild(newComment);

                            // Clear the comment input
                            commentForm.querySelector("textarea[name='content']").value = "";

                            // Scroll to the bottom of the comments section
                            commentsSection.scrollTop = commentsSection.scrollHeight;
                        } else {
                            alert("Failed to post the comment.");
                        }
                    })
                    .catch(error => {
                        console.error("Error posting the comment:", error);
                        alert("An error occurred while posting the comment.");
                    });
            });
        }
    });

    function updateComment(commentId) {
    const content = document.getElementById(`edit-content-${commentId}`).value;

    fetch(`/comments/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  // CSRF protection
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())  // Convert response to JSON
    .then(data => {
        if (data.success) {
            // Atualiza o conteúdo do comentário no DOM
            document.getElementById(`comment-content-${commentId}`).innerText = data.comment.content;
            alert('Comentário atualizado com sucesso!');
        } else {
            alert('Falha ao atualizar o comentário.');
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar o comentário:', error);
        alert('Ocorreu um erro ao atualizar o comentário. Tente novamente.');
    });
}

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
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  
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


function saveInlineEdit(event, commentId) {
    event.preventDefault(); // Evita o envio padrão do formulário

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
            // Atualiza o conteúdo do comentário no DOM
            document.getElementById(`commentContent-${commentId}`).textContent = data.content;
            
            // Fecha o formulário de edição e exibe o comentário atualizado
            cancelInlineEdit(commentId);
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
