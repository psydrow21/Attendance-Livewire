import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss({
            theme: {
            extend: {
            borderRadius: {
                base: '0.5rem',
            },
            colors: {
                heading: '#111827',
                'neutral-primary-soft': '#F3F4F6',
            },
            },
        },
        }),
    ],
});
