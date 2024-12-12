@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 relative">
    @if($profile)
        <!-- Card de perfil -->
        <div id="profile-card" class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md sm:max-w-lg lg:max-w-xl relative transform transition-all duration-500">
            <!-- Imagem do cabeçalho -->
            <div class="relative h-72 w-full overflow-hidden rounded-t-lg">
                <img src="{{ $profile->profile_picture }}" alt="Header Image" class="w-full h-full object-cover">
                <!-- Nome do perfil -->
                <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent p-4 text-white">
                    <h2 class="text-2xl font-bold">{{ $profile->name }}</h2>
                    <p class="text-sm">{{ $profile->age }} anos - {{ $profile->location }}</p>
                </div>
            </div>
            <!-- Detalhes do perfil -->
            <div class="p-4">
                <p class="text-gray-600 text-center">"Descrição do usuário ou detalhes adicionais."</p>
            </div>
            <!-- Botões de interação -->
            <div class="flex justify-around mt-4">
                <form action="{{ route('matchmaking.interact') }}" method="POST" id="dislike-form">
                    @csrf
                    <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                    <input type="hidden" name="action" value="dislike">
                    <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-full shadow-lg text-lg hover:bg-red-600 transform hover:scale-105 transition">
                        ❌ Dislike
                    </button>
                </form>
                <form action="{{ route('matchmaking.interact') }}" method="POST" id="like-form">
                    @csrf
                    <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                    <input type="hidden" name="action" value="like">
                    <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-full shadow-lg text-lg hover:bg-green-600 transform hover:scale-105 transition">
                        ❤️ Like
                    </button>
                </form>
            </div>
        </div>

        <!-- Efeito de match -->
        <div id="match-overlay" class="hidden absolute top-0 left-0 w-full h-full bg-black/70 flex items-center justify-center z-50">
            <div class="text-center">
                <h1 class="text-white text-6xl font-bold mb-4">🎉 Match! 🎉</h1>
                <p class="text-white text-lg">Vocês deram like um no outro!</p>
            </div>
        </div>
    @else
        <p class="text-gray-500">Nenhum perfil disponível no momento.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const likeForm = document.getElementById('like-form');
        const matchOverlay = document.getElementById('match-overlay');
        const profileCard = document.getElementById('profile-card');

        likeForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Previne o envio imediato do formulário
            // Mostra o efeito de match
            matchOverlay.classList.remove('hidden');
            matchOverlay.classList.add('flex');
            profileCard.classList.add('opacity-0');

            // Redireciona após alguns segundos
            setTimeout(() => {
                likeForm.submit(); // Envia o formulário após a animação
            }, 2000);
        });
    });
</script>
@endsection
