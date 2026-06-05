<template>
    <section class="page-container">

        <div class="main-panel">

            <h2 class="page-title">Carrito de compra</h2>

            <div v-if="products.length === 0" class="empty-message">
                <h3>Carrito vacío</h3>
                <p>No has agregado productos al carrito.</p>

                <router-link to="/" class="btn-primary">
                    Seguir comprando
                </router-link>
            </div>

            <div v-else class="cart-layout">

                <div class="cart-products">

                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="cart-item"
                    >

                        <div class="cart-image-box">
                            <img
                                v-if="product.image"
                                :src="product.image"
                                :alt="product.name"
                                class="cart-image"
                            >

                            <span v-else>Imagen</span>
                        </div>

                        <div class="cart-info">
                            <h3>{{ product.name }}</h3>

                            <p>
                                {{ product.brand ?? 'Celular' }}
                                <span v-if="product.ram"> • {{ product.ram }}</span>
                                <span v-if="product.storage"> • {{ product.storage }}</span>
                            </p>

                            <div class="cart-quantity">
                                <span>Cantidad:</span>
                                <strong>{{ product.quantity }}</strong>
                            </div>
                        </div>

                        <div class="cart-price">
                            <span>Subtotal</span>
                            <strong>₡{{ productSubtotal(product) }}</strong>
                        </div>

                        <button
                            class="cart-delete"
                            @click="openDeleteModal(product.id)"
                            title="Eliminar producto"
                        >
                            <img :src="'/images/delete.png'" alt="Eliminar">
                        </button>

                    </div>

                </div>

                <aside class="cart-summary">
                    <h3>Resumen de compra</h3>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong>₡{{ subtotal }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Impuestos (13%)</span>
                        <strong>₡{{ taxes }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Envío</span>
                        <strong>₡{{ shipping }}</strong>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <strong>₡{{ total }}</strong>
                    </div>

                    <div class="summary-actions">
                        <button class="summary-btn summary-btn-primary">
                            Facturar
                        </button>

                        <router-link to="/" class="summary-btn summary-btn-secondary">
                            Seguir comprando
                        </router-link>
                    </div>
                </aside>

            </div>

        </div>

        <AppModal
            :show="modal.show"
            :type="modal.type"
            :title="modal.title"
            :message="modal.message"
            :confirm-text="modal.confirmText"
            :show-cancel="modal.showCancel"
            @confirm="confirmModal"
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
            products: [],
            shipping: 5,

            modal: {
                show: false,
                type: 'success',
                title: '',
                message: '',
                confirmText: 'Aceptar',
                showCancel: false,
                productId: null,
                action: null
            }
        }
    },

    computed: {

        subtotal() {
            return this.products.reduce((total, product) => {
                return total + this.productSubtotal(product)
            }, 0)
        },

        taxes() {
            return Math.round(this.subtotal * 0.13)
        },

        total() {
            return this.subtotal + this.taxes + this.shipping
        }

    },

    created() {
        this.loadCart()
    },

    methods: {

        loadCart() {

            fetch('/api/cart')
                .then(response => response.json())
                .then(data => {

                    this.products = Object.keys(data.cart).map(id => ({
                        id,
                        ...data.cart[id]
                    }))

                })

        },

        productSubtotal(product) {
            const price = Number(product.price) || 0
            const quantity = Number(product.quantity) || 1

            return price * quantity
        },

        openDeleteModal(id) {
            this.modal = {
                show: true,
                type: 'danger',
                title: '¿Eliminar producto?',
                message: 'Esta acción eliminará el producto del carrito.',
                confirmText: 'Eliminar',
                showCancel: true,
                productId: id,
                action: 'delete-cart'
            }
        },

        confirmModal() {
            if (this.modal.action === 'delete-cart') {
                const productId = this.modal.productId

                fetch('/api/cart/remove/' + productId, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {

                        this.loadCart()

                        this.modal = {
                            show: true,
                            type: 'success',
                            title: 'Producto eliminado',
                            message: data.message ?? 'El producto fue eliminado del carrito.',
                            confirmText: 'Aceptar',
                            showCancel: false,
                            productId: null,
                            action: 'success'
                        }

                    })

                return
            }

            this.closeModal()
        },

        closeModal() {
            this.modal.show = false
        }

    }

}
</script>