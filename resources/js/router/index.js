import { createRouter, createWebHistory } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

const routes = [
    {
        path: '/',
        component: () => import('@/pages/Home.vue'),
        name: 'home',
    },
    {
        path: '/login',
        component: () => import('@/pages/Login.vue'),
        name: 'login',
        meta: { guest: true },
    },
    {
        path: '/register',
        component: () => import('@/pages/Register.vue'),
        name: 'register',
        meta: { guest: true },
    },
    {
        path: '/products',
        component: () => import('@/pages/Products.vue'),
        name: 'products',
    },
    {
        path: '/products/:id',
        component: () => import('@/pages/ProductDetail.vue'),
        name: 'product-detail',
    },
    {
        path: '/cart',
        component: () => import('@/pages/Cart.vue'),
        name: 'cart',
    },
    {
        path: '/orders',
        component: () => import('@/pages/Orders.vue'),
        name: 'orders',
        meta: { requiresAuth: true },
    },
    {
        path: '/admin',
        component: () => import('@/pages/admin/Dashboard.vue'),
        name: 'admin-dashboard',
        meta: { requiresAuth: true, requiresAdmin: true },
    },
    {
        path: '/admin/products',
        component: () => import('@/pages/admin/Products.vue'),
        name: 'admin-products',
        meta: { requiresAuth: true, requiresAdmin: true },
    },
    {
        path: '/admin/orders',
        component: () => import('@/pages/admin/Orders.vue'),
        name: 'admin-orders',
        meta: { requiresAuth: true, requiresAdmin: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const { isAuthenticated, isAdmin } = useAuth();

    if (to.meta.requiresAuth && !isAuthenticated.value) {
        next({ name: 'login' });
    } else if (to.meta.requiresAdmin && !isAdmin.value) {
        next({ name: 'home' });
    } else if (to.meta.guest && isAuthenticated.value) {
        next({ name: 'home' });
    } else {
        next();
    }
});

export default router;
