<template>
    <div>

        <div v-if="!product">
            Cargando...
        </div>

        <div v-else>

            <h2>{{ product.name }}</h2>

            <p>
                {{ product.description }}
            </p>

            <p>
                Marca:
                {{ product.brand }}
            </p>

            <p>
                RAM:
                {{ product.ram }}
            </p>

            <p>
                Almacenamiento:
                {{ product.storage }}
            </p>

            <p>
                Sistema:
                {{ product.operating_system }}
            </p>

            <p>
                Categoría:
                {{ product.category.name }}
            </p>

            <h3>
                ₡{{ product.price }}
            </h3>

            <button @click="addToCart">
                Agregar al carrito
            </button>

            <br><br>

            <button @click="addToFavorites">
                Agregar a favoritos
            </button>

            <br><br>

            <router-link to="/">
                Volver
            </router-link>

        </div>

    </div>
</template>

<script>
export default {

    data() {
        return {
            product: null
        }
    },

    created() {
        this.loadProduct()
    },

    methods: {

        loadProduct() {

            fetch('/api/products/' + this.$route.params.id)
                .then(response => response.json())
                .then(data => {

                    this.product = data

                })

        },

        addToCart() {

            fetch('/api/cart/add/' + this.product.id, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {

                    console.log('RESPUESTA CART:', data)

                    alert(data.message ?? 'Producto agregado')

                })
                .catch(error => {
                    console.log('ERROR:', error)
                })

        },

        addToFavorites() {

            fetch('/api/favorites/add/' + this.product.id, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {

                    alert(data.message)

                })

        }

    }

}
</script>