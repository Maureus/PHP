<template>
    <div class="pt-4">
        <h1>Rand user</h1>
        <p>ID is {{ $route.params.user_id }}</p>
        <button @click="goHome">Go Home</button>
    </div>
</template>

<script>
export default {
    watch: {
        '$route'(to, from) {
            // alert(to.params.user_id);
        }
    },
    created() {
        // alert(this.$route.params.user_id);
    },
    methods: {
        goHome() {
            this.$router.push('/');
        }
    }
}
</script>

<style scoped>

</style>
