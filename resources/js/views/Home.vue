<template>
    <section class="home-page">

        <div class="home-hero">
            <div class="hero-text">
                <h1>Los mejores celulares al mejor precio</h1>

                <p>
                    Descubre nuestra amplia selección de smartphones de las marcas más reconocidas.
                    Calidad garantizada y envíos a todo el país.
                </p>

                <router-link to="/" class="btn-primary">
                    Ver catálogo
                </router-link>
            </div>

            <div class="hero-carousel">

                <button class="carousel-btn left" @click="previousSlide">
                    ‹
                </button>

                <img
                    :src="carouselImages[currentSlide]"
                    alt="Imagen de celular"
                    class="carousel-image"
                >

                <button class="carousel-btn right" @click="nextSlide">
                    ›
                </button>

                <div class="carousel-dots">
                    <button
                        v-for="(image, index) in carouselImages"
                        :key="index"
                        :class="['dot', { active: index === currentSlide }]"
                        @click="currentSlide = index"
                    ></button>
                </div>

            </div>
        </div>

        <section class="page-container">

            <h2 class="section-title">Recomendados</h2>

            <div v-if="products.length === 0" class="empty-message">
                Cargando productos...
            </div>

            <template v-else>

                <div class="products-grid">

                    <div
                        v-for="product in products"
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
                        @click="loadProducts(currentPage - 1)"
                    >
                        Anterior
                    </button>

                    <button
                        v-for="page in lastPage"
                        :key="page"
                        class="pagination-number"
                        :class="{ active: page === currentPage }"
                        @click="loadProducts(page)"
                    >
                        {{ page }}
                    </button>

                    <button
                        class="pagination-btn"
                        :disabled="currentPage === lastPage"
                        @click="loadProducts(currentPage + 1)"
                    >
                        Siguiente
                    </button>
                </div>

            </template>

        </section>

        <section class="benefits-section">

            <div class="benefit-card">
                <img
                    :src="'/images/beneficios/compra_segura.png'"
                    alt="Compra segura"
                    class="benefit-image"
                >

                <h3>Compra segura</h3>
                <p>
                    Protegemos tus datos y tus compras para que tengas una experiencia segura.
                </p>
            </div>

            <div class="benefit-card">
                <img
                    :src="'/images/beneficios/garantia.png'"
                    alt="Garantía incluida"
                    class="benefit-image"
                >

                <h3>Garantía incluida</h3>
                <p>
                    Todos nuestros productos cuentan con garantía directa de la tienda.
                </p>
            </div>

            <div class="benefit-card">
                <img
                    :src="'/images/beneficios/envio.png'"
                    alt="Envíos a todo el país"
                    class="benefit-image"
                >

                <h3>Envíos a todo el país</h3>
                <p>
                    Recibe tu celular en cualquier parte de Costa Rica.
                </p>
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