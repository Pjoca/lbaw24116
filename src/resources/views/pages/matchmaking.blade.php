@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <!-- Container do Card -->
    <div 
        id="profile-container" 
        class="relative w-full max-w-4xl h-[90vh] bg-white shadow-2xl rounded-lg overflow-hidden transform transition-all duration-500"
    >
        @if($profile)
            @include('partials.profile-cards-matchmaking', ['profile' => $profile])
        @else
            <div class="text-center flex items-center justify-center h-[90vh]">
                <p class="text-gray-500 text-2xl">Não há mais perfis disponíveis no momento!</p>
            </div>
        @endif
    </div>

    <!-- Overlay de Match -->
    <div id="match-overlay" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50">
        <h1 class="text-white text-6xl font-extrabold animate-bounce">🎉 It's a Match! 🎉</h1>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const profileContainer = document.getElementById('profile-container');
    const matchOverlay = document.getElementById('match-overlay');

    // Função para carregar o próximo perfil
    function loadNextProfile() {
        profileContainer.classList.add('opacity-0'); // Animação de saída

        fetch("{{ route('matchmaking.index') }}", {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.text())
        .then(html => {
            setTimeout(() => {
                profileContainer.innerHTML = html; // Substitui o conteúdo
                profileContainer.classList.remove('opacity-0'); // Remove animação de saída
                attachEventListeners(); // Reanexa os eventos
            }, 300);
        })
        .catch(error => console.error('Erro ao carregar perfil:', error));
    }

    // Função para processar as interações de like/dislike
    function handleInteraction(action, profileId) {
        fetch("{{ route('matchmaking.interact') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ profile_id: profileId, action: action })
        })
        .then(() => {
            if (action === "like") {
                matchOverlay.classList.remove('hidden');
                setTimeout(() => {
                    matchOverlay.classList.add('hidden');
                    loadNextProfile();
                }, 2000);
            } else {
                loadNextProfile();
            }
        })
        .catch(error => console.error('Erro ao processar interação:', error));
    }

    // Função para anexar eventos aos botões
    function attachEventListeners() {
        const likeButton = document.getElementById('like-button');
        const dislikeButton = document.getElementById('dislike-button');
        if (!likeButton || !dislikeButton) return;

        const profileId = likeButton.dataset.profileId;

        likeButton.addEventListener('click', () => handleInteraction('like', profileId));
        dislikeButton.addEventListener('click', () => handleInteraction('dislike', profileId));
    }

    attachEventListeners(); // Inicia os eventos ao carregar a página
});
</script>
@endsection
