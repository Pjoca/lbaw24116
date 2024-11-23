@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-3xl mx-auto space-y-8">
            <h1 class="text-2xl font-bold mb-6">Os Meus Posts</h1>

            @if($posts->isEmpty())
                <!-- Mensagem quando não houver posts -->
                <div class="bg-yellow-100 p-6 rounded-lg shadow-md text-center">
                    <p class="text-lg text-gray-800">Ainda não tens posts.</p>
                    <p class="mt-4 text-sm text-gray-600">Cria o teu primeiro post agora!</p>
                    <a href="{{ route('posts.create') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition duration-200">
                        Criar Post
                    </a>
                </div>
            @else
                <!-- Exibição dos posts -->
                @foreach($posts as $post)
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">{{ $post->created_at->format('d/m/Y H:i') }}</p>
                                <p class="font-semibold">{{ $post->user->name }}</p>
                            </div>

                            <!-- Botão de Edição -->
                            <a href="{{ route('posts.edit', $post->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fa fa-pencil-alt"></i> Editar
                            </a>
                        </div>

                        <p class="mt-2">{{ $post->content }}</p>

                        <!-- Exibe imagem se houver -->
                        @if ($post->image_url)
                            <img src="{{ asset('storage/' . $post->image_url) }}" alt="Imagem do Post" class="mt-2 max-w-full">
                        @endif

                        <div class="mt-2 text-sm text-gray-500">
                            Visibilidade: {{ ucfirst($post->visibility) }}
                        </div>

                        <!-- Formulário para eliminar um post -->
                        <form action="{{ route('posts.delete', $post->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mt-4 text-red-500 hover:text-red-700">
                                <i class="fa fa-trash"></i> Excluir
                            </button>
                        </form>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
