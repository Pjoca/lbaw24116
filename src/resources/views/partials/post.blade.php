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
                    <!-- Use the named route for Edit -->
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                    <!-- Form for delete action -->
                    <form action="{{ route('post.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="block px-4 py-2">
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

    <!-- Image -->
    @if ($post->image_url)
        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image" class="w-full h-auto rounded-lg object-cover">
    @endif

    <!-- Content -->
    <p class="text-lg text-gray-800 mb-1">{{ $post->content }}</p>
</article>
