<ul class="max-h-64 overflow-y-auto">
    @forelse ($notifications as $notification)
        <li class="p-4 border-b hover:bg-gray-100 flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11c0-3.866-2.239-7-5-7S8 7.134 8 11v3.159c0 .538-.214 1.055-.595 1.436L6 17h9z" />
                </svg>
            </div>
            <div class="flex-1">
                <a href="{{ $notification->onclick_url ?? '#' }}" class="block font-semibold text-black">
                    {{ $notification->message_content }}
                </a>
                <span class="text-xs text-gray-500">
                    @if ($notification->created_at->diffInDays(now()) === 0)
                        Today
                    @else
                        {{ $notification->created_at->diffInDays(now()) }} days ago
                    @endif
                </span>
            </div>
        </li>
    @empty
        <li class="text-center p-4 text-gray-500">No new notifications</li>
    @endforelse
</ul>
