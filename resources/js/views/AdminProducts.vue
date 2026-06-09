<template>
    <section class="page-container">

        <div class="main-panel">

            <div class="admin-header">
                <div>
                    <h2 class="page-title">Panel administrativo</h2>
                    <p class="admin-subtitle">
                        Gestión de productos de la tienda G&S.
                    </p>
                </div>

                <button class="summary-btn summary-btn-primary" @click="openCreateModal">
                    Agregar producto
                </button>
            </div>

            <div class="admin-stats">
                <div class="admin-stat-card">
                    <span>Total productos</span>
                    <strong>{{ totalProducts }}</strong>
                </div>

                <div class="admin-stat-card">
                    <span>Página actual</span>
                    <strong>{{ currentPage }}</strong>
                </div>

                <div class="admin-stat-card">
                    <span>Categorías</span>
                    <strong>{{ categories.length }}</strong>
                </div>
            </div>

            <div v-if="loading" class="empty-message">
                Cargando productos...
            </div>

            <div v-else-if="products.length === 0" class="empty-message">
                <h3>No hay productos</h3>
                <p>Aún no hay productos registrados.</p>
            </div>

            <div v-else class="admin-table-card">

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>RAM</th>
                            <th>Almacenamiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="product in products"
                            :key="product.id"
                        >
                            <td>
                                <div class="admin-product-cell">
                                    <img
                                        v-if="product.image"
                                        :src="product.image"
                                        :alt="product.name"
                                    >

                                    <div v-else class="admin-product-placeholder">
                                        Img
                                    </div>

                                    <div>
                                        <strong>{{ product.name }}</strong>
                                        <span>{{ product.operating_system ?? 'Sistema no registrado' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>{{ product.brand ?? 'Sin marca' }}</td>

                            <td>{{ product.category?.name ?? 'Sin categoría' }}</td>

                            <td>₡{{ product.price }}</td>

                            <td>{{ product.ram ?? 'N/A' }}</td>

                            <td>{{ product.storage ?? 'N/A' }}</td>

                            <td>
                                <div class="admin-actions">
                                    <button class="admin-btn edit" @click="openEditModal(product)">
                                        Editar
                                    </button>

                                    <button class="admin-btn delete" @click="openDeleteModal(product.id)">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="lastPage > 1" class="pagination">
                    <button
                        class="pagination-btn"
                        :disabled="currentPage === 1"
                        @click="changePage(currentPage - 1)"
                    >
                        Anterior
                    </button>

                    <button
                        v-for="page in lastPage"
                        :key="page"
                        class="pagination-number"
                        :class="{ active: currentPage === page }"
                        @click="changePage(page)"
                    >
                        {{ page }}
                    </button>

                    <button
                        class="pagination-btn"
                        :disabled="currentPage === lastPage"
                        @click="changePage(currentPage + 1)"
                    >
                        Siguiente
                    </button>
                </div>

            </div>

        </div>

        <div v-if="productModal.show" class="modal-overlay">
            <div class="admin-product-modal">

                <h2>
                    {{ productModal.mode === 'create' ? 'Agregar producto' : 'Editar producto' }}
                </h2>

                <form @submit.prevent="saveProduct">

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" v-model="form.name" required>
                        </div>

                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" v-model="form.brand" required>
                        </div>

                        <div class="form-group">
                            <label>Precio</label>
                            <input type="number" v-model="form.price" required>
                        </div>

                        <div class="form-group">
                            <label>RAM</label>
                            <input type="text" v-model="form.ram" required>
                        </div>

                        <div class="form-group">
                            <label>Almacenamiento</label>
                            <input type="text" v-model="form.storage" required>
                        </div>

                        <div class="form-group">
                            <label>Sistema operativo</label>
                            <input type="text" v-model="form.operating_system" required>
                        </div>

                        <div class="form-group">
                            <label>Categoría</label>
                            <select v-model="form.category_id" required>
                                <option value="">Seleccione una categoría</option>

                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Link de imagen</label>
                            <input type="text" v-model="form.image" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea v-model="form.description" rows="4" required></textarea>
                    </div>

                    <div v-if="form.image" class="admin-image-preview">
                        <span>Vista previa</span>
                        <img :src="form.image" alt="Vista previa">
                    </div>

                    <div class="admin-modal-actions">
                        <button
                            type="button"
                            class="summary-btn summary-btn-secondary"
                            @click="closeProductModal"
                        >
                            Cancelar
                        </button>

                        <button type="submit" class="summary-btn summary-btn-primary">
                            {{ productModal.mode === 'create' ? 'Guardar producto' : 'Actualizar producto' }}
                        </button>
                    </div>

                </form>

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
                price: '',
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
                price: product.price ?? '',
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