<template>
    <div v-if="show" class="modal-overlay">
        <div class="app-modal">

            <div class="modal-icon" :class="type">
                <span v-if="type === 'success'">✓</span>
                <span v-else>!</span>
            </div>

            <h2>{{ title }}</h2>

            <p>{{ message }}</p>

            <div class="modal-actions">
                <button
                    v-if="showCancel"
                    class="modal-btn modal-btn-cancel"
                    @click="$emit('cancel')"
                >
                    Cancelar
                </button>

                <button
                    class="modal-btn"
                    :class="type === 'success' ? 'modal-btn-success' : 'modal-btn-danger'"
                    @click="$emit('confirm')"
                >
                    {{ confirmText }}
                </button>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false
        },
        type: {
            type: String,
            default: 'success'
        },
        title: {
            type: String,
            default: ''
        },
        message: {
            type: String,
            default: ''
        },
        confirmText: {
            type: String,
            default: 'Aceptar'
        },
        showCancel: {
            type: Boolean,
            default: false
        }
    }
}
</script>