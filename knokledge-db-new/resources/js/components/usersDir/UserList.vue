<template>
    <div>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="users.length" class="flex flex-col">
            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200 text-xl">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th v-if="getUser.role===getAdminRole && getAdminId==null" class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Emulate
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Contact info
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Date of registration
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
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
        </div>

        <Confirm/>

        <div class="modal fade" id="userEditModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                Your name
                            </label>
                            <input id="name" v-model="curUser.name" name="name" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="email" class="block text-sm font-medium leading-5 text-gray-700">
                                E-mail
                            </label>
                            <input id="email" name="email" v-model="curUser.email"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="phone" class="block text-sm font-medium leading-5 text-gray-700">
                                Phone number
                            </label>
                            <input id="phone" v-model="curUser.phone" name="phone"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="address" class="block text-sm font-medium leading-5 text-gray-700">
                                Address
                            </label>
                            <input id="address" v-model="curUser.address" name="address"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="rolePicker" class="block text-sm font-medium leading-5 text-gray-700">
                                Change role
                            </label>
                            <select id="rolePicker" v-model="curUser.role"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option>student</option>
                                <option>teacher</option>
                                <option>admin</option>
                            </select>
                        </div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start">
                                <button @click="saveUserChanges" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="cancelEditingUserInfo" data-dismiss="modal" class="btn">
                                    Cancel
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="deleteUser" data-dismiss="modal" class="btn red">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserListItem from "./UserListItem";
import Preloader from "../Preloader";
import Confirm from "../Confirm";
import {mapActions, mapGetters} from 'vuex';

export default {
    name: "UserList",
    components: {
        UserListItem, Preloader, Confirm
    },
    data() {
        return {
            users: [],
            loading: true,
            curUser: {}
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getAdminId"])
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => {
            this.users = value;
            this.loading = false;
        });
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        editUserData(userEditedId) {
            axios.get("http://127.0.0.1:8000/api/users/" + userEditedId)
                .then(value => value.data)
                .then(value => {
                    this.curUser = value;
                })
                .catch(error => this.saveErrors(error));
        },
        cancelEditingUserInfo() {
            this.curUser = {};
        },
        deleteUser() {
            axios.delete("http://127.0.0.1:8000/api/users/" + this.curUser.id)
                .then(() => {
                    this.users = this.users.filter(user => user.id !== this.curUser.id);
                    this.curUser = {};
                    this.confirm();
                });
        },
        saveUserChanges() {
            axios.put("http://127.0.0.1:8000/api/users/" + this.curUser.id, this.curUser)
                .then(() => {
                    axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => {
                        this.users = value;
                    });
                    this.curUser = {};
                    this.confirm();
                });
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$margin : 10px;

@import "./resources/sass/form_util_btns";

.max {
    min-height : 100%;
    min-width  : min-content;
}

table {
    text-align      : center;
    margin-top      : $margin;
    margin-bottom   : $margin;
    border-radius   : 10px;
    overflow        : hidden;
    border-collapse : collapse;
}

input {
    margin-bottom : $margin;
    margin-top    : 0;
}
</style>
