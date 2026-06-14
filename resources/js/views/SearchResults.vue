<template>
    <section class="page-container">
        <div class="main-panel">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="page-title m-0">
                        Resultados de búsqueda
                    </h2>

                    <p class="text-600 mt-2 mb-0">
                        Buscando:
                        <strong class="text-primary">{{ searchQuery }}</strong>
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
                v-if="loading"
                class="flex flex-column align-items-center justify-content-center p-5 surface-card border-round shadow-1"
            >
                <ProgressSpinner />
                <p class="text-700 mt-3 mb-0">
                    Cargando resultados...
                </p>
            </div>

            <Message
                v-else-if="products.length === 0"
                severity="info"
                :closable="false"
                class="mb-4"
            >
                No hay celulares relacionados con "{{ searchQuery }}".
            </Message>

            <div
                v-if="!loading && products.length === 0"
                class="flex justify-content-center"
            >
                <router-link to="/" class="no-underline">
                    <Button
                        label="Volver al catálogo"
                        icon="pi pi-shopping-bag"
                    />
                </router-link>
            </div>

            <div v-else-if="!loading" class="grid">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="col-12 sm:col-6 lg:col-4 xl:col-3"
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
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            products: [],
            loading: true
        }
    },

    computed: {
        searchQuery() {
            return this.$route.query.query ?? ''
        }
    },

    created() {
        this.searchProducts()
    },

    watch: {
        '$route.query.query'() {
            this.searchProducts()
        }
    },

    methods: {
        searchProducts() {
            const query = this.searchQuery.trim()

            if (query === '') {
                this.products = []
                this.loading = false
                return
            }

            this.loading = true

            fetch('/api/products/search?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    this.products = data.data ?? data
                    this.loading = false
                })
                .catch(error => {
                    console.log('Error en búsqueda:', error)
                    this.products = []
                    this.loading = false
                })
        }
    }
}
</script>