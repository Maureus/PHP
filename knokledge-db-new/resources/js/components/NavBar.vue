<template>
    <div class="flex py-4 border-b border-white-300">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex">
                <router-link class="mr-4 text-white" to="/">Home</router-link>
                <router-link v-if="getUser" class="mr-4 text-white" to="/dashboard" exact>Dashboard</router-link>
                <router-link v-if="getUser" class="mr-4 text-white" to="/userlist">All users</router-link>
                <router-link class="mr-4 text-white" to="/subjectlist">All subjects</router-link>
                <router-link v-if="getUser != null && getUser.role !== getAdminRole" class="mr-4 text-white"
                             to="/mysubjects">My subjects
                </router-link>
                <!--                <router-link v-if="getUser != null && getUser.role === getTeacherRole" class="mr-4 text-white"-->
                <!--                             to="/mystudents">My students-->
                <!--                </router-link>-->
                <router-link class="mr-4 text-white" to="/about">About</router-link>
            </div>
            <div v-if="!getUser" class="flex">
                <router-link class="mr-4 text-white" to="/login" exact>Login</router-link>
                <router-link class="mr-4 text-white" to="/register">Register</router-link>
            </div>
            <div v-if="getUser" class="flex">
                <button v-if="getAdminId!=null"
                        class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-sm-1 px-4 rounded mr-4"
                        @click.prevent="cancelEmu">
                    Cancel emulation
                </button>
                <router-link class="mr-4 text-white" to="/profile" exact>{{ getUser.name }}'s profile</router-link>
                <button class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-sm-1 px-4 rounded"
                        @click.prevent="logout">
                    Logout
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapState, mapActions} from 'vuex';

export default {
    name: "NavBar",
    data() {
        return {
            loading: true
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole", "getStudentRole", "getAdminId"]),
        ...mapState(['user', 'errors'])
    },
    methods: {
        ...mapActions(["getLoggedInUser", "cancelEmulation"]),
        logout() {
            this.$store.dispatch('logoutUser').then(() => this.$router.push({name: "Home"}));
        },
        async cancelEmu() {
            await this.cancelEmulation().then(() => {
                this.$router.push({name: "UserList"});
            });
        }
    }
}
</script>

<style scoped>

</style>
