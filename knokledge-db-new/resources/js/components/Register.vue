<template>
    <div class="flex flex-wrap w-full justify-center items-center pt-56">
        <div class="flex flex-wrap max-w-xl">
            <div class="form-container">
                <div class="p-2 text-2xl text-white font-semibold">
                    <h1>Register an account</h1>
                </div>
                <form @submit.prevent="saveForm">
                    <div class="p-2 w-full">
                        <label class="w-full text-white" for="name">Name:</label>
                        <span class="w-full text-red-500" v-if="errors.name">{{ errors.name[0] }}</span>
                        <input id="name" placeholder="Name" type="text" v-model="form.name" required maxlength="255"
                               class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2"/>
                    </div>
                    <div class="p-2 w-full">
                        <label class="text-white" for="email">Your e-mail:</label>
                        <input id="email" placeholder="Email" type="email" v-model="form.email" required maxlength="255"
                               pattern="^[a-zA-Z][a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.(),:;<>[\]]*@[a-zA-Z.]+.[a-zA-Z]{2,4}$"
                               class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2"/>
                    </div>
                    <div class="p-2 w-full">
                        <label class="text-white" for="password">Password:</label>
                        <input id="password" placeholder="Password" type="password" v-model="form.password"
                               name="password" required minlength="6"
                               class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2"/>
                    </div>
                    <div class="p-2 w-full">
                        <label class="text-white" for="confirm_password">Confirm Password:</label>
                        <input id="confirm_password" name="password_confirmation" placeholder="Confirm Password"
                               type="password" minlength="6"
                               class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2"
                               v-model="form.password_confirmation" required/>
                    </div>
                    <div class="p-2 w-full mt-4">
                        <button type="submit"
                                class="flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex';

export default {
    name: "Register",
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            errors: []
        }
    },
    methods: {
        ...mapActions(['registerUser']),
        async saveForm() {
            await this.registerUser(this.form).then(() => {
                this.$router.push({name: "Login"});
            });
        }
    },
    watch: {
        // things that vue watches change
        // validate here
    }
}
</script>

<style scoped="scoped" lang="scss">
.form-container {
    width     : 500px;
    transform : translateY(-30%);
}
</style>
