<template>
    <div class="flex flex-col flex-wrap w-full justify-center items-center pt-56">
        <preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
<!--        <div v-if="getErrors" class="flex items-center justify-center">-->
<!--            <p class="text-3xl text-red-800 text-center">{{ getErrors }}</p>-->
<!--        </div>-->

        <div v-if="getUser" class="flex items-center justify-center">
            <div class="pt-4">
                <h3 class="text-2xl text-white">Dashboard </h3>
                <p class="text-white">Name: {{ getUser.name }} </p>
                <p class="text-white">Email: {{ getUser.email }}</p>
                <button class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded"
                        @click.prevent="logout">Logout
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions, mapState} from 'vuex';
import Preloader from "./Preloader";

export default {
    name: "Dashboard",
    components: {Preloader},
    data() {
        return {
            loading: false,
            dropDown: false,
        }
    },
    watch: {
        // call again the method if the route changes
        // '$route': 'fetchData'
        preload: {
            handler: 'fetchData',
            immediate: true
        }
    },
    methods: {
        ...mapActions(['getLoggedInUser']),
        async logout() {
            this.loading = true;
            await this.$store.dispatch('logoutUser')
                .then(() => {
                        this.loading = false;
                        this.$router.push({name: "Home"})
                    }
                );
        },
        async fetchData() {
            if (this.getUser === undefined) {
                this.loading = true;
                await this.getLoggedInUser();
                this.loading = false;
            }
        },
        showDropDown() {
            this.dropDown = !this.dropDown;
        },
    },
    computed: {
        ...mapGetters(['getUser', 'getErrors']),
        ...mapState(['user', 'errors'])
    },
}

</script>

<style scoped>

</style>

// v-for="todo in allTodos" :key="todo.id"
