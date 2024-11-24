@if($users->isEmpty())
    <p class="text-gray-600 p-2">No results found.</p>
@else
    <ul>
        @foreach($users as $user)
            <li>
                <a href="{{ route('profile.show', $user->id) }}" class="block text-blue-600 hover:underline">
                    {{ $user->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
