<template>
    <div>
        <preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="users.length" class="flex flex-col">
            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-200 text-xl">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Contact info
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Date of registration
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th v-if="getUser != null && getUser.role === getAdminRole"
                                class="px-6 py-3 bg-gray-50"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <UserListItem v-for="user in users" :key="user.id" :user="user" @edit-user="editUserData"/>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-if="editUser"
             class="absolute inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
            <div class="flex-column items-center justify-center w-2/5 bg-white border-0 rounded">
                <h3 class="text-center pt-4 text-lg">Edit profile</h3>
                <form @submit.prevent="saveUserChanges">
                    <div class="col-span-6 sm:col-span-4">
                        <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                            Your name
                        </label>
                        <input id="name" v-model="curUser.name" name="name" required
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label for="avatar" class="block text-sm font-medium leading-5 text-gray-700">
                            Choose a new avatar
                        </label>
                        <input id="avatar" v-model="curUser.avatar" name="avatar"
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label for="phone" class="block text-sm font-medium leading-5 text-gray-700">
                            Phone number
                        </label>
                        <input id="phone" v-model="curUser.phone" name="phone"
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label for="address" class="block text-sm font-medium leading-5 text-gray-700">
                            Address
                        </label>
                        <input id="address" v-model="curUser.address" name="address"
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="flex items-center pt-4 justify-start w-full pr-2">
                        <button @click="" type="submit"
                                class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                                    focus:outline-none hover:bg-indigo-600 rounded text-xs">
                            Confirm
                        </button>
                    </div>
                </form>
                <div class="flex items-center pt-4 justify-end w-full pr-2">
                    <button @click="cancelEditingUserInfo"
                            class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                                    focus:outline-none hover:bg-indigo-600 rounded text-xs">
                        Cancel
                    </button>
                    <button @click="deleteUser"
                            class="flex items-center justify-center text-white bg-red-500 border-0 py-1 px-2
                                    focus:outline-none hover:bg-red-600 rounded text-xs">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserListItem from "./UserListItem";
import Preloader from "../Preloader";
import {mapGetters, mapActions} from 'vuex';

export default {
    name: "UserList",
    components: {
        UserListItem, Preloader
    },
    data() {
        return {
            users: [],
            loading: true,
            editUser: false,
            curUser: {
                name: '',
                email: '',
                avatar: '',
                phone: '',
                address: '',
                id: ''
            },
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole"])
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => {
            this.users = value;
            console.log(value)
            this.loading = false;
        });
    },
    methods: {
        ...mapActions(["saveErrors"]),
        editUserData(userEditedId) {
            this.editUser = true;
            axios.get("http://127.0.0.1:8000/api/users/" + userEditedId)
                .then(value => value.data)
                .then(value => {
                    const user = value;
                    console.log(user);
                    this.curUser.name = user.name;
                    this.curUser.phone = user.phone;
                    this.curUser.address = user.address;
                })
                .catch(error => this.saveErrors(error));
        },
        cancelEditingUserInfo() {
            this.editUser = false;
            this.curUser.name = "";
            this.curUser.phone = "";
            this.curUser.address = "";
        },
        deleteUser() {

        },
        saveUserChanges() {

        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$margin : 10px;

table {
    margin-top      : $margin;
    margin-bottom   : $margin;
    overflow        : hidden;
    border-collapse : collapse;
    border-radius   : 10px;
}

input {
    margin-bottom : $margin;
    margin-top    : 0;
}

form {
    margin-left  : $margin * 2;
    margin-right : $margin * 2;
}

button {
    width         : 100px;
    height        : auto;
    font-size     : 15px;
    margin-bottom : $margin * 1.5;
    margin-right  : $margin;
    margin-left   : $margin;
}
</style>
