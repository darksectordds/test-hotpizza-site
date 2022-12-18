import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes = [
    {
        path: '',
        name: 'index',
        component: () => import('./components/pages/PageIndexComponent'),
    },
    {
        path: 'cart',
        name: 'cart',
        component: () => import('./components/pages/cart/PageCartProductComponent'),
    },
    {
        path: 'cart/submit',
        name: 'cart-submit',
        component: () => import('./components/pages/cart/PageCartFormSubmitComponent'),
    },
];

const router = new VueRouter({
    routes, // сокращённая запись для `routes: routes`
});

Vue.router = router;

export default router;