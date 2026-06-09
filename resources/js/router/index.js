import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import ProductDetail from '../views/ProductDetail.vue'
import CategoryProducts from '../views/CategoryProducts.vue'
import Cart from '../views/Cart.vue'
import Favorites from '../views/Favorites.vue'
import SearchResults from '../views/SearchResults.vue'
import Billing from '../views/Billing.vue'
import AdminProducts from '../views/AdminProducts.vue'

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
    },
    {
        path: '/busqueda',
        component: SearchResults
    },
    {
    path: '/facturacion',
    component: Billing
},
{
    path: '/admin/productos',
    component: AdminProducts
}
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router