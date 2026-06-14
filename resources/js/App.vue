<template>
    <div class="app-wrapper">
        <header class="app-header">
            <div class="app-header-top">
                <router-link to="/" class="flex align-items-center no-underline">
                    <img :src="'/images/logo.png'" alt="Logo G&S" class="app-logo">
                </router-link>

                <div class="app-search">
                    <InputText
                        v-model="searchText"
                        @keyup.enter="searchProducts"
                        placeholder="Buscar celulares, marca, modelo..."
                        class="w-full"
                    />

                    <Button
                        icon="pi pi-search"
                        label="Buscar"
                        @click="searchProducts"
                    />
                </div>

                <div class="app-icons">
                    <router-link to="/favoritos" class="header-icon">
                        <img :src="'/images/navbar/favoritos.svg'" alt="Favoritos">
                    </router-link>

                    <router-link to="/admin/productos" class="header-icon">
                        <img :src="'/images/navbar/perfil.svg'" alt="Perfil">
                    </router-link>

                    <router-link to="/carrito" class="header-icon">
                        <img :src="'/images/navbar/carrito.svg'" alt="Carrito">
                    </router-link>
                </div>
            </div>

            <nav class="app-nav">
                <router-link to="/" class="nav-link">Inicio</router-link>
                <router-link to="/categoria/1" class="nav-link">Alta gama</router-link>
                <router-link to="/categoria/2" class="nav-link">Gama media</router-link>
                <router-link to="/categoria/3" class="nav-link">Gama baja</router-link>
            </nav>
        </header>

        <main class="app-main">
            <router-view />
        </main>

        <footer class="app-footer">
            <div class="grid">
                <div class="col-12 md:col-3">
                    <h4 class="mt-0 mb-3">Sobre G&S</h4>
                    <p class="line-height-3">
                        Tu tienda en línea de confianza para comprar los mejores celulares del mercado.
                        Calidad garantizada y envíos a todo el país.
                    </p>

                    <div class="flex gap-3 mt-3">
                        <a href="#" class="social-link">
                            <img :src="'/images/social/facebook.svg'" alt="Facebook">
                        </a>

                        <a href="#" class="social-link">
                            <img :src="'/images/social/instagram.svg'" alt="Instagram">
                        </a>
                    </div>
                </div>

                <div class="col-12 md:col-3">
                    <h4 class="mt-0 mb-3">Enlaces rápidos</h4>
                    <div class="flex flex-column gap-2">
                        <router-link to="/" class="footer-link">Ver catálogo</router-link>
                        <a href="#" class="footer-link">Sobre nosotros</a>
                        <a href="#" class="footer-link">Garantía</a>
                        <a href="#" class="footer-link">Información de envíos</a>
                    </div>
                </div>

                <div class="col-12 md:col-3">
                    <h4 class="mt-0 mb-3">Contacto</h4>
                    <p>info@techphone.com</p>
                    <p>+506 2222-3333</p>
                    <p>San José, Costa Rica</p>
                </div>

                <div class="col-12 md:col-3">
                    <h4 class="mt-0 mb-3">Información</h4>
                    <div class="flex flex-column gap-2">
                        <a href="#" class="footer-link">Políticas de privacidad</a>
                        <a href="#" class="footer-link">Términos y condiciones</a>
                        <a href="#" class="footer-link">Política de devoluciones</a>
                        <a href="#" class="footer-link">Preguntas frecuentes</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    name: "App",

    data() {
        return {
            searchText: ''
        }
    },

    methods: {
        searchProducts() {
            const text = this.searchText.trim()

            if (text === '') {
                this.$router.push('/')
                return
            }

            this.$router.push({
                path: '/busqueda',
                query: {
                    query: text
                }
            })
        }
    }
}
</script>