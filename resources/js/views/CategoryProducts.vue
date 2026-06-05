<template>
    <section class="page-container">

        <div class="main-panel">

            <h2 class="page-title">
                {{ category.name }}
            </h2>

            <div class="catalog-layout">

                <aside class="filters-panel">
                    <h3>Filtros</h3>

                    <!-- Marca: solo aparece si NO estás en una categoría de marca -->
                    <div class="filter-card" v-if="!isBrandCategory">
                        <h4>Marca</h4>

                        <label>
                            <input type="checkbox" value="Xiaomi" v-model="selectedBrands">
                            Xiaomi
                        </label>

                        <label>
                            <input type="checkbox" value="Samsung" v-model="selectedBrands">
                            Samsung
                        </label>

                        <label>
                            <input type="checkbox" value="Apple" v-model="selectedBrands">
                            Apple
                        </label>

                        <label>
                            <input type="checkbox" value="Redmi" v-model="selectedBrands">
                            Redmi
                        </label>

                        <label>
                            <input type="checkbox" value="Motorola" v-model="selectedBrands">
                            Motorola
                        </label>

                        <label>
                            <input type="checkbox" value="Huawei" v-model="selectedBrands">
                            Huawei
                        </label>

                        <label>
                            <input type="checkbox" value="Realme" v-model="selectedBrands">
                            Realme
                        </label>

                        <label>
                            <input type="checkbox" value="Oppo" v-model="selectedBrands">
                            Oppo
                        </label>
                    </div>

                    <div class="filter-card">
                        <h4>Gama</h4>

                        <label>
                            <input type="checkbox" value="Alta gama" v-model="selectedRanges">
                            Alta gama
                        </label>

                        <label>
                            <input type="checkbox" value="Gama media" v-model="selectedRanges">
                            Gama media
                        </label>

                        <label>
                            <input type="checkbox" value="Gama baja" v-model="selectedRanges">
                            Gama baja
                        </label>
                    </div>

                    <div class="filter-card">
                        <h4>Sistema Operativo</h4>

                        <label>
                            <input type="checkbox" value="iOS" v-model="selectedSystems">
                            iOS
                        </label>

                        <label>
                            <input type="checkbox" value="Android" v-model="selectedSystems">
                            Android
                        </label>
                    </div>

                    <div class="filter-card">
                        <h4>Memoria</h4>

                        <label>
                            <input type="checkbox" value="64" v-model="selectedMemories">
                            64 GB
                        </label>

                        <label>
                            <input type="checkbox" value="128" v-model="selectedMemories">
                            128 GB
                        </label>

                        <label>
                            <input type="checkbox" value="256" v-model="selectedMemories">
                            256 GB
                        </label>

                        <label>
                            <input type="checkbox" value="512" v-model="selectedMemories">
                            512 GB
                        </label>
                    </div>

                    <div class="filter-card">
                        <h4>Precio</h4>

                        <label class="price-input-label">
                            Mínimo
                            <input
                                type="number"
                                class="price-input"
                                v-model.number="minPrice"
                                min="0"
                            >
                        </label>

                        <label class="price-input-label">
                            Máximo
                            <input
                                type="number"
                                class="price-input"
                                v-model.number="maxPrice"
                                min="0"
                            >
                        </label>
                    </div>

                    <button class="btn-secondary clear-filters-btn" @click="clearFilters">
                        Limpiar filtros
                    </button>
                </aside>

                <div class="catalog-products">

                    <div v-if="filteredProducts.length === 0" class="empty-message">
                        No hay productos que coincidan con los filtros seleccionados.
                    </div>

                    <template v-else>

                        <div class="catalog-grid">

                            <div
                                v-for="product in paginatedProducts"
                                :key="product.id"
                                class="product-card"
                            >

                                <img
                                    v-if="product.image"
                                    :src="product.image"
                                    :alt="product.name"
                                    class="product-image"
                                >

                                <div v-else class="image-placeholder">
                                    Imagen
                                </div>

                                <p class="product-brand">
                                    {{ product.brand }}
                                </p>

                                <h3 class="product-name">
                                    {{ product.name }}
                                </h3>

                                <div class="product-specs">
                                    <span>{{ product.ram }}</span>
                                    <span>{{ product.storage }}</span>
                                </div>

                                <div class="product-specs">
                                    <span>₡{{ product.price }}</span>
                                    <span>{{ product.operating_system }}</span>
                                </div>

                                <router-link
                                    :to="'/producto/' + product.id"
                                    class="btn-secondary product-button"
                                >
                                    Ver producto
                                </router-link>

                            </div>

                        </div>

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
                                :class="{ active: page === currentPage }"
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

                    </template>

                </div>

            </div>

        </div>

    </section>
</template>

<script>
export default {

    data() {
        return {
            category: {},
            products: [],

            selectedBrands: [],
            selectedRanges: [],
            selectedSystems: [],
            selectedMemories: [],

            minPrice: 0,
            maxPrice: 1000000,

            currentPage: 1,
            perPage: 6
        }
    },

    computed: {

        isBrandCategory() {
            const categoryName = (this.category.name ?? '').toLowerCase()

            return [
                'samsung',
                'apple',
                'xiaomi',
                'redmi',
                'motorola',
                'huawei',
                'realme',
                'oppo'
            ].includes(categoryName)
        },

        filteredProducts() {
            return this.products.filter(product => {

                const brand = product.brand ?? ''
                const system = product.operating_system ?? ''
                const storage = product.storage ?? ''
                const price = Number(product.price) || 0
                const categoryName = product.category?.name ?? this.category.name ?? ''

                const matchBrand =
                    this.selectedBrands.length === 0 ||
                    this.selectedBrands.includes(brand)

                const matchRange =
                    this.selectedRanges.length === 0 ||
                    this.selectedRanges.some(range =>
                        categoryName.toLowerCase().includes(range.toLowerCase())
                    )

                const matchSystem =
                    this.selectedSystems.length === 0 ||
                    this.selectedSystems.some(systemSelected =>
                        system.toLowerCase().includes(systemSelected.toLowerCase())
                    )

                const matchMemory =
                    this.selectedMemories.length === 0 ||
                    this.selectedMemories.some(memory =>
                        storage.toLowerCase().includes(memory.toLowerCase())
                    )

                const matchPrice =
                    price >= this.minPrice &&
                    price <= this.maxPrice

                return matchBrand && matchRange && matchSystem && matchMemory && matchPrice
            })
        },

        paginatedProducts() {
            const start = (this.currentPage - 1) * this.perPage
            const end = start + this.perPage

            return this.filteredProducts.slice(start, end)
        },

        lastPage() {
            return Math.ceil(this.filteredProducts.length / this.perPage) || 1
        }

    },

    created() {
        this.loadCategory()
    },

    watch: {
        '$route.params.id'() {
            this.loadCategory()
            this.clearFilters()
        },

        selectedBrands() {
            this.currentPage = 1
        },

        selectedRanges() {
            this.currentPage = 1
        },

        selectedSystems() {
            this.currentPage = 1
        },

        selectedMemories() {
            this.currentPage = 1
        },

        minPrice() {
            this.currentPage = 1
        },

        maxPrice() {
            this.currentPage = 1
        }
    },

    methods: {

        loadCategory() {

            fetch('/api/categories/' + this.$route.params.id)
                .then(response => response.json())
                .then(data => {

                    this.category = data
                    this.products = data.products ?? []
                    this.currentPage = 1

                })

        },

        clearFilters() {
            this.selectedBrands = []
            this.selectedRanges = []
            this.selectedSystems = []
            this.selectedMemories = []
            this.minPrice = 0
            this.maxPrice = 1000000
            this.currentPage = 1
        },

        changePage(page) {
            if (page < 1 || page > this.lastPage) {
                return
            }

            this.currentPage = page
        }

    }

}
</script>