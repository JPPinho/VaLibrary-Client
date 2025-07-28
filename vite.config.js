import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command, mode }) => {
    // This correctly loads the .env file variables
    const env = process.env;

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
        server: {
            host: '0.0.0.0',
            hmr: {
                host: env.APP_URL.replace('http://', ''),
            }
        }
    };
});
