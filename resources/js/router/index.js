import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import ProductDetail from '../views/ProductDetail.vue'
import CategoryProducts from '../views/CategoryProducts.vue'
import Cart from '../views/Cart.vue'
import Favorites from '../views/Favorites.vue'

const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/producto/:id',
        component: ProductDetail
    },
    {
        path: '/categoria/:id',
        component: CategoryProducts
    },
    {
        path: '/carrito',
        component: Cart
    },
    {
        path: '/favoritos',
        component: Favorites
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router