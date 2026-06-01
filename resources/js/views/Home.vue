<template>
    <div>

        <h2>Categorías</h2>

        <div v-if="categories.length">

            <div
                v-for="category in categories"
                :key="category.id"
            >
                <router-link
                    :to="'/categoria/' + category.id"
                >
                    {{ category.name }}
                </router-link>
            </div>

        </div>

        <hr>

        <h2>Catálogo de Celulares</h2>

        <div v-if="products.length === 0">
            Cargando productos...
        </div>

        <div
            v-for="product in products"
            :key="product.id"
        >

            <h3>{{ product.name }}</h3>

            <p>{{ product.description }}</p>

            <p>
                ₡{{ product.price }}
            </p>

            <router-link
                :to="'/producto/' + product.id"
            >
                Ver detalle
            </router-link>

            <hr>

        </div>

    </div>
</template>

<script>
export default {

    data() {
        return {
            products: [],
            categories: []
        }
    },

    created() {
        this.loadProducts()
        this.loadCategories()
    },

    methods: {

        loadProducts() {

            fetch('/api/products')
                .then(response => response.json())
                .then(data => {

                    this.products = data.data

                })

        },

        loadCategories() {

            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {

                    this.categories = data

                })

        }

    }

}
</script>