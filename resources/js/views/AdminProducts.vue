<template>
    <section class="page-container">
        <div class="main-panel">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="page-title m-0">
                        Panel administrativo
                    </h2>

                    <p class="text-600 mt-2 mb-0">
                        Gestión de productos de la tienda G&S.
                    </p>
                </div>

                <Button
                    label="Agregar producto"
                    icon="pi pi-plus"
                    @click="openCreateModal"
                />
            </div>

            <Divider />

            <div class="grid mb-4">
                <div class="col-12 md:col-4">
                    <Card>
                        <template #content>
                            <span class="text-600 block mb-2">Total productos</span>
                            <strong class="text-3xl text-primary">{{ totalProducts }}</strong>
                        </template>
                    </Card>
                </div>

                <div class="col-12 md:col-4">
                    <Card>
                        <template #content>
                            <span class="text-600 block mb-2">Página actual</span>
                            <strong class="text-3xl text-primary">{{ currentPage }}</strong>
                        </template>
                    </Card>
                </div>

                <div class="col-12 md:col-4">
                    <Card>
                        <template #content>
                            <span class="text-600 block mb-2">Categorías</span>
                            <strong class="text-3xl text-primary">{{ categories.length }}</strong>
                        </template>
                    </Card>
                </div>
            </div>

            <div
                v-if="loading"
                class="flex flex-column align-items-center justify-content-center p-5 surface-card border-round shadow-1"
            >
                <ProgressSpinner />
                <p class="text-700 mt-3 mb-0">
                    Cargando productos...
                </p>
            </div>

            <Message
                v-else-if="products.length === 0"
                severity="info"
                :closable="false"
            >
                Aún no hay productos registrados.
            </Message>

            <Card v-else>
                <template #content>
                    <DataTable
                        :value="products"
                        responsiveLayout="scroll"
                        stripedRows
                    >
                        <Column header="Producto">
                            <template #body="slotProps">
                                <div class="flex align-items-center gap-3">
                                    <div class="admin-product-image-prime">
                                        <img
                                            v-if="slotProps.data.image"
                                            :src="slotProps.data.image"
                                            :alt="slotProps.data.name"
                                        >

                                        <span v-else>Img</span>
                                    </div>

                                    <div>
                                        <strong class="block text-900 mb-1">
                                            {{ slotProps.data.name }}
                                        </strong>

                                        <span class="text-600 text-sm">
                                            {{ slotProps.data.operating_system ?? 'Sistema no registrado' }}
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </Column>

                        <Column field="brand" header="Marca">
                            <template #body="slotProps">
                                {{ slotProps.data.brand ?? 'Sin marca' }}
                            </template>
                        </Column>

                        <Column header="Categoría">
                            <template #body="slotProps">
                                <Tag
                                    :value="slotProps.data.category?.name ?? 'Sin categoría'"
                                    severity="info"
                                />
                            </template>
                        </Column>

                        <Column header="Precio">
                            <template #body="slotProps">
                                <strong>₡{{ slotProps.data.price }}</strong>
                            </template>
                        </Column>

                        <Column header="RAM">
                            <template #body="slotProps">
                                {{ slotProps.data.ram ?? 'N/A' }}
                            </template>
                        </Column>

                        <Column header="Almacenamiento">
                            <template #body="slotProps">
                                {{ slotProps.data.storage ?? 'N/A' }}
                            </template>
                        </Column>

                        <Column header="Acciones">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <Button
                                        icon="pi pi-pencil"
                                        label="Editar"
                                        severity="secondary"
                                        size="small"
                                        @click="openEditModal(slotProps.data)"
                                    />

                                    <Button
                                        icon="pi pi-trash"
                                        label="Eliminar"
                                        severity="danger"
                                        size="small"
                                        @click="openDeleteModal(slotProps.data.id)"
                                    />
                                </div>
                            </template>
                        </Column>
                    </DataTable>

                    <div
                        v-if="lastPage > 1"
                        class="flex justify-content-center align-items-center gap-2 mt-4 flex-wrap"
                    >
                        <Button
                            label="Anterior"
                            icon="pi pi-angle-left"
                            severity="secondary"
                            :disabled="currentPage === 1"
                            @click="changePage(currentPage - 1)"
                        />

                        <Button
                            v-for="page in lastPage"
                            :key="page"
                            :label="String(page)"
                            :severity="currentPage === page ? 'primary' : 'secondary'"
                            @click="changePage(page)"
                        />

                        <Button
                            label="Siguiente"
                            icon="pi pi-angle-right"
                            iconPos="right"
                            severity="secondary"
                            :disabled="currentPage === lastPage"
                            @click="changePage(currentPage + 1)"
                        />
                    </div>
                </template>
            </Card>
        </div>

        <Dialog
            v-model:visible="productModal.show"
            :header="productModal.mode === 'create' ? 'Agregar producto' : 'Editar producto'"
            modal
            :style="{ width: '48rem' }"
        >
            <form @submit.prevent="saveProduct">
                <div class="formgrid grid">
                    <div class="field col-12 md:col-6">
                        <label for="name" class="font-bold block mb-2">Nombre</label>
                        <InputText id="name" v-model="form.name" required class="w-full" />
                    </div>

                    <div class="field col-12 md:col-6">
                        <label for="brand" class="font-bold block mb-2">Marca</label>
                        <InputText id="brand" v-model="form.brand" required class="w-full" />
                    </div>

                    <div class="field col-12 md:col-6">
                        <label for="price" class="font-bold block mb-2">Precio</label>
                        <InputNumber
                            id="price"
                            v-model="form.price"
                            required
                            inputClass="w-full"
                            class="w-full"
                            :min="0"
                        />
                    </div>

                    <div class="field col-12 md:col-6">
                        <label for="ram" class="font-bold block mb-2">RAM</label>
                        <InputText id="ram" v-model="form.ram" required class="w-full" />
                    </div>

                    <div class="field col-12 md:col-6">
                        <label for="storage" class="font-bold block mb-2">Almacenamiento</label>
                        <InputText id="storage" v-model="form.storage" required class="w-full" />
                    </div>

                    <div class="field col-12 md:col-6">
                        <label for="operating_system" class="font-bold block mb-2">Sistema operativo</label>
                        <InputText
                            id="operating_system"
                            v-model="form.operating_system"
                            required
                            class="w-full"
                        />
                    </div>

                    <div class="field col-12 md:col-6">
                        <label for="category" class="font-bold block mb-2">Categoría</label>
                        <Dropdown
                            id="category"
                            v-model="form.category_id"
                            :options="categories"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Seleccione una categoría"
                            required
                            class="w-full"
                        />
                    </div>

                    <div class="field col-12 md:col-6">
                        <label for="image" class="font-bold block mb-2">Link de imagen</label>
                        <InputText id="image" v-model="form.image" required class="w-full" />
                    </div>

                    <div class="field col-12">
                        <label for="description" class="font-bold block mb-2">Descripción</label>
                        <Textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            required
                            class="w-full"
                        />
                    </div>
                </div>

                <div v-if="form.image" class="admin-image-preview-prime">
                    <span>Vista previa</span>
                    <img :src="form.image" alt="Vista previa">
                </div>

                <div class="flex justify-content-end gap-2 mt-4">
                    <Button
                        type="button"
                        label="Cancelar"
                        icon="pi pi-times"
                        severity="secondary"
                        @click="closeProductModal"
                    />

                    <Button
                        type="submit"
                        :label="productModal.mode === 'create' ? 'Guardar producto' : 'Actualizar producto'"
                        icon="pi pi-check"
                    />
                </div>
            </form>
        </Dialog>

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
            categories: [],
            loading: false,

            currentPage: 1,
            lastPage: 1,
            totalProducts: 0,

            productModal: {
                show: false,
                mode: 'create'
            },

            form: {
                id: null,
                name: '',
                description: '',
                price: '',
                brand: '',
                ram: '',
                storage: '',
                operating_system: '',
                image: '',
                category_id: ''
            },

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
        this.loadProducts()
        this.loadCategories()
    },

    methods: {
        loadProducts(page = 1) {
            this.loading = true

            fetch('/api/products?page=' + page)
                .then(response => response.json())
                .then(data => {
                    this.products = data.data ?? []
                    this.currentPage = data.current_page ?? 1
                    this.lastPage = data.last_page ?? 1
                    this.totalProducts = data.total ?? this.products.length
                })
                .finally(() => {
                    this.loading = false
                })
        },

        loadCategories() {
            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    this.categories = data.data ?? data
                })
        },

        changePage(page) {
            if (page < 1 || page > this.lastPage) {
                return
            }

            this.loadProducts(page)
        },

        openCreateModal() {
            this.productModal = {
                show: true,
                mode: 'create'
            }

            this.form = {
                id: null,
                name: '',
                description: '',
                price: null,
                brand: '',
                ram: '',
                storage: '',
                operating_system: '',
                image: '',
                category_id: ''
            }
        },

        openEditModal(product) {
            this.productModal = {
                show: true,
                mode: 'edit'
            }

            this.form = {
                id: product.id,
                name: product.name ?? '',
                description: product.description ?? '',
                price: Number(product.price) || 0,
                brand: product.brand ?? '',
                ram: product.ram ?? '',
                storage: product.storage ?? '',
                operating_system: product.operating_system ?? '',
                image: product.image ?? '',
                category_id: product.category_id ?? product.category?.id ?? ''
            }
        },

        closeProductModal() {
            this.productModal.show = false
        },

        saveProduct() {
            if (this.productModal.mode === 'create') {
                this.createProduct()
            } else {
                this.updateProduct()
            }
        },

        createProduct() {
            fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.form)
            })
                .then(async response => {
                    const data = await response.json()

                    if (!response.ok) {
                        throw data
                    }

                    return data
                })
                .then(data => {
                    this.closeProductModal()
                    this.loadProducts(this.currentPage)

                    this.showSuccess(
                        'Producto agregado',
                        data.message ?? 'El producto fue registrado correctamente.'
                    )
                })
                .catch(error => {
                    this.showError(error)
                })
        },

        updateProduct() {
            fetch('/api/products/' + this.form.id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.form)
            })
                .then(async response => {
                    const data = await response.json()

                    if (!response.ok) {
                        throw data
                    }

                    return data
                })
                .then(data => {
                    this.closeProductModal()
                    this.loadProducts(this.currentPage)

                    this.showSuccess(
                        'Producto actualizado',
                        data.message ?? 'El producto fue actualizado correctamente.'
                    )
                })
                .catch(error => {
                    this.showError(error)
                })
        },

        openDeleteModal(id) {
            this.modal = {
                show: true,
                type: 'danger',
                title: '¿Eliminar producto?',
                message: 'Esta acción eliminará el producto del sistema.',
                confirmText: 'Eliminar',
                showCancel: true,
                productId: id,
                action: 'delete-product'
            }
        },

        confirmModal() {
            if (this.modal.action === 'delete-product') {
                this.deleteProduct()
                return
            }

            this.closeModal()
        },

        deleteProduct() {
            fetch('/api/products/' + this.modal.productId, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(async response => {
                    const data = await response.json()

                    if (!response.ok) {
                        throw data
                    }

                    return data
                })
                .then(data => {
                    this.loadProducts(this.currentPage)

                    this.modal = {
                        show: true,
                        type: 'success',
                        title: 'Producto eliminado',
                        message: data.message ?? 'El producto fue eliminado correctamente.',
                        confirmText: 'Aceptar',
                        showCancel: false,
                        productId: null,
                        action: null
                    }
                })
                .catch(error => {
                    this.showError(error)
                })
        },

        showSuccess(title, message) {
            this.modal = {
                show: true,
                type: 'success',
                title: title,
                message: message,
                confirmText: 'Aceptar',
                showCancel: false,
                productId: null,
                action: null
            }
        },

        showError(error) {
            let message = 'Ocurrió un error al procesar la solicitud.'

            if (error.message) {
                message = error.message
            }

            if (error.errors) {
                const firstKey = Object.keys(error.errors)[0]
                message = error.errors[firstKey][0]
            }

            this.modal = {
                show: true,
                type: 'danger',
                title: 'Error',
                message: message,
                confirmText: 'Aceptar',
                showCancel: false,
                productId: null,
                action: null
            }
        },

        closeModal() {
            this.modal.show = false
        }
    }
}
</script>