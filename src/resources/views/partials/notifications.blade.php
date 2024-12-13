<ul id="notification-list" class="max-h-64 overflow-y-auto">
    @forelse ($notifications as $notification)
        <li class="p-4 border-b hover:bg-gray-100 flex items-center gap-3" id="notification-{{ $notification->id }}">
            <!-- Notification Icon -->
            <div class="flex-shrink-0 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11c0-3.866-2.239-7-5-7S8 7.134 8 11v3.159c0 .538-.214 1.055-.595 1.436L6 17h9z" />
                </svg>
            </div>

            <!-- Notification Content -->
            <div class="flex-1">
            <a 
                href="{{ str_starts_with($notification->onclick_url ?? '', 'javascript:') ? '#' : url($notification->onclick_url ?? '') }}" 
                {!! str_starts_with($notification->onclick_url ?? '', 'javascript:') ? 'onclick="' . substr($notification->onclick_url, 11) . '"' : '' !!} 
                class="block font-semibold text-black"
            >
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

            <!-- Delete Button -->
            <button 
                class="text-red-500 hover:text-red-700 ml-auto flex items-center"
                onclick="deleteNotification({{ $notification->id }})"
                title="Delete Notification"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </li>
    @empty
        <li class="text-center p-4 text-gray-500">No new notifications</li>
    @endforelse
</ul>
