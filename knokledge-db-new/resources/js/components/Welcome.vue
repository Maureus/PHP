<template>
    <div>
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

export default {
    name: "Welcome",
    components: {
        NavBar
    }
}
</script>

<style scoped>

</style>
