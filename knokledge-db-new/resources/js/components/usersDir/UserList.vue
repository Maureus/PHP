<template>
    <div>
        <div class="search-container">
            <div class="search-label">
                <label for="searchArea" @click="isShownSearchArea = !isShownSearchArea"
                       :title="isShownSearchArea ? 'click to hide search field' : 'click to start search'">
                    <i class="fas fa-search"></i> Search
                </label>
            </div>
            <transition name="fade">
                <div class="search-field" v-if="isShownSearchArea">
                    <textarea id="searchArea" v-model="searchAreaText" placeholder="Start typing..."></textarea>
                </div>
            </transition>
        </div>

        <UserTable v-if="searchedList.length && searchAreaText.length !== 0"
                   :userList="searchedList" @edit-user="editUserData"/>

        <div v-if="adminsList.length">
            <h1 class="p-2 text-2xl text-white font-semibold">Admins' list</h1>
            <UserTable :userList="adminsList" @edit-user="editUserData"/>
        </div>

        <div v-if="teachersList.length">
            <h1 class="p-2 text-2xl text-white font-semibold">Teachers' list</h1>
            <UserTable :userList="teachersList" @edit-user="editUserData"/>
        </div>

        <div v-if="studentsList.length">
            <h1 class="p-2 text-2xl text-white font-semibold">Students' list</h1>
            <UserTable :userList="studentsList" @edit-user="editUserData"/>
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
import {mapActions, mapGetters} from 'vuex';
import UserListItem from "./UserListItem";
import UserTable from "./UserTable";
import Confirm from "../Confirm";

export default {
    name: "UserList",
    components: {
        UserListItem, Confirm, UserTable
    },
    data() {
        return {
            users: [],
            curUser: {},
            searchAreaText: "",
            isShownSearchArea: false
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole", "getStudentRole"]),
        searchedList() {
            return this.users.filter(user => user.name.toLowerCase().trim().startsWith(this.searchAreaText.trim().toLowerCase()));
        },
        studentsList() {
            return this.users.filter(user => user.role === this.getStudentRole);
        },
        adminsList() {
            return this.users.filter(user => user.role === this.getAdminRole);
        },
        teachersList() {
            return this.users.filter(user => user.role === this.getTeacherRole);
        }
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => {
            this.users = value;
        });
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        editUserData(userEditedId) {
            axios.get("http://127.0.0.1:8000/api/users/" + userEditedId)
                .then(value => value.data).then(value => {
                this.curUser = value;
            }).catch(error => this.saveErrors(error));
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
$indent : 0.25em;

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

.search-container {
    margin-top : $indent * 2;

    label {
        cursor : pointer;
        color  : white;
    }

    textarea {
        text-indent   : $indent;
        width         : 100%;
        padding       : $indent;
        border-radius : 7px;

        &:focus {
            outline : none;
        }
    }
}

.fade-enter-active, .fade-leave-active {
    transition : opacity 1s;
}

</style>
