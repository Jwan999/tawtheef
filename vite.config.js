import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                compilerOptions: {
                    // isCustomElement: (tag) => tag === 'lottie-loader',
                    isCustomElement: tag => tag.includes('dotlottie-player')

                }
            }
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@vuelidate': path.resolve(__dirname, 'node_modules/@vuelidate'),
        },
    },
    build: {
        sourcemap: true,
    },
    optimizeDeps: {
        include: ['vue-simple-range-slider', '@vuelidate/core', '@vuelidate/validators'],
        exclude: ['vendor/phpunit/phpunit/src/Event/*'],
    },
    server: {
        port: 5173,
        open: true,
        watch: {
            ignored: ['**/vendor/**', '**/node_modules/**', '**/storage/**'],
        },
        proxy: {
            '/api': {
                target: 'http://127.0.0.1:8000',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, ''),
            },
        },
    },
});
