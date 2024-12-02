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

    <!-- Botão de Like/Unlike e Contagem de Likes -->
    <!-- Botão de Like/Unlike e Contagem de Likes -->
    <div class="flex items-center">
    @if(Auth::check())
        @if($post->likes()->where('id_user', Auth::id())->exists()) 
            <!-- Botão de "Descurtir" -->
            <form action="{{ route('posts.unlike', $post->id) }}" method="POST" class="flex items-center" id="unlike-form-{{ $post->id }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="transition-transform transform hover:scale-125 hover:text-red-500 text-red-500 flex items-center">
                    <i class="fas fa-heart text-2xl mr-2"></i> Descurtir
                </button>
            </form>
        @else
            <!-- Botão de "Curtir" -->
            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="flex items-center" id="like-form-{{ $post->id }}">
                @csrf
                <button type="submit" class="transition-transform transform hover:scale-125 hover:text-red-500 text-gray-500 flex items-center">
                    <i class="far fa-heart text-2xl mr-2"></i> Curtir
                </button>
            </form>
        @endif
    @endif
    <!-- Contagem de Likes -->
    <span id="like-count-{{ $post->id }}" class="ml-4 text-gray-600">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
</div>

</article>
