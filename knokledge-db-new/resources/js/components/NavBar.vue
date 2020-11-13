<template>
    <div class="flex py-4 border-b border-white-300">
        <div class="container mx-auto flex items-center justify-between">
            <div v-if="!getUser" class="flex">
                <router-link class="mr-4 text-white" to="/" exact>Home</router-link>
                <router-link class="mr-4 text-white" to="/about">About</router-link>
                <router-link class="mr-4 text-white" to="/rand/10">User 10</router-link>
                <router-link class="mr-4 text-white" to="/rand/12">User 12</router-link>
            </div>
            <div v-if="getUser" class="flex">
                <router-link class="mr-4 text-white" to="/dashboard" exact>Dashboard</router-link>
                <router-link class="mr-4 text-white" to="/main">Main</router-link>
                <router-link class="mr-4 text-white" to="/mycourses">My courses</router-link>
            </div>
            <div v-if="!getUser" class="flex">
                <router-link class="mr-4 text-white" to="/login" exact>Login</router-link>
                <router-link class="mr-4 text-white" to="/register">Register</router-link>
            </div>
            <div v-if="getUser" class="flex">
                <router-link class="mr-4 text-white" to="/profile" exact>{{ getUser.name }}'s profile</router-link>
                <button class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-sm-1 px-4 rounded" @click.prevent="logout">
                    Logout
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions, mapState} from 'vuex';

export default {
    name: "NavBar",
    computed: {
        ...mapGetters(["getUser"]),
        ...mapState(['user', 'errors'])
    },
    watch: {
    },
    methods: {
        logout() {
            this.$store.dispatch('logoutUser')
                .then(() => this.$router.push({name: "Home"}));
        },
    }
}
</script>

<style scoped>

</style>
