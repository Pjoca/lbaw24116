@if($users->isEmpty())
    <p class="text-gray-500 text-sm p-2">No results found.</p>
@else
    <ul class="divide-y divide-gray-200">
        @foreach($users as $user)
            <li class="py-2 px-4 hover:bg-gray-100">
                <a href="{{ route('profile.show', $user->id) }}" class="block text-teal-600 hover:text-teal-800 font-medium transition">
                    {{ $user->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
