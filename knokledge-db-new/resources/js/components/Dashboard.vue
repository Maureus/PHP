<template>
    <div class="flex flex-col flex-wrap w-full justify-center items-center pt-56">
        <div v-if="loading" class="flex items-center justify-center">
            <p class="text-3xl text-red-800 text-center">Loading!</p>
        </div>

        <div v-if="error" class="flex items-center justify-center">
            <p class="text-3xl text-red-800 text-center">{{ error }}</p>
        </div>

        <div v-if="getUser" class="flex items-center justify-center">
            <div class="pt-4">
                Dashboard <br>
                Name: {{getUser.name}} <br>
                Email: {{getUser.email}}<br><br>
                <button class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded" @click.prevent="logout">Logout</button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions, mapState} from 'vuex';

export default {
    name: "Dashboard",
    data(){
        return{
            loading: true,
            logUser: null,
            error: null
        }
    },
    watch: {
        // call again the method if the route changes
        '$route': 'fetchData'
    },
    methods: {
        ...mapActions(['getLoggedInUser']),
        logout() {
            this.$store.dispatch('logoutUser')
                .then(() => this.$router.push({name: "Home"}));
        },
        fetchData() {
            this.error = this.logUser = null;
            this.loading = true;
            this.getLoggedInUser();
            this.loading = false;

        },
    },
    computed: {
        ...mapGetters(["getUser"]),
        ...mapState(['user', 'errors'])
    },
    created () {
        // this.getLoggedInUser();
        this.fetchData();
    },
}

</script>

<style scoped>

</style>

// v-for="todo in allTodos" :key="todo.id"
