import { fileURLToPath, URL } from 'node:url';
import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import basicSsl from '@vitejs/plugin-basic-ssl';
import fs from 'fs';
import path from 'path';

export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        server: {
            https: {
                key: fs.readFileSync(`bin/docker/8.3/certificate.key`),
                cert: fs.readFileSync(`bin/docker/8.3/certificate.crt`),
            },
            host: env.APP_DOMAIN,
        },
        plugins: [
            laravel({
                input: ['resources/js/front/main.ts'],
                refresh: [
                    'app/**',
                    'lang/**',
                    'resources/views/**',
                    'routes/**',
                ],
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            basicSsl(),
        ],
        resolve: {
            alias: {
                '~': fileURLToPath(new URL('.', import.meta.url)),
                '@front': fileURLToPath(new URL('./resources/js/front', import.meta.url)),
                'vue$': 'vue/dist/vue.esm-bundler.js',
                vue: path.resolve(__dirname, `./node_modules/vue`),
            },
        },
    };
});
