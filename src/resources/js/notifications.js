import Pusher from 'pusher-js';

document.addEventListener('DOMContentLoaded', () => {
    const notificationBell = document.getElementById('notification-bell');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationList = document.getElementById('notification-list');
    const notificationCount = document.getElementById('notification-count');

    // Show/Hide the notification dropdown
    notificationBell?.addEventListener('click', () => {
        notificationDropdown?.classList.toggle('hidden');
    });

    // Initialize Pusher with debug mode enabled
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        },
        logToConsole: true // Enable Pusher logging to console
    });

    // Bind to Pusher connection events for debugging
    pusher.connection.bind('connected', () => {
        console.log('Pusher connected');
    });

    pusher.connection.bind('disconnected', () => {
        console.log('Pusher disconnected');
    });

    pusher.connection.bind('error', (error) => {
        console.error('Pusher connection error:', error);
    });

    // Subscribe to the user's private notification channel
    const userId = document.querySelector('meta[name="user-id"]').content;
    const channel = pusher.subscribe(`private-notification.${userId}`);

    // Bind to subscription events for debugging
    channel.bind('pusher:subscription_succeeded', () => {
        console.log(`Successfully subscribed to private-notification.${userId}`);
    });

    channel.bind('pusher:subscription_error', (error) => {
        console.error('Subscription error:', error);
    });

    channel.bind('pusher:member_added', (member) => {
        console.log('New member added to the channel:', member);
    });

    channel.bind('pusher:member_removed', (member) => {
        console.log('Member removed from the channel:', member);
    });

    // Listen for notifications from the server
    channel.bind('new-notification', (data) => {
        console.log('New notification received:', data);

        const { message_content, onclick_url } = data.notification;

        // Update the notification bell count
        let currentCount = parseInt(notificationCount.textContent) || 0;
        currentCount++;
        notificationCount.textContent = currentCount;
        notificationCount.classList.remove('hidden');

        // Create a new notification list item
        const li = createNotificationItem(onclick_url, message_content, data.notification.created_at);

        // Add the new notification to the top of the list
        notificationList.prepend(li);
    });

    // Close dropdown if user clicks outside
    document.addEventListener('click', (e) => {
        if (!notificationDropdown.contains(e.target) && !notificationBell.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });
});


function createNotificationItem(onclick_url, message_content, created_at) {
    const li = document.createElement('li');
    li.classList.add('p-4', 'border-b', 'hover:bg-gray-100', 'flex', 'items-start', 'gap-3');

    li.innerHTML = `
        <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11c0-3.866-2.239-7-5-7S8 7.134 8 11v3.159c0 .538-.214 1.055-.595 1.436L6 17h9z" />
            </svg>
        </div>
        <div class="flex-1">
            <a href="${onclick_url ?? '#'}" class="block font-semibold text-black">
                ${message_content}
            </a>
            <span class="text-xs text-gray-500">
                ${formatNotificationDate(created_at)}
            </span>
        </div>
    `;

    return li;
}


function formatNotificationDate(created_at) {
    const notificationDate = new Date(created_at);
    const currentDate = new Date();

    const diffTime = Math.abs(currentDate - notificationDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) {
        return 'Today';
    } else {
        return `${diffDays} days ago`;
    }
}