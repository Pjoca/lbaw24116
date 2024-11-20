<article class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300" data-id="{{ $post->id }}">
    <!-- User Info -->
    <header class="flex items-center mb-2">
        <!-- Avatar -->
        <div class="mr-4">
            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($post->user->email))) }}" alt="User Avatar" class="w-12 h-12 rounded-full">
        </div>
        
        <!-- User Info -->
        <div class="flex flex-col justify-center mb-2">
            <p class="text-lg font-semibold text-gray-800 mb-0">{{ $post->user->name }}</p>
            <p class="text-sm text-gray-500 mt-0 mb-0">{{ $post->created_at->toFormattedDateString() }}</p>
        </div>
    </header>

    <!-- Image -->
    @if ($post->image_url)
        <div class="mb-4">
            <img src="{{ $post->image_url }}" alt="Post Image" class="w-full h-auto rounded-lg">
        </div>
    @endif

    <!-- Content Text -->
    <div>
        <p class="text-lg text-gray-800 mb-1">{{ $post->content }}</p>
    </div>

    <!-- Post Metadata -->
    <footer class="mt-4">
        @if (Auth::check() && Auth::user()->id == $post->user_id)
            <a href="{{ url('/posts/' . $post->id . '/edit') }}" class="text-blue-600 hover:underline mr-4">Edit</a>
            <a href="{{ url('/posts/' . $post->id . '/delete') }}" class="text-red-600 hover:underline">Delete</a>
        @endif
    </footer>
</article>
