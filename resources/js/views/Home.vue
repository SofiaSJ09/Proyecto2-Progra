<template>
    <section class="home-page">
        <section class="grid align-items-center surface-card border-round-2xl shadow-2 p-4 mb-5">
            <div class="col-12 lg:col-6">
                <div class="p-3">
                    <h1 class="text-5xl font-bold text-900 mt-0 mb-3">
                        Los mejores celulares al mejor precio
                    </h1>

                    <p class="text-lg text-700 line-height-3 mb-4">
                        Descubre nuestra amplia selección de smartphones de las marcas más reconocidas.
                        Calidad garantizada y envíos a todo el país.
                    </p>

                    <router-link to="/" class="no-underline">
                        <Button label="Ver catálogo" icon="pi pi-shopping-bag" />
                    </router-link>
                </div>
            </div>

            <div class="col-12 lg:col-6">
                <div class="carousel-box relative flex align-items-center justify-content-center p-4 border-round-xl surface-100">
                    <Button
                        icon="pi pi-chevron-left"
                        class="carousel-control left"
                        rounded
                        text
                        @click="previousSlide"
                    />

                    <img
                        :src="carouselImages[currentSlide]"
                        alt="Imagen de celular"
                        class="carousel-image"
                    >

                    <Button
                        icon="pi pi-chevron-right"
                        class="carousel-control right"
                        rounded
                        text
                        @click="nextSlide"
                    />

                    <div class="carousel-dots flex gap-2">
                        <button
                            v-for="(image, index) in carouselImages"
                            :key="index"
                            :class="['dot', { active: index === currentSlide }]"
                            @click="currentSlide = index"
                        ></button>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <div class="flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <h2 class="text-3xl font-bold text-900 m-0">
                    Recomendados
                </h2>
            </div>

            <Divider />

            <div v-if="products.length === 0" class="flex flex-column align-items-center justify-content-center p-5">
                <ProgressSpinner />
                <p class="text-700 mt-3">Cargando productos...</p>
            </div>

            <template v-else>
                <div class="grid">
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
                                <router-link :to="'/producto/' + product.id" class="no-underline">
                                    <Button
                                        label="Ver producto"
                                        icon="pi pi-eye"
                                        class="w-full"
                                        severity="secondary"
                                    />
                                </router-link>
                            </template>
                        </Card>
                    </div>
                </div>

                <div v-if="lastPage > 1" class="flex justify-content-center align-items-center gap-2 mt-5 flex-wrap">
                    <Button
                        label="Anterior"
                        icon="pi pi-angle-left"
                        severity="secondary"
                        :disabled="currentPage === 1"
                        @click="loadProducts(currentPage - 1)"
                    />

                    <Button
                        v-for="page in lastPage"
                        :key="page"
                        :label="String(page)"
                        :severity="page === currentPage ? 'primary' : 'secondary'"
                        @click="loadProducts(page)"
                    />

                    <Button
                        label="Siguiente"
                        icon="pi pi-angle-right"
                        iconPos="right"
                        severity="secondary"
                        :disabled="currentPage === lastPage"
                        @click="loadProducts(currentPage + 1)"
                    />
                </div>
            </template>
        </section>

        <section class="grid">
            <div class="col-12 md:col-4">
                <Card class="h-full text-center">
                    <template #header>
                        <img
                            :src="'/images/beneficios/compra_segura.png'"
                            alt="Compra segura"
                            class="benefit-image"
                        >
                    </template>

                    <template #title>
                        Compra segura
                    </template>

                    <template #content>
                        <p class="text-700 line-height-3">
                            Protegemos tus datos y tus compras para que tengas una experiencia segura.
                        </p>
                    </template>
                </Card>
            </div>

            <div class="col-12 md:col-4">
                <Card class="h-full text-center">
                    <template #header>
                        <img
                            :src="'/images/beneficios/garantia.png'"
                            alt="Garantía incluida"
                            class="benefit-image"
                        >
                    </template>

                    <template #title>
                        Garantía incluida
                    </template>

                    <template #content>
                        <p class="text-700 line-height-3">
                            Todos nuestros productos cuentan con garantía directa de la tienda.
                        </p>
                    </template>
                </Card>
            </div>

            <div class="col-12 md:col-4">
                <Card class="h-full text-center">
                    <template #header>
                        <img
                            :src="'/images/beneficios/envio.png'"
                            alt="Envíos a todo el país"
                            class="benefit-image"
                        >
                    </template>

                    <template #title>
                        Envíos a todo el país
                    </template>

                    <template #content>
                        <p class="text-700 line-height-3">
                            Recibe tu celular en cualquier parte de Costa Rica.
                        </p>
                    </template>
                </Card>
            </div>
        </section>
    </section>
</template>

<script>
export default {
    data() {
        return {
            products: [],
            currentSlide: 0,
            autoSlide: null,
            carouselImages: [
                '/images/carousel/iphone.png',
                '/images/carousel/samsung.png',
                '/images/carousel/xiaomi.png'
            ],
            currentPage: 1,
            lastPage: 1
        }
    },

    created() {
        this.loadProducts()
    },

    mounted() {
        this.autoSlide = setInterval(() => {
            this.nextSlide()
        }, 3000)
    },

    beforeUnmount() {
        clearInterval(this.autoSlide)
    },

    methods: {
        loadProducts(page = 1) {
            fetch('/api/products?page=' + page)
                .then(response => response.json())
                .then(data => {
                    this.products = data.data ?? []
                    this.currentPage = data.current_page ?? 1
                    this.lastPage = data.last_page ?? 1
                })
        },

        nextSlide() {
            this.currentSlide++

            if (this.currentSlide >= this.carouselImages.length) {
                this.currentSlide = 0
            }
        },

        previousSlide() {
            this.currentSlide--

            if (this.currentSlide < 0) {
                this.currentSlide = this.carouselImages.length - 1
            }
        }
    }
}
</script>
