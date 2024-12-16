@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Exibindo usuários com grupos em comum -->
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Utilizador em grupos em comum contigo</h2>
    <div class="same-group-users grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sameGroupUsers as $user)
            <div class="user-card bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center space-x-4">
                    <!-- Imagem do usuário -->
                    <img src="{{ $user->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Exibindo outros usuários -->
    <h2 class="text-2xl font-semibold mt-12 mb-4 text-gray-800">Outros Utilizadores</h2>
    <div class="other-users grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($otherUsers as $user)
            <div class="user-card bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center space-x-4">
                    <!-- Imagem do usuário -->
                    <img src="{{ $user->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
