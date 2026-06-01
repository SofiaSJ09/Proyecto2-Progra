<template>
    <div>

        <h2>{{ category.name }}</h2>

        <div
            v-for="product in products"
            :key="product.id"
        >

            <h3>{{ product.name }}</h3>

            <p>{{ product.description }}</p>

            <p>₡{{ product.price }}</p>

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
            category: {},
            products: []
        }
    },

    created() {
        this.loadCategory()
    },

    methods: {

        loadCategory() {

            fetch('/api/categories/' + this.$route.params.id)
                .then(response => response.json())
                .then(data => {

                    this.category = data
                    this.products = data.products

                })

        }

    }

}
</script>