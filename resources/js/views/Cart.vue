<template>
    <section class="page-container">
        <div class="main-panel">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="page-title m-0">
                        Carrito de compra
                    </h2>

                    <p class="text-600 mt-2 mb-0">
                        Revisa los productos agregados antes de continuar con la facturación.
                    </p>
                </div>

                <router-link to="/" class="no-underline">
                    <Button
                        label="Seguir comprando"
                        icon="pi pi-shopping-bag"
                        severity="secondary"
                    />
                </router-link>
            </div>

            <Divider />

            <div
                v-if="products.length === 0"
                class="surface-card border-round shadow-1 p-5 text-center"
            >
                <i class="pi pi-shopping-cart cart-empty-icon"></i>

                <h3 class="text-2xl text-900 mb-2">
                    Carrito vacío
                </h3>

                <p class="text-600 mb-4">
                    No has agregado productos al carrito.
                </p>

                <router-link to="/" class="no-underline">
                    <Button
                        label="Seguir comprando"
                        icon="pi pi-shopping-bag"
                    />
                </router-link>
            </div>

            <div v-else class="grid align-items-start">
                <div class="col-12 lg:col-8">
                    <div class="flex flex-column gap-3">
                        <Card
                            v-for="product in products"
                            :key="product.id"
                            class="cart-prime-card"
                        >
                            <template #content>
                                <div class="grid align-items-center">
                                    <div class="col-12 md:col-3">
                                        <div class="cart-image-box-prime">
                                            <img
                                                v-if="product.image"
                                                :src="product.image"
                                                :alt="product.name"
                                                class="cart-image-prime"
                                            >

                                            <span v-else class="text-600 font-bold">
                                                Imagen
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 md:col-5">
                                        <h3 class="text-2xl text-900 mt-0 mb-2">
                                            {{ product.name }}
                                        </h3>

                                        <p class="text-600 mb-3">
                                            {{ product.brand ?? 'Celular' }}
                                            <span v-if="product.ram"> • {{ product.ram }}</span>
                                            <span v-if="product.storage"> • {{ product.storage }}</span>
                                        </p>

                                        <div class="flex align-items-center gap-2">
                                            <span class="text-600">Cantidad:</span>
                                            <strong class="cart-quantity-badge">
                                                {{ product.quantity }}
                                            </strong>
                                        </div>
                                    </div>

                                    <div class="col-12 md:col-3">
                                        <span class="text-600 block mb-2">
                                            Subtotal
                                        </span>

                                        <strong class="text-2xl text-900">
                                            ₡{{ productSubtotal(product) }}
                                        </strong>
                                    </div>

                                    <div class="col-12 md:col-1 flex md:justify-content-end">
                                        <Button
                                            icon="pi pi-trash"
                                            severity="danger"
                                            rounded
                                            text
                                            title="Eliminar producto"
                                            @click="openDeleteModal(product.id)"
                                        />
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </div>
                </div>

                <aside class="col-12 lg:col-4">
                    <Card class="cart-summary-prime">
                        <template #title>
                            Resumen de compra
                        </template>

                        <template #content>
                            <div class="summary-prime-row">
                                <span>Subtotal</span>
                                <strong>₡{{ subtotal }}</strong>
                            </div>

                            <div class="summary-prime-row">
                                <span>Impuestos (13%)</span>
                                <strong>₡{{ taxes }}</strong>
                            </div>

                            <div class="summary-prime-row">
                                <span>Envío</span>
                                <strong>₡{{ shipping }}</strong>
                            </div>

                            <Divider />

                            <div class="summary-prime-total">
                                <span>Total</span>
                                <strong>₡{{ total }}</strong>
                            </div>

                            <div class="flex flex-column gap-3 mt-4">
                                <router-link to="/facturacion" class="no-underline">
                                    <Button
                                        label="Facturar"
                                        icon="pi pi-credit-card"
                                        class="w-full"
                                    />
                                </router-link>

                                <router-link to="/" class="no-underline">
                                    <Button
                                        label="Seguir comprando"
                                        icon="pi pi-shopping-bag"
                                        severity="secondary"
                                        class="w-full"
                                    />
                                </router-link>
                            </div>
                        </template>
                    </Card>
                </aside>
            </div>
        </div>

        <Dialog
            v-model:visible="modal.show"
            :header="modal.title"
            modal
            :style="{ width: '28rem' }"
        >
            <div class="flex flex-column align-items-center text-center gap-3">
                <i
                    :class="modal.type === 'success' ? 'pi pi-check-circle text-primary' : 'pi pi-exclamation-triangle text-red-500'"
                    class="modal-prime-icon"
                ></i>

                <p class="text-700 line-height-3 m-0">
                    {{ modal.message }}
                </p>

                <div class="flex justify-content-center gap-2 mt-2">
                    <Button
                        v-if="modal.showCancel"
                        label="Cancelar"
                        severity="secondary"
                        @click="closeModal"
                    />

                    <Button
                        :label="modal.confirmText"
                        :severity="modal.type === 'danger' ? 'danger' : 'primary'"
                        @click="confirmModal"
                    />
                </div>
            </div>
        </Dialog>
    </section>
</template>

<script>
export default {
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