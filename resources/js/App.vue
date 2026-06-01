<template>
  <div>
    <h1>Tienda de Celulares</h1>

    <nav>
      <router-link to="/">Inicio</router-link> |
      <router-link to="/carrito">Carrito</router-link> |
      <router-link to="/favoritos">Favoritos</router-link>
    </nav>

    <hr>

    <router-view />
  </div>
</template>

<script>
export default {
  name: "App"
}
</script>