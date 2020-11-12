<template>
    <div class="flex flex-wrap w-full justify-center items-center pt-56">
        <div class="flex flex-wrap max-w-xl">
            <div class="p-2 text-2xl text-white font-semibold"><h1>Login to your account</h1></div>
            <div class="p-2 w-full">
                <label class="text-white" for="email">Your e-mail:</label>
                <input id="email" class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2" placeholder="Email" type="email" v-model="user.email">
            </div>
            <div class="p-2 w-full">
                <label class="text-white" for="password">Password:</label>
                <input id="password" class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2" placeholder="Password" type="password" v-model="user.password" name="password">
            </div>
            <div class="p-2 w-full mt-4">
                <button @click.prevent="login" type="submit" class="flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Login</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",
    data: () => ({
        user:{
             email: '',
             password: ''
        }
    }),
    methods:{
        login() {
            this.$store.dispatch('loginUser', this.user)
                .then(() => this.$router.push({name: "Dashboard"}));
        }
    },
}
</script>

<style scoped>

</style>
