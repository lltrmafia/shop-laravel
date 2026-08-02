import { createRouter, createWebHistory} from "vue-router";

const router =  createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'Home',
            component: () => import('@/Pages/Client/Home/Index.vue')
        },
        {
            path: '/catalog',
            name: 'Catalog',
            component: () => import('@/Pages/Client/Catalog/Index.vue')
        },
        {
            path: '/catalog/:category',
            name: 'Category',
            component: () => import('@/Pages/Client/Catalog/Index.vue')
        },
        {
            path: '/products/:product',
            name: 'Product',
            component: () => import('@/Pages/Client/Product/Index.vue'),
        },
    ]
})

export default router

