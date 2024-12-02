<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<!-- Exibir post -->
<article class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105" data-id="{{ $post->id }}">
    <header class="flex items-center justify-between mb-4">
        <!-- Avatar e informações do usuário -->
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

    <!-- Imagem -->
    @if ($post->image_url)
        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image" class="w-full h-auto rounded-lg object-cover">
    @endif

    <!-- Conteúdo -->
    <p class="text-lg text-gray-800 mb-1">{{ $post->content }}</p>

    <!-- Curtir/Descurtir -->
    <div class="flex items-center">
        @if(Auth::check())
            @if($post->likes()->where('id_user', Auth::id())->exists()) 
                <!-- Botão de "Descurtir" -->
                <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="flex items-center">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="transition-transform transform hover:scale-125 hover:text-red-500 text-red-500 flex items-center">
                        <i class="fas fa-heart text-2xl mr-2"></i> <!-- Ícone de coração -->
                        Descurtir
                    </button>
                </form>
            @else
                <!-- Botão de "Curtir" -->
                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="flex items-center">
                    @csrf
                    <button type="submit" class="transition-transform transform hover:scale-125 hover:text-red-500 text-gray-500 flex items-center">
                        <i class="far fa-heart text-2xl mr-2"></i> <!-- Ícone de coração -->
                        Curtir
                    </button>
                </form>
            @endif
        @endif
        <!-- Contagem de Likes -->
        <span class="ml-4 text-gray-600">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
    </div>

    <!-- Seção de Comentários -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800">Comentários</h3>
        
        <div id="comments-section-{{ $post->id }}" class="mt-4">
            <!-- Exibe os 3 primeiros comentários -->
            @foreach($post->comments->take(3) as $comment)
                <div class="bg-gray-100 p-4 rounded-lg mb-2">
                    <div class="flex items-center">
                        <img src="{{ asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')) }}" class="w-8 h-8 rounded-full mr-3">
                        <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                    </div>
                    <p class="mt-2 text-gray-700">{{ $comment->content }}</p>
                </div>
            @endforeach
        </div>

        <!-- Carregar mais comentários -->
        @if ($post->comments->count() > 3)
            <button onclick="loadMoreComments({{ $post->id }}, 3)" class="text-blue-500 hover:underline mt-2">Carregar mais comentários</button>
        @endif

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
    function loadMoreComments(postId, offset) {
        fetch(`/posts/${postId}/comments/load/${offset}`)
            .then(response => response.json())
            .then(data => {
                const commentsSection = document.getElementById(`comments-section-${postId}`);
                data.comments.forEach(comment => {
                    const commentDiv = document.createElement('div');
                    commentDiv.classList.add('bg-gray-100', 'p-4', 'rounded-lg', 'mb-2');
                    commentDiv.innerHTML = `
                        <div class="flex items-center">
                            <img src="/storage/${comment.user_image_url}" class="w-8 h-8 rounded-full mr-3">
                            <span class="font-semibold text-gray-800">${comment.user_name}</span>
                        </div>
                        <p class="mt-2 text-gray-700">${comment.content}</p>
                    `;
                    commentsSection.appendChild(commentDiv);
                });
            });
    }
</script>

