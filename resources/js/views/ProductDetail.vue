<template>
    <section class="page-container">
        <div class="main-panel">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="page-title m-0">
                        Detalles del producto
                    </h2>
                    <p class="text-600 mt-2 mb-0">
                        Consulta la información completa del celular seleccionado.
                    </p>
                </div>

                <router-link to="/" class="no-underline">
                    <Button
                        label="Volver al catálogo"
                        icon="pi pi-arrow-left"
                        severity="secondary"
                    />
                </router-link>
            </div>

            <Divider />

            <div
                v-if="!product"
                class="flex flex-column align-items-center justify-content-center p-5 surface-card border-round shadow-1"
            >
                <ProgressSpinner />
                <p class="text-700 mt-3 mb-0">
                    Cargando producto...
                </p>
            </div>

            <div v-else class="grid align-items-start">
                <div class="col-12 lg:col-5">
                    <Card class="h-full product-detail-card">
                        <template #header>
                            <div class="detail-image-box">
                                <img
                                    v-if="product.image"
                                    :src="product.image"
                                    :alt="product.name"
                                    class="detail-product-image"
                                >

                                <div v-else class="detail-image-placeholder">
                                    Imagen
                                </div>
                            </div>
                        </template>

                        <template #title>
                            <span class="text-2xl">
                                {{ product.name }}
                            </span>
                        </template>

                        <template #subtitle>
                            <span class="text-primary font-semibold">
                                {{ product.brand }}
                            </span>
                        </template>

                        <template #content>
                            <div class="flex align-items-center justify-content-between gap-3 flex-wrap">
                                <span class="text-4xl font-bold text-900">
                                    ₡{{ product.price }}
                                </span>

                                <Tag
                                    :value="product.operating_system"
                                    severity="info"
                                />
                            </div>
                        </template>
                    </Card>
                </div>

                <div class="col-12 lg:col-7">
                    <Card class="h-full">
                        <template #title>
                            Descripción
                        </template>

                        <template #content>
                            <p class="text-700 line-height-3 mt-0 mb-4">
                                {{ product.description }}
                            </p>

                            <Divider />

                            <h3 class="text-xl font-bold text-900 mb-3">
                                Características técnicas
                            </h3>

                            <div class="grid mb-4">
                                <div class="col-12 md:col-6">
                                    <div class="spec-prime-row">
                                        <span>Marca</span>
                                        <strong>{{ product.brand }}</strong>
                                    </div>
                                </div>

                                <div class="col-12 md:col-6">
                                    <div class="spec-prime-row">
                                        <span>Memoria RAM</span>
                                        <strong>{{ product.ram }}</strong>
                                    </div>
                                </div>

                                <div class="col-12 md:col-6">
                                    <div class="spec-prime-row">
                                        <span>Almacenamiento</span>
                                        <strong>{{ product.storage }}</strong>
                                    </div>
                                </div>

                                <div class="col-12 md:col-6">
                                    <div class="spec-prime-row">
                                        <span>Sistema Operativo</span>
                                        <strong>{{ product.operating_system }}</strong>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="spec-prime-row">
                                        <span>Categoría</span>
                                        <strong>{{ product.category?.name }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="field mb-4">
                                <label for="quantity" class="font-bold block mb-2">
                                    Cantidad
                                </label>

                                <div class="flex align-items-center gap-2">
                                    <Button
                                        icon="pi pi-minus"
                                        severity="secondary"
                                        rounded
                                        :disabled="quantity === 1"
                                        @click="decreaseQuantity"
                                    />

                                    <InputNumber
                                        id="quantity"
                                        v-model="quantity"
                                        :min="1"
                                        inputClass="text-center"
                                        class="quantity-input"
                                    />

                                    <Button
                                        icon="pi pi-plus"
                                        severity="secondary"
                                        rounded
                                        @click="increaseQuantity"
                                    />
                                </div>
                            </div>

                            <div class="flex gap-3 flex-wrap">
                                <Button
                                    label="Agregar al carrito"
                                    icon="pi pi-shopping-cart"
                                    @click="addToCart"
                                />

                                <Button
                                    label="Favoritos"
                                    icon="pi pi-heart"
                                    severity="secondary"
                                    @click="addToFavorites"
                                />
                            </div>
                        </template>
                    </Card>
                </div>
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
                    :class="modal.type === 'success' ? 'pi pi-check-circle text-primary' : 'pi pi-times-circle text-red-500'"
                    class="modal-prime-icon"
                ></i>

                <p class="text-700 line-height-3 m-0">
                    {{ modal.message }}
                </p>

                <Button
                    :label="modal.confirmText"
                    icon="pi pi-check"
                    @click="closeModal"
                />
            </div>
        </Dialog>
    </section>
</template>

<script>
export default {
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