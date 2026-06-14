<template>
    <section class="page-container">
        <div class="main-panel">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="page-title m-0">
                        {{ category.name }}
                    </h2>
                    <p class="text-600 mt-2 mb-0">
                        Filtra los productos según marca, gama, sistema, memoria o precio.
                    </p>
                </div>

                <Button
                    label="Limpiar filtros"
                    icon="pi pi-filter-slash"
                    severity="secondary"
                    @click="clearFilters"
                />
            </div>

            <Divider />

            <div class="grid align-items-start">
                <aside class="col-12 lg:col-3">
                    <Card class="filters-prime-card">
                        <template #title>
                            <div class="flex align-items-center gap-2">
                                <i class="pi pi-filter"></i>
                                <span>Filtros</span>
                            </div>
                        </template>

                        <template #content>
                            <div class="flex flex-column gap-4">
                                <div v-if="!isBrandCategory">
                                    <h4 class="filter-title">Marca</h4>

                                    <div class="flex flex-column gap-3">
                                        <div
                                            v-for="brand in brands"
                                            :key="brand"
                                            class="field-checkbox m-0"
                                        >
                                            <Checkbox
                                                v-model="selectedBrands"
                                                :inputId="'brand-' + brand"
                                                :value="brand"
                                            />
                                            <label :for="'brand-' + brand">
                                                {{ brand }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="filter-title">Gama</h4>

                                    <div class="flex flex-column gap-3">
                                        <div
                                            v-for="range in ranges"
                                            :key="range"
                                            class="field-checkbox m-0"
                                        >
                                            <Checkbox
                                                v-model="selectedRanges"
                                                :inputId="'range-' + range"
                                                :value="range"
                                            />
                                            <label :for="'range-' + range">
                                                {{ range }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="filter-title">Sistema Operativo</h4>

                                    <div class="flex flex-column gap-3">
                                        <div
                                            v-for="system in systems"
                                            :key="system"
                                            class="field-checkbox m-0"
                                        >
                                            <Checkbox
                                                v-model="selectedSystems"
                                                :inputId="'system-' + system"
                                                :value="system"
                                            />
                                            <label :for="'system-' + system">
                                                {{ system }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="filter-title">Memoria</h4>

                                    <div class="flex flex-column gap-3">
                                        <div
                                            v-for="memory in memories"
                                            :key="memory.value"
                                            class="field-checkbox m-0"
                                        >
                                            <Checkbox
                                                v-model="selectedMemories"
                                                :inputId="'memory-' + memory.value"
                                                :value="memory.value"
                                            />
                                            <label :for="'memory-' + memory.value">
                                                {{ memory.label }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="filter-title">Precio</h4>

                                    <div class="formgrid grid">
                                        <div class="field col-12">
                                            <label for="minPrice">Mínimo</label>
                                            <InputNumber
                                                id="minPrice"
                                                v-model="minPrice"
                                                inputClass="w-full"
                                                class="w-full"
                                                :min="0"
                                            />
                                        </div>

                                        <div class="field col-12">
                                            <label for="maxPrice">Máximo</label>
                                            <InputNumber
                                                id="maxPrice"
                                                v-model="maxPrice"
                                                inputClass="w-full"
                                                class="w-full"
                                                :min="0"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Card>
                </aside>

                <div class="col-12 lg:col-9">
                    <Message
                        v-if="filteredProducts.length === 0"
                        severity="info"
                        :closable="false"
                    >
                        No hay productos que coincidan con los filtros seleccionados.
                    </Message>

                    <template v-else>
                        <div class="grid">
                            <div
                                v-for="product in paginatedProducts"
                                :key="product.id"
                                class="col-12 md:col-6 xl:col-4"
                            >
                                <Card class="h-full product-card">
                                    <template #header>
                                        <div class="product-image-box">
                                            <img
                                                v-if="product.image"
                                                :src="product.image"
                                                :alt="product.name"
                                                class="product-image"
                                            >

                                            <div v-else class="image-placeholder">
                                                Imagen
                                            </div>
                                        </div>
                                    </template>

                                    <template #title>
                                        <span class="text-xl">
                                            {{ product.name }}
                                        </span>
                                    </template>

                                    <template #subtitle>
                                        <span class="text-primary font-semibold">
                                            {{ product.brand }}
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="flex flex-column gap-2">
                                            <div class="flex justify-content-between text-700">
                                                <span>{{ product.ram }}</span>
                                                <span>{{ product.storage }}</span>
                                            </div>

                                            <div class="flex justify-content-between align-items-center">
                                                <span class="text-2xl font-bold text-900">
                                                    ₡{{ product.price }}
                                                </span>

                                                <span class="text-sm text-600">
                                                    {{ product.operating_system }}
                                                </span>
                                            </div>
                                        </div>
                                    </template>

                                    <template #footer>
                                        <router-link
                                            :to="'/producto/' + product.id"
                                            class="no-underline"
                                        >
                                            <Button
                                                label="Ver producto"
                                                icon="pi pi-eye"
                                                severity="secondary"
                                                class="w-full"
                                            />
                                        </router-link>
                                    </template>
                                </Card>
                            </div>
                        </div>

                        <div
                            v-if="lastPage > 1"
                            class="flex justify-content-center align-items-center gap-2 mt-5 flex-wrap"
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
                                :severity="page === currentPage ? 'primary' : 'secondary'"
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

            brands: [
                'Xiaomi',
                'Samsung',
                'Apple',
                'Redmi',
                'Motorola',
                'Huawei',
                'Realme',
                'Oppo'
            ],

            ranges: [
                'Alta gama',
                'Gama media',
                'Gama baja'
            ],

            systems: [
                'iOS',
                'Android'
            ],

            memories: [
                { label: '64 GB', value: '64' },
                { label: '128 GB', value: '128' },
                { label: '256 GB', value: '256' },
                { label: '512 GB', value: '512' }
            ],

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