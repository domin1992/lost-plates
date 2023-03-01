import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        server: {
            host: env.APP_DOMAIN,
        },
        plugins: [
            laravel({
                input: [
                    'resources/scss/front/master.scss',
                    'resources/js/front/master.js',
                ],
                refresh: [
                    'app/**',
                    'resources/lang/**',
                    'resources/views/**',
                    'routes/**',
                ],
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    }
                }
            }),
        ],
        resolve: {
            alias: [
                // SCSS import other stylesheets from node_modules
                {
                    find: /^~(.*)$/,
                    replacement: '$1',
                },
            ],
        },
    };
});