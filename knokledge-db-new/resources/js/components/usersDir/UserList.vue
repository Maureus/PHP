<template>
    <div>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="users.length" class="flex flex-col">
            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-200 text-xl">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Name
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

        <Confirm :mess="mess"/>
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
                        <label for="email" class="block text-sm font-medium leading-5 text-gray-700">
                            E-mail
                        </label>
                        <input id="email" name="email" v-model="curUser.email"
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
                    <div class="btn-container">
                        <div class="btn-box start">
                            <button type="submit" class="btn">
                                Confirm
                            </button>
                        </div>
                        <div class="btn-box end">
                            <button @click="cancelEditingUserInfo" type="button" class="btn">
                                Cancel
                            </button>
                        </div>
                        <div class="btn-box end">
                            <button @click="deleteUser" type="button" class="btn red">
                                Delete
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import UserListItem from "./UserListItem";
import Preloader from "../Preloader";
import Confirm from "../Confirm";
import {mapGetters, mapActions} from 'vuex';

export default {
    name: "UserList",
    components: {
        UserListItem, Preloader, Confirm
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
            mess: ""
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole"])
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
            this.editUser = true;
            axios.get("http://127.0.0.1:8000/api/users/" + userEditedId)
                .then(value => value.data)
                .then(value => {
                    this.curUser = value;
                })
                .catch(error => this.saveErrors(error));
        },
        cancelEditingUserInfo() {
            this.editUser = false;
            this.curUser = {};
        },
        deleteUser() {
            axios.delete("http://127.0.0.1:8000/api/users/" + this.curUser.id)
                .then(() => {
                    this.users = this.users.filter(user => user.id !== this.curUser.id);
                    this.editUser = false;
                    this.curUser = {};
                    this.mess = "User has been deleted.";
                    this.confirm();
                });
        },
        saveUserChanges() {
            axios.put("http://127.0.0.1:8000/api/users/" + this.curUser.id, this.curUser)
                .then(() => {
                    axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => {
                        this.users = value;
                        this.loading = false;
                    });
                    this.editUser = false;
                    this.curUser = {};
                    this.mess = "User has been changed.";
                    this.confirm();
                });
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

.btn-container {
    display : flex;
}

.btn-box {
    padding-top : 50px;

    &.start {
        text-align : start;
        width      : 60%;
    }

    &.end {
        text-align : end;
        width      : 20%;
    }
}

.btn {
    width            : 100px;
    height           : auto;
    font-size        : 14px;
    margin-bottom    : $margin * 1.5;
    color            : white;
    background-color : #6875f5;

    &.red {
        background-color : #f05252;

        &:hover {
            background-color : #e02424;
        }
    }

    &:hover {
        background-color : #5850ec;
    }

    &:focus {
        outline : none;
    }
}
</style>
