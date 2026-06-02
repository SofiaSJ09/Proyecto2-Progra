<template>
    <section class="page-container">

        <div class="main-panel">

            <h2 class="page-title">Favoritos</h2>

            <div v-if="products.length === 0" class="empty-message">
                <h3>No hay favoritos</h3>
                <p>Aún no has agregado productos a favoritos.</p>

                <router-link to="/" class="btn-primary">
                    Ver productos
                </router-link>
            </div>

            <div v-else class="favorites-grid">

                <div
                    v-for="product in products"
                    :key="product.id"
                    class="favorite-card"
                >

                 
                     <button
    class="favorite-delete"
    @click="removeProduct(product.id)"
    title="Eliminar producto"
>
    <img :src="'/images/delete.png'" alt="Eliminar">
</button>

                    <router-link
                        :to="'/producto/' + product.id"
                        class="favorite-image-link"
                    >
                        <img
                            v-if="product.image"
                            :src="product.image"
                            :alt="product.name"
                            class="favorite-image"
                        >

                        <div v-else class="favorite-placeholder">
                            Imagen
                        </div>
                    </router-link>

                    <p class="product-brand">
                        {{ product.brand ?? 'Celular' }}
                    </p>

                    <h3 class="favorite-name">
                        {{ product.name }}
                    </h3>

                    <div class="favorite-specs">
                        <span v-if="product.ram">{{ product.ram }}</span>
                        <span v-if="product.storage">{{ product.storage }}</span>
                    </div>

                    <div class="favorite-bottom">
                        <p class="favorite-price">
                            ₡{{ product.price }}
                        </p>

                        <router-link
                            :to="'/producto/' + product.id"
                            class="favorite-detail-link"
                        >
                            Ver detalle
                        </router-link>
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

            if (!confirm('¿Desea eliminar este producto de favoritos?')) {
                return
            }

            fetch('/api/favorites/remove/' + id, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {

                alert(data.message ?? 'Producto eliminado de favoritos')
                this.loadFavorites()

            })

        }

    }

}
</script>