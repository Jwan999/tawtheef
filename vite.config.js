// vite.config.js
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import {defineConfig} from 'vite';

export default defineConfig({
    build: {
        sourcemap: true,
    },
    // optimizeDeps: {
    //     exclude: ['vendor/phpunit/phpunit/src/Event/**'],
    // },
    optimizeDeps: {
        // include: ['@vueuse/core'], // Example external path
        exclude: ['vendor/phpunit/phpunit/src/Event/*'], // Check this line
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],

    transpileDependencies: true,
    alias: {
        '@': '/resources/js',
    },
    // server: {
    //     allowedHosts: ['127.0.0.1:8000'],
    //     sourcemap: true, // Ensure source maps are served
    //
    // },
    server: {
        open: true,
        sourcemap: true,
        port: 5173, // Ensure your server is running on the correct port
        proxy: {
            '/api': {
                target: 'http://127.0.0.1:8000', // Laravel API endpoint
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, ''),
            },
        },
    },

    // server: {
    //     cors: true,
    //     proxy: {
    //         '/resources': 'http://127.0.0.1:8000', // Adjust this to match your Laravel development server URL
    //
    //     },
    // },

});
