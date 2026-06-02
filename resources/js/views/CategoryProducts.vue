<template>
    <section class="page-container">

        <div class="main-panel">

            <h2 class="page-title">
                {{ category.name }}
            </h2>

            <div class="catalog-layout">

                <aside class="filters-panel">
                    <h3>Filtros</h3>

                    <div class="filter-card">
                        <h4>Marca</h4>

                        <label>
                            <input type="checkbox">
                            Xiaomi
                        </label>

                        <label>
                            <input type="checkbox">
                            Samsung
                        </label>

                        <label>
                            <input type="checkbox">
                            Apple
                        </label>
                    </div>

                    <div class="filter-card">
                        <h4>Gama</h4>

                        <label>
                            <input type="checkbox">
                            Alta gama
                        </label>

                        <label>
                            <input type="checkbox">
                            Gama media
                        </label>

                        <label>
                            <input type="checkbox">
                            Gama baja
                        </label>
                    </div>

                    <div class="filter-card">
                        <h4>Sistema Operativo</h4>

                        <label>
                            <input type="checkbox">
                            iOS 17
                        </label>

                        <label>
                            <input type="checkbox">
                            Android 12
                        </label>

                        <label>
                            <input type="checkbox">
                            Android 13
                        </label>
                    </div>

                    <div class="filter-card">
                        <h4>Precio</h4>

                        <p class="price-label">Mínimo</p>
                        <input type="range">

                        <p class="price-label">Máximo</p>
                        <input type="range">
                    </div>
                </aside>

                <div class="catalog-products">

                    <div v-if="products.length === 0" class="empty-message">
                        No hay productos en esta categoría.
                    </div>

                    <div v-else class="catalog-grid">

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

            </div>

        </div>

    </section>
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

    watch: {
        '$route.params.id'() {
            this.loadCategory()
        }
    },

    methods: {

        loadCategory() {

            fetch('/api/categories/' + this.$route.params.id)
                .then(response => response.json())
                .then(data => {

                    this.category = data
                    this.products = data.products ?? []

                })

        }

    }

}
</script>