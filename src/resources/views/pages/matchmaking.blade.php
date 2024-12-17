@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Usuários com grupos em comum -->
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Utilizadores em grupos em comum contigo</h2>
    <div id="same-group-users" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sameGroupUsers as $user)
            <!-- Card do usuário agora está dentro de um link -->
            <a href="{{ route('profile.show', $user->id) }}" class="user-card bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 block">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/' . ($user->image_url ?? 'users/images/default.png')) }}" class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $user->name }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <button id="load-more-same" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Carregar Mais</button>
    <!-- Mensagem quando não houver mais usuários (grupos em comum) -->
    <div id="no-more-same" class="hidden bg-yellow-200 text-yellow-800 text-sm p-2 rounded mt-2 text-center">
        <strong>Não há mais utilizadores com grupos em comum</strong>
    </div>

    <!-- Outros usuários -->
    <h2 class="text-2xl font-semibold mt-12 mb-4 text-gray-800">Outros Utilizadores</h2>
    <div id="other-users" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($otherUsers as $user)
            <!-- Card do usuário agora está dentro de um link -->
            <a href="{{ route('profile.show', $user->id) }}" class="user-card bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 block">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/' . ($user->image_url ?? 'users/images/default.png')) }}" class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $user->name }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <button id="load-more-other" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Carregar Mais</button>
    <!-- Mensagem quando não houver mais usuários (outros usuários) -->
    <div id="no-more-other" class="hidden bg-yellow-200 text-yellow-800 text-sm p-2 rounded mt-2 text-center">
        <strong>Não há mais utilizadores</strong>
    </div>
</div>

<!-- Script para carregar mais usuários -->
<script>
let samePage = 2;
let otherPage = 2;

function loadMore(type) {
    let url = '{{ url("matchmaking/load-more") }}';
    let container = type === 'sameGroup' ? '#same-group-users' : '#other-users';
    let page = type === 'sameGroup' ? samePage : otherPage;

    fetch(`${url}?type=${type}&page=${page}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar dados.');
            }
            return response.json();
        })
        .then(data => {
            if (data.users && data.users.data.length > 0) {
                data.users.data.forEach(user => {
                    let userCard = `
                        <a href="/profile/${user.id}" class="user-card bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 block">
                            <div class="flex items-center space-x-4">
                                <img src="/storage/${user.image_url ?? 'users/images/default.png'}" class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">${user.name}</h3>
                                    <p class="text-sm text-gray-600">${user.email}</p>
                                </div>
                            </div>
                        </a>
                    `;
                    document.querySelector(container).innerHTML += userCard;
                });
                if (type === 'sameGroup') samePage++;
                else otherPage++;
            } else {
                // Exibe a mensagem de "Não há mais usuários"
                if (type === 'sameGroup') {
                    document.getElementById('no-more-same').classList.remove('hidden');
                } else {
                    document.getElementById('no-more-other').classList.remove('hidden');
                }
            }
        })
        .catch(error => {
            console.error('Erro ao carregar mais usuários:', error);
            alert('Ocorreu um erro ao carregar mais usuários.');
        });
}

document.getElementById('load-more-same').addEventListener('click', () => loadMore('sameGroup'));
document.getElementById('load-more-other').addEventListener('click', () => loadMore('otherUsers'));
</script>
@endsection
