<template>
    <section class="page-container">


        <div class="main-panel">

            <h2 class="page-title">
                Resultados de búsqueda
            </h2>

            <p class="search-result-text">
                Buscando: <strong>{{ searchQuery }}</strong>
            </p>

            <div v-if="loading" class="empty-message">
                Cargando resultados...
            </div>

            <div v-else-if="products.length === 0" class="empty-message">
                <h3>No se encontraron productos</h3>
                <p>No hay celulares relacionados con "{{ searchQuery }}".</p>

                <router-link to="/" class="btn-primary">
                    Volver al catálogo
                </router-link>
            </div>

            <div v-else class="products-grid">

                <div
                    v-for="product in products"
                    :key="product.id"
                    class="product-card"
                >

                    <img
                        v-if="product.image"
                        :src="product.image"
                        :alt="product.name"
                        class="product-image"
                    >

                    <div v-else class="image-placeholder">
                        Imagen
                    </div>

                    <p class="product-brand">
                        {{ product.brand }}
                    </p>

                    <h3 class="product-name">
                        {{ product.name }}
                    </h3>

                    <div class="product-specs">
                        <span>{{ product.ram }}</span>
                        <span>{{ product.storage }}</span>
                    </div>

                    <div class="product-specs">
                        <span>₡{{ product.price }}</span>
                        <span>{{ product.operating_system }}</span>
                    </div>

                    <router-link
                        :to="'/producto/' + product.id"
                        class="btn-secondary product-button"
                    >
                        Ver producto
                    </router-link>

                </div>

            </div>

        </div>

    </section>
</template>

<script>
export default {

    data() {
        return {
            products: [],
            loading: true
        }
    },

    computed: {
        searchQuery() {
            return this.$route.query.query ?? ''
        }
    },

    created() {
        this.searchProducts()
    },

    watch: {
        '$route.query.query'() {
            this.searchProducts()
        }
    },

    methods: {

        searchProducts() {

            const query = this.searchQuery.trim()

            if (query === '') {
                this.products = []
                this.loading = false
                return
            }

            this.loading = true

            fetch('/api/products/search?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {

                    this.products = data.data ?? data
                    this.loading = false

                })
                .catch(error => {

                    console.log('Error en búsqueda:', error)
                    this.products = []
                    this.loading = false

                })

        }

    }

}
</script>