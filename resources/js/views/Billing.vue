<template>
    <section class="page-container">
        <div class="main-panel">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="page-title m-0">
                        Datos de facturación
                    </h2>

                    <p class="text-600 mt-2 mb-0">
                        Completa la información para confirmar tu compra.
                    </p>
                </div>

                <router-link to="/carrito" class="no-underline">
                    <Button
                        label="Volver al carrito"
                        icon="pi pi-arrow-left"
                        severity="secondary"
                    />
                </router-link>
            </div>

            <Divider />

            <div class="grid align-items-start">
                <div class="col-12 lg:col-8">
                    <form @submit.prevent="confirmOrder">
                        <Card class="mb-4">
                            <template #title>
                                Información personal
                            </template>

                            <template #content>
                                <div class="formgrid grid">
                                    <div class="field col-12 md:col-6">
                                        <label for="fullName" class="font-bold block mb-2">
                                            Nombre completo
                                        </label>

                                        <InputText
                                            id="fullName"
                                            v-model="form.fullName"
                                            required
                                            placeholder="Ingrese su nombre completo"
                                            class="w-full"
                                        />
                                    </div>

                                    <div class="field col-12 md:col-6">
                                        <label for="idNumber" class="font-bold block mb-2">
                                            Cédula
                                        </label>

                                        <InputText
                                            id="idNumber"
                                            v-model="form.idNumber"
                                            required
                                            placeholder="Ingrese su cédula"
                                            class="w-full"
                                        />
                                    </div>

                                    <div class="field col-12 md:col-6">
                                        <label for="email" class="font-bold block mb-2">
                                            Correo electrónico
                                        </label>

                                        <InputText
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            required
                                            placeholder="correo@ejemplo.com"
                                            class="w-full"
                                        />
                                    </div>

                                    <div class="field col-12 md:col-6">
                                        <label for="phone" class="font-bold block mb-2">
                                            Teléfono
                                        </label>

                                        <InputText
                                            id="phone"
                                            v-model="form.phone"
                                            required
                                            placeholder="+506 8888-8888"
                                            class="w-full"
                                        />
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <Card class="mb-4">
                            <template #title>
                                Datos de entrega
                            </template>

                            <template #content>
                                <div class="formgrid grid">
                                    <div class="field col-12">
                                        <label for="province" class="font-bold block mb-2">
                                            Provincia
                                        </label>

                                        <InputText
                                            id="province"
                                            v-model="form.province"
                                            required
                                            placeholder="Ejemplo: San José"
                                            class="w-full"
                                        />
                                    </div>

                                    <div class="field col-12">
                                        <label for="address" class="font-bold block mb-2">
                                            Dirección exacta
                                        </label>

                                        <Textarea
                                            id="address"
                                            v-model="form.address"
                                            required
                                            rows="4"
                                            placeholder="Ingrese la dirección exacta de entrega"
                                            class="w-full"
                                        />
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <Card class="mb-4">
                            <template #title>
                                Método de pago
                            </template>

                            <template #content>
                                <div class="grid">
                                    <div
                                        v-for="method in paymentMethods"
                                        :key="method.value"
                                        class="col-12 md:col-4"
                                    >
                                        <label class="payment-prime-card">
                                            <RadioButton
                                                v-model="form.paymentMethod"
                                                :inputId="'payment-' + method.value"
                                                name="paymentMethod"
                                                :value="method.value"
                                                required
                                            />

                                            <span class="font-bold">
                                                {{ method.label }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <div class="flex gap-3 flex-wrap">
                            <router-link to="/carrito" class="no-underline">
                                <Button
                                    label="Volver al carrito"
                                    icon="pi pi-arrow-left"
                                    severity="secondary"
                                />
                            </router-link>

                            <Button
                                type="submit"
                                label="Confirmar compra"
                                icon="pi pi-check"
                            />
                        </div>
                    </form>
                </div>

                <aside class="col-12 lg:col-4">
                    <Card class="billing-summary-prime">
                        <template #title>
                            Resumen del pedido
                        </template>

                        <template #content>
                            <Message
                                v-if="products.length === 0"
                                severity="warn"
                                :closable="false"
                            >
                                No hay productos en el carrito.
                            </Message>

                            <div v-else>
                                <div
                                    v-for="product in products"
                                    :key="product.id"
                                    class="billing-product-prime"
                                >
                                    <div class="billing-product-image-box">
                                        <img
                                            v-if="product.image"
                                            :src="product.image"
                                            :alt="product.name"
                                        >

                                        <span v-else>Imagen</span>
                                    </div>

                                    <div>
                                        <h4 class="mt-0 mb-1 text-900">
                                            {{ product.name }}
                                        </h4>

                                        <p class="text-600 mt-0 mb-1">
                                            Cantidad: {{ product.quantity }}
                                        </p>

                                        <strong class="text-primary">
                                            ₡{{ productSubtotal(product) }}
                                        </strong>
                                    </div>
                                </div>

                                <Divider />

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

                <Button
                    :label="modal.confirmText"
                    :severity="modal.type === 'danger' ? 'danger' : 'primary'"
                    @click="goHome"
                />
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

            paymentMethods: [
                { label: 'Tarjeta', value: 'Tarjeta' },
                { label: 'SINPE Móvil', value: 'SINPE' },
                { label: 'Pago contra entrega', value: 'Efectivo' }
            ],

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

            if (this.modal.type === 'success') {
                this.$router.push('/')
            }
        }
    }
}
</script>