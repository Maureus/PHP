<template>
    <div class="flex flex-wrap w-full justify-center items-center pt-56">
        <div class="flex flex-wrap max-w-xl">
            <div class="form-container">
                <div class="p-2 text-2xl text-white font-semibold">
                    <h1>Login to your account</h1>
                </div>
                <form @submit.prevent="login">
                    <div class="p-2 w-full">
                        <label class="text-white" for="email">Your e-mail:</label>
                        <input id="email" type="email" v-model="user.email" placeholder="Email" required maxlength="255"
                               pattern="^[a-zA-Z][a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.(),:;<>[\]]*@[a-zA-Z.]+.[a-zA-Z]{2,4}$"
                               class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2">
                    </div>
                    <div class="p-2 w-full">
                        <label class="text-white" for="password">Password:</label>
                        <input id="password" type="password" v-model="user.password" placeholder="Password" required
                               class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2">
                    </div>
                    <div class="p-2 w-full mt-4">
                        <input type="submit" value="Login"
                               class="flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex';

export default {
    name: "Login",
    data: () => ({
        user: {
            email: '',
            password: ''
        }
    }),
    methods: {
        ...mapActions(['loginUser', 'getLoggedInUser']),
        login() {
            this.loginUser(this.user)
                .then(() => this.getLoggedInUser())
                .then(() => this.$router.push({name: "Dashboard"}));
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
.form-container {
    width     : 500px;
    transform : translateY(-30%);
}
</style>
