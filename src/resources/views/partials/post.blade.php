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
    <div class="flex items-center">
        @if(Auth::check())
            @if($post->likes()->where('id_user', Auth::id())->exists()) 
                <!-- Botão para remover o like -->
                <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="flex items-center">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="transition-transform transform hover:scale-125 hover:text-red-500 text-red-500 flex items-center">
                        <i class="fas fa-heart text-2xl mr-2"></i> <!-- Ícone de coração -->
                        Remove like
                    </button>
                </form>
            @else
                <!-- Botão de like -->
                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="flex items-center">
                    @csrf
                    <button type="submit" class="transition-transform transform hover:scale-125 hover:text-red-500 text-gray-500 flex items-center">
                        <i class="far fa-heart text-2xl mr-2"></i> <!-- Ícone de coração -->
                        Like
                    </button>
                </form>
            @endif
        @endif
        <!-- Contagem de Likes -->
        <span class="ml-4 text-gray-600">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
    </div>

    <!-- Seção de Comentários com rolagem infinita -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800">Comments</h3>

        <div id="comments-section-{{ $post->id }}" class="mt-4 overflow-y-auto max-h-[400px] p-2">
            <!-- Comentários já carregados -->
            @foreach($post->comments as $comment)
                <div class="bg-gray-100 p-4 rounded-lg mb-2">
                    <div class="flex items-center">
                        <img src="{{ asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')) }}" class="w-8 h-8 rounded-full mr-3">
                        <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                    </div>
                    <p class="mt-2 text-gray-700">{{ $comment->content }}</p>
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
