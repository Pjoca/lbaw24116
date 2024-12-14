<div id="profile-card" class="relative h-[90vh] bg-white rounded-lg shadow-lg overflow-hidden">
    @if($profile)
        <!-- Imagem Principal -->
        <div class="relative h-3/4">
            <img src="{{ $profile->profile_picture }}" alt="Profile Picture" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent p-6 text-white">
                <h2 class="text-4xl font-bold">{{ $profile->name }}</h2>
                <p class="text-lg mt-2">{{ $profile->age }} anos - {{ $profile->location }}</p>
            </div>
        </div>

        <!-- Informações e Botões -->
        <div class="h-1/4 p-6 flex flex-col justify-between">
            <p class="text-gray-600 text-lg text-center italic mb-4">
                "{{ $profile->bio ?? 'Nenhuma descrição disponível' }}"
            </p>

            <div class="flex justify-center gap-8">
                <!-- Botão Dislike -->
                <button 
                    id="dislike-button"
                    data-profile-id="{{ $profile->id }}"
                    class="bg-red-500 text-white px-8 py-4 rounded-full text-2xl shadow-md hover:bg-red-600 hover:scale-105 transition">
                    ❌
                </button>

                <!-- Botão Like -->
                <button 
                    id="like-button"
                    data-profile-id="{{ $profile->id }}"
                    class="bg-green-500 text-white px-8 py-4 rounded-full text-2xl shadow-md hover:bg-green-600 hover:scale-105 transition">
                    ❤️
                </button>
            </div>
        </div>
    @else
        <!-- Caso não haja perfil -->
        <div class="p-8 text-center">
            <p class="text-gray-600 text-xl font-semibold">Nenhum perfil disponível no momento.</p>
        </div>
    @endif
</div>
