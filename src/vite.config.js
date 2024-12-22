import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/infinite-scroll.js', 'resources/js/group.js', 'resources/js/infinite-group.js', 'resources/js/notifications.js', 'resources/js/friends.js', 'resources/js/comments.js', 'resources/js/messages.js'],
            refresh: true,
        }),
    ],
});
