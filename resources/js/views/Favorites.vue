<template>
    <section class="page-container">
        <div class="main-panel">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="page-title m-0">
                        Favoritos
                    </h2>

                    <p class="text-600 mt-2 mb-0">
                        Aquí puedes ver los productos que agregaste a tu lista de favoritos.
                    </p>
                </div>

                <router-link to="/" class="no-underline">
                    <Button
                        label="Ver productos"
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
                <i class="pi pi-heart favorite-empty-icon"></i>

                <h3 class="text-2xl text-900 mb-2">
                    No hay favoritos
                </h3>

                <p class="text-600 mb-4">
                    Aún no has agregado productos a favoritos.
                </p>

                <router-link to="/" class="no-underline">
                    <Button
                        label="Ver productos"
                        icon="pi pi-shopping-bag"
                    />
                </router-link>
            </div>

            <div v-else class="grid">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="col-12 sm:col-6 lg:col-4 xl:col-3"
                >
                    <Card class="h-full favorite-prime-card">
                        <template #header>
                            <div class="favorite-image-box-prime">
                                <Button
                                    icon="pi pi-trash"
                                    severity="danger"
                                    rounded
                                    text
                                    class="favorite-delete-prime"
                                    title="Eliminar producto"
                                    @click="openDeleteModal(product.id)"
                                />

                                <router-link
                                    :to="'/producto/' + product.id"
                                    class="no-underline"
                                >
                                    <img
                                        v-if="product.image"
                                        :src="product.image"
                                        :alt="product.name"
                                        class="favorite-image-prime"
                                    >

                                    <div v-else class="favorite-placeholder-prime">
                                        Imagen
                                    </div>
                                </router-link>
                            </div>
                        </template>

                        <template #title>
                            <span class="text-xl">
                                {{ product.name }}
                            </span>
                        </template>

                        <template #subtitle>
                            <span class="text-primary font-semibold">
                                {{ product.brand ?? 'Celular' }}
                            </span>
                        </template>

                        <template #content>
                            <div class="flex flex-column gap-2">
                                <div class="flex justify-content-between text-700">
                                    <span v-if="product.ram">{{ product.ram }}</span>
                                    <span v-if="product.storage">{{ product.storage }}</span>
                                </div>

                                <p class="text-2xl font-bold text-900 m-0">
                                    ₡{{ product.price }}
                                </p>
                            </div>
                        </template>

                        <template #footer>
                            <router-link
                                :to="'/producto/' + product.id"
                                class="no-underline"
                            >
                                <Button
                                    label="Ver detalle"
                                    icon="pi pi-eye"
                                    severity="secondary"
                                    class="w-full"
                                />
                            </router-link>
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

        openDeleteModal(id) {
            this.modal = {
                show: true,
                type: 'danger',
                title: '¿Eliminar favorito?',
                message: 'Esta acción eliminará el producto de tu lista de favoritos.',
                confirmText: 'Eliminar',
                showCancel: true,
                productId: id,
                action: 'delete-favorite'
            }
        },

        confirmModal() {
            if (this.modal.action === 'delete-favorite') {
                const productId = this.modal.productId

                fetch('/api/favorites/remove/' + productId, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        this.loadFavorites()

                        this.modal = {
                            show: true,
                            type: 'success',
                            title: 'Favorito eliminado',
                            message: data.message ?? 'El producto fue eliminado de favoritos.',
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