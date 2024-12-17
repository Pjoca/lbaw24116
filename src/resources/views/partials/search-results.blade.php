@if($results->isNotEmpty())
    <ul class="divide-y divide-gray-200">
        @foreach($results as $result)
            <li class="py-2 px-4 hover:bg-gray-100 cursor-pointer">
                @if($type === 'users')
                    <ul class="divide-y divide-gray-200">
                    @foreach($users as $user)
                        <li class="py-2 px-4 hover:bg-gray-100">
                            <a href="{{ route('profile.show', $user->id) }}" class="block text-teal-600 hover:text-teal-800 font-medium transition">
                            {{ $user->name }}
                            </a>
                        </li>
                    @endforeach
                    </ul>

                @elseif($type === 'groups')
                    <ul class="divide-y divide-gray-200">
                    @foreach($groups as $group)
                        <li class="py-2 px-4 hover:bg-gray-100">
                            <a href="{{ route('groups.show', $group->id) }}" class="block text-teal-600 hover:text-teal-800 font-medium transition">
                            {{ $group->name }}
                            </a>
                        </li>
                    @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p class="text-gray-500 px-4 py-2">No results found.</p>
@endif
