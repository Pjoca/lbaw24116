<article class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105" data-id="{{ $post->id }}" onclick="event.stopPropagation(); showPostViewModal({{ $post->id }});">
    <header class="flex items-center justify-between mb-4" onclick="event.stopPropagation();">
        <!-- Avatar and User Info -->
        <div class="flex items-center">
            <a href="{{ route('profile.show', ['id' => $post->id_user]) }}" class="mr-4" onclick="event.stopPropagation();">
                <img src="{{ asset('storage/' . ($post->user->image_url ?? 'users/images/default.png')) }}"
                     alt="{{ $post->user->name }}"
                     class="w-12 h-12 rounded-full shadow-md hover:opacity-80">
            </a>
            <div class="flex flex-col">
                <a href="{{ route('profile.show', ['id' => $post->id_user]) }}" class="text-lg font-semibold text-gray-800 hover:text-blue-500" onclick="event.stopPropagation();">
                    <h2>{{ $post->user->name }}</h2>
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
                    class="flex items-center text-blue-500 hover:text-blue-700 w-full text-left px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a2 2 0 112.828 2.828l-8.486 8.486a4 4 0 11-2.828-2.828l8.486-8.486z"/>
                </svg>
                Edit
                    </button>
                    <form action="{{ route('post.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="block px-4 py-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center text-red-500 hover:text-red-700 w-full text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Delete
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
        <!-- Like Button -->
        <button onclick="event.stopPropagation();toggleLike({{ $post->id }})" class="flex items-center like-button" id="like-button-{{ $post->id }}">
            @if($post->likes()->where('id_user', Auth::id())->exists())
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            @endif
        </button>

        <!-- Like Count -->
        <span class="ml-4 text-gray-600" id="like-count-{{ $post->id }}">
            {{ $post->likes->count() }}
        </span>

        <!-- Comment Button -->
        <button onclick="showPostViewModal({{ $post->id }})" class="flex items-center comment-button" id="comment-button-{{ $post->id }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-6 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10z"/>
    </svg>




        </button>
    </div>

</article>
