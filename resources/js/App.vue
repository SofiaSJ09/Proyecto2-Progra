<template>
    <div class="site-wrapper">
        <header class="site-header">
            <div class="header-top">
                <router-link to="/" class="logo-link">
                    <img :src="'/images/logo.png'" alt="Logo G&S" class="site-logo">
                </router-link>
                <div class="search-box">
    <input
        type="text"
        v-model="searchText"
        @keyup.enter="searchProducts"
        placeholder="Buscar celulares, marca, modelo..."
    >
</div>
             <div class="header-icons">
    <router-link to="/favoritos" class="header-icon">
        <img :src="'/images/navbar/favoritos.svg'" alt="Favoritos">
    </router-link>

    <span class="header-icon">
        <img :src="'/images/navbar/perfil.svg'" alt="Perfil">
    </span>

    <router-link to="/carrito" class="header-icon">
        <img :src="'/images/navbar/carrito.svg'" alt="Carrito">
    </router-link>
</div>
            </div>
            <nav class="main-nav">
                <router-link to="/">Inicio</router-link>
                <router-link to="/categoria/1">Alta gama</router-link>
                <router-link to="/categoria/2">Gama media</router-link>
                <router-link to="/categoria/3">Xiaomi</router-link>
                <router-link to="/categoria/4">Samsung</router-link>
                <router-link to="/categoria/5">Apple</router-link>
                <router-link to="/categoria/6">Redmi</router-link>
            </nav>
        </header>
        <main>
            <router-view />
        </main>
        <footer class="site-footer">
            <div class="footer-column">
                <h4>Sobre G&S</h4>
                <p>
                    Tu tienda en línea de confianza para comprar los mejores celulares del mercado.
                    Calidad garantizada y envíos a todo el país.
                </p>
                <div class="social-links">
    <a href="#" class="social-link">
        <img :src="'/images/social/facebook.svg'" alt="Facebook">
    </a>

    <a href="#" class="social-link">
        <img :src="'/images/social/instagram.svg'" alt="Instagram">
    </a>
</div>
            </div>
            <div class="footer-column">
                <h4>Enlaces rápidos</h4>
                <router-link to="/">Ver catálogo</router-link>
                <a href="#">Sobre nosotros</a>
                <a href="#">Garantía</a>
                <a href="#">Información de envíos</a>
            </div>
            <div class="footer-column">
                <h4>Contacto</h4>
                <p>info@techphone.com</p>
                <p>+506 2222-3333</p>
                <p>San José, Costa Rica</p>
            </div>
            <div class="footer-column">
                <h4>Información</h4>
                <a href="#">Políticas de privacidad</a>
                <a href="#">Términos y condiciones</a>
                <a href="#">Política de devoluciones</a>
                <a href="#">Preguntas frecuentes</a>
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
                path: '/',
                query: {
                    search: text
                }
            })
        }
    }
}
</script>