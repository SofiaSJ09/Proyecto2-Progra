<template>

    <div>

        <h2>Carrito de Compras</h2>

        <div v-if="products.length === 0">

            Carrito vacío

        </div>

        <div v-for="product in products" :key="product.id">

            <h3>{{ product.name }}</h3>

            <p>
                Cantidad: {{ product.quantity }}
            </p>

            <p>
                ₡{{ product.price }}
            </p>

            <button @click="removeProduct(product.id)">
                Eliminar
            </button>

            <hr>

        </div>

    </div>

</template>

<script>
export default {

    data() {
        return {
            products: []
        }
    },

    created() {
        this.loadCart()
    },

    methods: {

        loadCart() {

            fetch('/api/cart')
                .then(response => response.json())
                .then(data => {

                    this.products = Object.keys(data.cart).map(id => ({
                        id,
                        ...data.cart[id]
                    }))

                })

        },

        removeProduct(id) {

            fetch('/api/cart/remove/' + id, {
                method: 'DELETE'
            })
                .then(() => {

                    this.loadCart()

                })

        }

    }

}
</script>