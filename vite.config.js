import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // This controls the URL prefix Vite writes into manifest/asset URLs
    base: '/themes/shop/drivespot/build/',

    // This controls where the build output is written on disk
    build: {
        outDir: 'public/themes/shop/drivespot/build',
        emptyOutDir: true,
        manifest: 'manifest.json',
    },

    plugins: [
        laravel({
            input: [
                'src/Resources/assets/css/app.css',
                'src/Resources/assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
