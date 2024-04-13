import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/Home.vue'),
        },
    ]
    // .concat(authenticationRoutes as Array<any>)
    // .concat(companyRoutes as Array<any>)
});

export default router;
