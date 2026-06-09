<template>
    <section class="page-container">

        <div class="main-panel">

            <h2 class="page-title">Datos de facturación</h2>

            <div class="billing-layout">

                <form class="billing-form" @submit.prevent="confirmOrder">

                    <div class="form-section">
                        <h3>Información personal</h3>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nombre completo</label>
                                <input
                                    type="text"
                                    v-model="form.fullName"
                                    required
                                    placeholder="Ingrese su nombre completo"
                                >
                            </div>

                            <div class="form-group">
                                <label>Cédula</label>
                                <input
                                    type="text"
                                    v-model="form.idNumber"
                                    required
                                    placeholder="Ingrese su cédula"
                                >
                            </div>

                            <div class="form-group">
                                <label>Correo electrónico</label>
                                <input
                                    type="email"
                                    v-model="form.email"
                                    required
                                    placeholder="correo@ejemplo.com"
                                >
                            </div>

                            <div class="form-group">
                                <label>Teléfono</label>
                                <input
                                    type="text"
                                    v-model="form.phone"
                                    required
                                    placeholder="+506 8888-8888"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Datos de entrega</h3>

                        <div class="form-group">
                            <label>Provincia</label>
                            <input
                                type="text"
                                v-model="form.province"
                                required
                                placeholder="Ejemplo: San José"
                            >
                        </div>

                        <div class="form-group">
                            <label>Dirección exacta</label>
                            <textarea
                                v-model="form.address"
                                required
                                rows="4"
                                placeholder="Ingrese la dirección exacta de entrega"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Método de pago</h3>

                        <div class="payment-options">
                            <label class="payment-card">
                                <input
                                    type="radio"
                                    value="Tarjeta"
                                    v-model="form.paymentMethod"
                                    required
                                >
                                <span>Tarjeta</span>
                            </label>

                            <label class="payment-card">
                                <input
                                    type="radio"
                                    value="SINPE"
                                    v-model="form.paymentMethod"
                                >
                                <span>SINPE Móvil</span>
                            </label>

                            <label class="payment-card">
                                <input
                                    type="radio"
                                    value="Efectivo"
                                    v-model="form.paymentMethod"
                                >
                                <span>Pago contra entrega</span>
                            </label>
                        </div>
                    </div>

                    <div class="billing-actions">
                        <router-link to="/carrito" class="summary-btn summary-btn-secondary">
                            Volver al carrito
                        </router-link>

                        <button type="submit" class="summary-btn summary-btn-primary">
                            Confirmar compra
                        </button>
                    </div>

                </form>

                <aside class="billing-summary">
                    <h3>Resumen del pedido</h3>

                    <div v-if="products.length === 0" class="billing-empty">
                        No hay productos en el carrito.
                    </div>

                    <div v-else>
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="billing-product"
                        >
                            <img
                                v-if="product.image"
                                :src="product.image"
                                :alt="product.name"
                            >

                            <div>
                                <h4>{{ product.name }}</h4>
                                <p>Cantidad: {{ product.quantity }}</p>
                                <strong>₡{{ productSubtotal(product) }}</strong>
                            </div>
                        </div>

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
            @confirm="goHome"
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

            form: {
                fullName: '',
                idNumber: '',
                email: '',
                phone: '',
                province: '',
                address: '',
                paymentMethod: ''
            },

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

        confirmOrder() {
            if (this.products.length === 0) {
                this.modal = {
                    show: true,
                    type: 'danger',
                    title: 'Carrito vacío',
                    message: 'No puedes confirmar una compra sin productos en el carrito.',
                    confirmText: 'Aceptar',
                    showCancel: false
                }

                return
            }

            this.modal = {
                show: true,
                type: 'success',
                title: 'Compra confirmada',
                message: 'La compra fue registrada correctamente. Gracias por comprar en G&S.',
                confirmText: 'Aceptar',
                showCancel: false
            }
        },

        closeModal() {
            this.modal.show = false
        },

        goHome() {
            this.modal.show = false
            this.$router.push('/')
        }

    }

}
</script>