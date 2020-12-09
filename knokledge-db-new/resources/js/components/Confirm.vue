<template>
    <div v-if="getShowModalConfirm"
         class="absolute inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
        <div class="flex-column items-center justify-center w-1/5 bg-white border-0 rounded">
            <p class="text-center pt-4 text-lg">{{ mess }}</p>
            <div class="flex items-center pt-4 justify-end w-full pr-2">
                <button @click="confirm"
                        class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-indigo-600 rounded text-xs mb-2">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
    name: "Confirm",
    props: {
        mess: {
            type: String,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getShowModalConfirm"])
    },
    methods: {
        ...mapActions(["confirm"])
    }
}
</script>

<style scoped>

</style>
