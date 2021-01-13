<template>
    <div>
        <nav>
            <a class="link" href="#teachers">Teachers</a>
            <a class="link" href="#students">Students</a>
        </nav>
        <SearchField @search-area-text="setSearchAreaText"/>

        <transition name="fade">
            <UserTable v-if="searchedList.length && searchAreaText.length !== 0"
                       :userList="searchedList" @edit-user="editUserData"/>
        </transition>

        <div v-if="adminsList.length">
            <h1 class="pl-2 text-2xl text-white font-semibold">Admins' list</h1>
            <UserTable :userList="adminsList" @edit-user="editUserData"/>
        </div>

        <div v-if="teachersList.length">
            <h1 id="teachers" class="pl-2 text-2xl text-white font-semibold">Teachers' list</h1>
            <UserTable :userList="teachersList" @edit-user="editUserData"/>
        </div>

        <div v-if="studentsList.length">
            <h1 id="students" class="pl-2 text-2xl text-white font-semibold">Students' list</h1>
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
                            <input id="address" v-model="curUser.address"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div v-if="curUser.role === getStudentRole" class="col-span-6 sm:col-span-4 mx-2">
                            <label for="year" class="block text-sm font-medium leading-5 text-gray-700">
                                Year
                            </label>
                            <input id="year" v-model="curUser.year" type="number" min="1" max="5"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div v-if="curUser.role === getStudentRole || curUser.role === getTeacherRole"
                             class="col-span-6 sm:col-span-4 mx-2">
                            <label for="discipline" class="block text-sm font-medium leading-5 text-gray-700">
                                Discipline
                            </label>
                            <input id="discipline" v-model="curUser.obor"
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
import SearchField from "../SearchField";

export default {
    name: "UserList",
    components: {
        UserListItem, Confirm, UserTable, SearchField
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
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole", "getStudentRole", "getAdminId"]),
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
        axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => this.users = value);
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        editUserData(userEditedId) {
            axios.get("http://127.0.0.1:8000/api/users/" + userEditedId).then(value => value.data).then(value => {
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
        },
        setSearchAreaText(searchAreaText) {
            this.searchAreaText = searchAreaText;
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent : 0.25em;

@import "./resources/sass/form_util_btns";
@import "./resources/sass/routerlink";

.max {
    min-height : 100%;
    min-width  : min-content;
}

table {
    text-align      : center;
    margin-top      : $indent * 2;
    margin-bottom   : $indent * 2;
    border-radius   : 10px;
    overflow        : hidden;
    border-collapse : collapse;
}

input {
    margin-bottom : $indent * 2;
    margin-top    : 0;
}

.fade-enter-active, .fade-leave-active {
    transition : opacity 1s;
}

</style>
