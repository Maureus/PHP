<template>
    <transition name="fade">
        <div v-if="show" class="preloader">
            <h3 class="text-3xl text-white">Loading...</h3>
            <div class="circles">
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="circle"></div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "Preloader",
    data() {
        return {
            show: true
        }
    },
    mounted() {
        this.showToggle()
    },
    methods: {
        showToggle() {
            // change this to make
            // setTimeout(()=>{
            //     this.show = false
            // }, 1000)
        }
    }
}

</script>

<style scoped>
</style>
