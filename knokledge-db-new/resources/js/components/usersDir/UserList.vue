<template>
    <div>
        <preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-if="users.length" class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
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
            <div class="flex-column items-center justify-center w-1/5 bg-white border-0 rounded">
                <p class="text-center pt-4 text-lg">Edit user data</p>
                <div class="flex items-center pt-4 justify-end w-full pr-2">
                    <button @click=""
                            class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-indigo-600 rounded text-xs mb-2">
                        Confirm
                    </button>
                    <button @click="this.editUser = !this.editUser"
                            class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-indigo-600 rounded text-xs mb-2">
                        Cancel
                    </button>
                    <button @click="deleteUser"
                            class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-indigo-600 rounded text-xs mb-2">
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
import {mapGetters} from 'vuex';

export default {
    name: "UserList",
    components: {
        UserListItem, Preloader
    },
    data() {
        return {
            users: [],
            loading: true,
            editUser: false
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole"])
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => {
            this.users = value;
            this.loading = false;
        });
    },
    methods: {
        editUserData(userEditedId) {
            this.editUser = true;
        },
        deleteUser() {

        }
    }
}
</script>

<style scoped>
table {
    margin-top      : 10px;
    margin-bottom   : 10px;
    overflow        : hidden;
    border-collapse : collapse;
    border-radius   : 10px;
}
</style>
