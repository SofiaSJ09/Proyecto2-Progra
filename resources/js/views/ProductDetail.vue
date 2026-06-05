<template>
    <section class="page-container">

        <div class="main-panel">

            <h2 class="page-title">Detalles del producto</h2>

            <div v-if="!product" class="empty-message">
                Cargando producto...
            </div>

            <div v-else class="product-detail-layout">

                <div class="detail-product-card">

                    <img
                        v-if="product.image"
                        :src="product.image"
                        :alt="product.name"
                        class="detail-product-image"
                    >

                    <div v-else class="detail-image-placeholder">
                        Imagen
                    </div>

                    <p class="product-brand">
                        {{ product.brand }}
                    </p>

                    <h3 class="detail-product-name">
                        {{ product.name }}
                    </h3>

                    <p class="detail-product-price">
                        ₡{{ product.price }}
                    </p>

                </div>

                <div class="detail-info-card">

                    <h3>Descripción</h3>

                    <p class="detail-description">
                        {{ product.description }}
                    </p>

                    <h3>Características técnicas</h3>

                    <div class="spec-list">

                        <div class="spec-row">
                            <span>Marca</span>
                            <strong>{{ product.brand }}</strong>
                        </div>

                        <div class="spec-row">
                            <span>Memoria RAM</span>
                            <strong>{{ product.ram }}</strong>
                        </div>

                        <div class="spec-row">
                            <span>Almacenamiento</span>
                            <strong>{{ product.storage }}</strong>
                        </div>

                        <div class="spec-row">
                            <span>Sistema Operativo</span>
                            <strong>{{ product.operating_system }}</strong>
                        </div>

                        <div class="spec-row">
                            <span>Categoría</span>
                            <strong>{{ product.category?.name }}</strong>
                        </div>

                    </div>

                    <div class="quantity-box">
                        <label>Cantidad</label>

                        <div class="quantity-control">
                            <button @click="decreaseQuantity">-</button>
                            <span>{{ quantity }}</span>
                            <button @click="increaseQuantity">+</button>
                        </div>
                    </div>

                    <div class="detail-actions">
                        <button @click="addToCart" class="btn-primary">
                            Agregar al carrito
                        </button>

                        <button @click="addToFavorites" class="btn-secondary">
                            Favoritos
                        </button>
                    </div>

                    <router-link to="/" class="back-link">
                        Volver al catálogo
                    </router-link>

                </div>

            </div>

        </div>

        <AppModal
            :show="modal.show"
            :type="modal.type"
            :title="modal.title"
            :message="modal.message"
            :confirm-text="modal.confirmText"
            :show-cancel="modal.showCancel"
            @confirm="closeModal"
            @cancel="closeModal"
        />

    </section>
</template>

<script>
import AppModal from '../components/AppModal.vue'

export default {

    components: {
        AppModal
    },

    data() {
        return {
            product: null,
            quantity: 1,

            modal: {
                show: false,
                type: 'success',
                title: '',
                message: '',
                confirmText: 'Aceptar',
                showCancel: false
            }
        }
    },

    created() {
        this.loadProduct()
    },

    methods: {

        loadProduct() {

            fetch('/api/products/' + this.$route.params.id)
                .then(response => response.json())
                .then(data => {

                    this.product = data

                })

        },

        increaseQuantity() {
            this.quantity++
        },

        decreaseQuantity() {
            if (this.quantity > 1) {
                this.quantity--
            }
        },

        addToCart() {

            fetch('/api/cart/add/' + this.product.id, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {

                    this.modal = {
                        show: true,
                        type: 'success',
                        title: 'Producto agregado',
                        message: data.message ?? 'El producto fue agregado correctamente al carrito.',
                        confirmText: 'Aceptar',
                        showCancel: false
                    }

                })
                .catch(error => {

                    console.log('ERROR:', error)

                    this.modal = {
                        show: true,
                        type: 'danger',
                        title: 'Error',
                        message: 'No se pudo agregar el producto al carrito.',
                        confirmText: 'Aceptar',
                        showCancel: false
                    }

                })

        },

        addToFavorites() {

            fetch('/api/favorites/add/' + this.product.id, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {

                    this.modal = {
                        show: true,
                        type: 'success',
                        title: 'Producto agregado',
                        message: data.message ?? 'El producto fue agregado correctamente a favoritos.',
                        confirmText: 'Aceptar',
                        showCancel: false
                    }

                })
                .catch(error => {

                    console.log('ERROR:', error)

                    this.modal = {
                        show: true,
                        type: 'danger',
                        title: 'Error',
                        message: 'No se pudo agregar el producto a favoritos.',
                        confirmText: 'Aceptar',
                        showCancel: false
                    }

                })

        },

        closeModal() {
            this.modal.show = false
        }

    }

}
</script>