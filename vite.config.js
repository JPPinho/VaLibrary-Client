import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // Import the new Tailwind Vite plugin

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(), // Add the Tailwind plugin here
    ],
    server: {
        host: true, // This is still needed for Docker
        hmr: {
            host: 'localhost',
        },
    },
});
