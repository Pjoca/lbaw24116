@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Utilizadores com grupos em comum (poss)-->
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Users in Common Groups with You</h2>
    <div id="same-group-users" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sameGroupUsers as $user)
            <a href="{{ route('profile.show', $user->id) }}" class="user-card bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 block">
                <div class="relative">
                    @if($currentUser->isFriend($user))
                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            Friend
                        </span>
                    @endif
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . ($user->image_url ?? 'users/images/default.png')) }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if(count($sameGroupUsers) == 0)
        <div id="no-more-same" class="bg-yellow-200 text-yellow-800 text-sm p-2 rounded mt-2 text-center">
            <strong>No users with common groups</strong>
        </div>
    @else
        <div id="no-more-same" class="hidden bg-yellow-200 text-yellow-800 text-sm p-2 rounded mt-2 text-center">
            <strong>No more users with common groups</strong>
        </div>
        <button id="load-more-same" class="bg-blue-600 text-white px-6 py-2 rounded-lg mt-6 transition-all hover:bg-blue-700">Load More</button>
    @endif

    <!-- Outros uTILIZADORES -->
    <h2 class="text-3xl font-bold mt-12 mb-6 text-gray-800">Other Users</h2>
    <div id="other-users" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($otherUsers as $user)
            <a href="{{ route('profile.show', $user->id) }}" class="user-card bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 block">
                <div class="relative">
                    @if($currentUser->isFriend($user))
                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            Friend
                        </span>
                    @endif
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . ($user->image_url ?? 'users/images/default.png')) }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if(count($otherUsers) == 0)
        <div id="no-more-other" class="bg-yellow-200 text-yellow-800 text-sm p-2 rounded mt-2 text-center">
            <strong>No users to show</strong>
        </div>
    @else
        <div id="no-more-other" class="hidden bg-yellow-200 text-yellow-800 text-sm p-2 rounded mt-2 text-center">
            <strong>No more users</strong>
        </div>
        <button id="load-more-other" class="bg-blue-600 text-white px-6 py-2 rounded-lg mt-6 transition-all hover:bg-blue-700">Load More</button>
    @endif
</div>

<!-- Script para carregar mais usuários -->
<script>
let samePage = 2;
let otherPage = 2;

function loadMore(type) {
    let url = '{{ url("matchmaking/load-more") }}';
    let container = type === 'sameGroup' ? '#same-group-users' : '#other-users';
    let page = type === 'sameGroup' ? samePage : otherPage;
    let buttonId = type === 'sameGroup' ? '#load-more-same' : '#load-more-other';
    let noMoreId = type === 'sameGroup' ? '#no-more-same' : '#no-more-other';

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
                    let friendBadge = user.is_friend 
                        ? `<span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Amigo</span>`
                        : '';

                    let userCard = `
                        <a href="/profile/${user.id}" class="user-card bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 block">
                            <div class="relative">
                                ${friendBadge}
                                <div class="flex items-center space-x-4">
                                    <img src="/storage/${user.image_url ?? 'users/images/default.png'}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">${user.name}</h3>
                                        <p class="text-sm text-gray-600">${user.email}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    `;
                    document.querySelector(container).innerHTML += userCard;
                });

                if (type === 'sameGroup') samePage++;
                else otherPage++;

                if (data.users.data.length < 1) {
                    document.querySelector(buttonId).classList.add('hidden');
                    document.querySelector(noMoreId).classList.remove('hidden');
                }
            } else {
                document.querySelector(buttonId).classList.add('hidden');
                document.querySelector(noMoreId).classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar mais usuários:', error);
            alert('Ocorreu um erro ao carregar mais usuários.');
        });
}

document.getElementById('load-more-same')?.addEventListener('click', () => loadMore('sameGroup'));
document.getElementById('load-more-other')?.addEventListener('click', () => loadMore('otherUsers'));

</script>
@endsection
