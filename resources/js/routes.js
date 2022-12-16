import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes = [
    {
        path: '',
        name: 'index',
        component: () => import('./components/pages/PageIndexComponent'),
    },
];

const router = new VueRouter({
    routes, // сокращённая запись для `routes: routes`
});

Vue.router = router;

export default router;