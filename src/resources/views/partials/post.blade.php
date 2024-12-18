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
<article class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105" data-id="{{ $post->id }}" onclick="event.stopPropagation(); showPostViewModal({{ $post->id }});">
    <header class="flex items-center justify-between mb-4" onclick="event.stopPropagation();">
        <!-- Avatar e Info do usuário -->
        <div class="flex items-center">
            <a href="{{ route('profile.show', ['id' => $post->id_user]) }}" class="mr-4" onclick="event.stopPropagation();">
                <img src="{{ asset('storage/' . ($post->user->image_url ?? 'users/images/default.png')) }}"
                     alt="{{ $post->user->name }}"
                     class="w-12 h-12 rounded-full shadow-md hover:opacity-80">
            </a>
            <div class="flex flex-col">
                <a href="{{ route('profile.show', ['id' => $post->id_user]) }}" class="text-lg font-semibold text-gray-800 hover:text-blue-500" onclick="event.stopPropagation();">
                    {{ $post->user->name }}
                </a>
                <p class="text-sm text-gray-500">{{ $post->created_at->toFormattedDateString() }}</p>
            </div>
        </div>

        @if (Auth::check() && Auth::user()->id == $post->id_user)
            <div class="relative">
                <button class="text-gray-500 hover:text-gray-700 focus:outline-none" id="dropdownMenuButton-{{ $post->id }}" aria-haspopup="true" aria-expanded="false" onclick="toggleDropdown(event, '{{ $post->id }}');">
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

    @if ($post->image_url)
        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image" class="w-full h-auto rounded-lg object-cover mb-4">
    @endif

    <p class="text-lg text-gray-800 mb-1">{{ $post->content }}</p>

    <div class="flex items-center mt-4">
        <button onclick="event.stopPropagation();toggleLike({{ $post->id }})" class="flex items-center like-button" id="like-button-{{ $post->id }}">
            @if($post->likes()->where('id_user', Auth::id())->exists())
                <i class="fas fa-heart text-2xl text-red-500"></i>
            @else
                <i class="far fa-heart text-2xl text-gray-500"></i>
            @endif
        </button>
        <span class="ml-4 text-gray-600" id="like-count-{{ $post->id }}">
            {{ $post->likes->count() }}
        </span>
        <button onclick="showPostViewModal({{ $post->id }})" class="flex items-center comment-button" id="comment-button-{{ $post->id }}">
            <i class="far fa-comment-alt text-2xl ml-8 text-gray-500"></i>
        </button>
    </div>

</article>
</body>
</html>
