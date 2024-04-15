import type { Axios, AxiosInstance } from 'axios';
import type { I18n } from 'laravel-vue-i18n';
import { inject } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import { emitter } from '../utils/eventBus';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/Home.vue'),
        },
        {
            path: '/:lang(en)',
            name: 'homeLang',
            component: () => import('../views/Home.vue'),
        },
    ]
});

router.beforeEach((to, from) => {
    const i18n = inject<I18n>('i18n');
    const axios = inject<AxiosInstance>('axios');
    if (to.params.lang) {
        if (i18n?.getActiveLanguage() !== to.params.lang as string) {
            setTimeout(() => {
                i18n?.loadLanguageAsync(to.params.lang as string);
                if (axios) {
                    axios.defaults.headers['Accept-Language'] = to.params.lang as string;
                }

                emitter.emit('languageChanged', { lang: to.params.lang as string });
            }, 500);
        }
    }
});

export default router;
