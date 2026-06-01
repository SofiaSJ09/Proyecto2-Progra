<template>

    <div>

        <h2>Favoritos</h2>

        <div v-if="products.length === 0">

            No hay favoritos

        </div>

        <div
            v-for="product in products"
            :key="product.id"
        >

            <h3>{{ product.name }}</h3>

            <p>
                ₡{{ product.price }}
            </p>

            <button
                @click="removeProduct(product.id)"
            >
                Eliminar
            </button>

            <hr>

        </div>

    </div>

</template>

<script>
export default {

    data() {
        return {
            products: []
        }
    },

    created() {
        this.loadFavorites()
    },

    methods: {

        loadFavorites() {

            fetch('/api/favorites')
                .then(response => response.json())
                .then(data => {

                    this.products = Object.keys(data.favorites).map(id => ({
                        id,
                        ...data.favorites[id]
                    }))

                })

        },

        removeProduct(id) {

            fetch('/api/favorites/remove/' + id, {
                method: 'DELETE'
            })
            .then(() => {

                this.loadFavorites()

            })

        }

    }

}
</script>