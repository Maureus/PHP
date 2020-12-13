<template>
    <div>
        <preloader v-if="getPreloader" class="absolute inset-0 flex items-center justify-center"/>
        <navbar-component/>
        <transition name="page" mode="out-in">
            <slot>
                <router-view/>
            </slot>
        </transition>
    </div>
</template>

<script>
import NavBar from "./NavBar";
import Preloader from "./Preloader";
import {mapGetters} from 'vuex';

export default {
    name: "Welcome",
    components: {
        NavBar,
        Preloader
    },
    methods: {
    },
    computed: {
        ...mapGetters(['getPreloader'])
    },
}
</script>

<style scoped>

</style>
