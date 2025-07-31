import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
        server: {
            host: '0.0.0.0',
            port: 5173,
            https: {
                key: '/etc/nginx/certs/nginx.key',
                cert: '/etc/nginx/certs/nginx.crt',
            },
            cors: true,
            hmr: {
                host: env.APP_URL.replace('https://', ''),
            }
        },
    };
});
