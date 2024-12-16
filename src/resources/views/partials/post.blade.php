<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
<article class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105" data-id="{{ $post->id }}">
    <header class="flex items-center justify-between mb-4">
        <!-- Avatar e Info do usuário -->
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
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 4a2 2 0 100-4 2 2 0 000 4zM10 10a2 2 0 100-4 2 2 0 000 4zM10 16a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                </button>
                <div id="dropdownMenu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                    <button
                        onclick="showEditPostModal(
                            {{ $post->id }},
                            {{ json_encode($post->content) }},
                            {{ json_encode($post->visibility) }}
                        )"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                        Edit
                    </button>
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

    <!-- Imagem do Post (se houver) -->
    @if ($post->image_url)
        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image" class="w-full h-auto rounded-lg object-cover mb-4" onclick="showPostViewModal({{ $post->id }})">
    @endif

    <!-- Conteúdo do Post -->
    <p class="text-lg text-gray-800 mb-1">{{ $post->content }}</p>

    <!-- Like -->
    <div class="flex items-center mt-4">
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
@php
    // Carrega os primeiros 3 comentários ordenados por ID (ascendente)
    $initialComments = $post->comments()->orderBy('id', 'asc')->take(3)->get();
    $totalComments = $post->comments()->count();
@endphp
    <!-- Seção de Comentários -->
<div class="mt-6">
    <h3 class="text-lg font-semibold text-gray-800">Comments</h3>

    <div id="comments-section-{{ $post->id }}" class="mt-4 overflow-y-auto max-h-[400px] p-2">
        @foreach($initialComments as $comment)
            <div class="bg-gray-100 p-4 rounded-lg mb-2" id="comment-{{ $comment->id }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')) }}" class="w-8 h-8 rounded-full mr-3">
                        <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                    </div>

                    @if(Auth::check() && Auth::id() === $comment->id_user)
                        <div class="flex space-x-2">
                            <button type="button" onclick="openInlineEdit({{ $comment->id }}, '{{ addslashes($comment->content) }}')" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button onclick="deleteComment({{ $comment->id }})" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i> Excluir
                            </button>
                        </div>
                    @endif
                </div>
                <p class="mt-2 text-gray-700" id="commentContent-{{ $comment->id }}">{{ $comment->content }}</p>
                <form id="editForm-{{ $comment->id }}" class="hidden mt-2" onsubmit="saveInlineEdit(event, {{ $comment->id }})">
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

    @if($totalComments > 3)
        <button id="load-more-comments-{{ $post->id }}" data-post-id="{{ $post->id }}" data-page="2" class="mt-2 px-4 py-2 bg-gray-200 rounded">
            Load More
        </button>
    @endif

    @auth
        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-4 comment-form">
            @csrf
            <textarea name="content" class="w-full p-3 border rounded-lg" placeholder="Escreva um comentário..." required></textarea>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg mt-2">Comentar</button>
        </form>
    @endauth

</div>

    @vite('resources/js/comments.js')
</article>
</body>
</html>
