<template>
    <div>
        <nav>
            <a class="link" style="padding: 0 5px" href="#teachers">Teachers</a>
            <a class="link" style="padding: 0 5px" href="#students">Students</a>
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

        <h1 id="students" class="pl-2 text-2xl text-white font-semibold">Students' list</h1>
        <div class="select-container">
            <select class="custom-select" v-model="filteredYear" @change="filterByYear">
                <option value="0">All</option>
                <option value="1">1 year</option>
                <option value="2">2 year</option>
                <option value="3">3 year</option>
                <option value="4">4 year</option>
                <option value="5">5 year</option>
            </select>
        </div>
        <div v-if="studentsList.length">
            <UserTable :userList="studentsList" @edit-user="editUserData"/>
            <div class="flex w-100 justify-content-end">
                <button class="btn-primary btn-lg mb-4" style="background-color: #1777d4" data-toggle="modal"
                        data-target="#assignSubjectToStudents">Assign subject to students' group
                </button>
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
        <div class="modal fade" id="assignSubjectToStudents" tabindex="-1" role="dialog"
             aria-labelledby="assignSubjectToStudentsModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignSubjectToStudentsModalCenterTitle">
                            Assign subject(-s) to students
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="subjects" class="block text-sm font-medium leading-5 text-gray-700">
                                Subjects
                            </label>
                            <select id="subjects" v-model="chosenSubjectId"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option v-for="(subject, index) in subjects" :key="subject.id" :value="subject.id">
                                    {{ subject.name }}, {{ subject.year }} {{ subject.semester }}
                                </option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="years" class="block text-sm font-medium leading-5 text-gray-700">
                                Students study year
                            </label>
                            <select id="years" v-model="chosenYear"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option v-for="year in years" :key="year" :value="year">
                                    {{ year }} year
                                </option>
                            </select>
                        </div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start">
                                <button @click="assignSubjectToStudentsGroup" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button data-dismiss="modal" class="btn">
                                    Cancel
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="unsubscribeSubject" data-dismiss="modal" class="btn red">
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
import subjects from "../../store/modules/subjects";

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
            isShownSearchArea: false,
            filteredYear: 0,
            studentsList: [],
            adminsList: [],
            teachersList: [],
            subjects: [],
            years: [1, 2, 3, 4, 5],
            chosenSubjectId: "",
            chosenYear: "1"
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole", "getStudentRole", "getAdminId"]),
        searchedList() {
            return this.users.filter(user => user.name.toLowerCase().trim().startsWith(this.searchAreaText.trim().toLowerCase()));
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => this.users = value);
        await axios.get("http://127.0.0.1:8000/api/subjects").then(resp => resp.data).then(value => this.subjects = value);
        if (this.subjects.length) {
            this.chosenSubjectId = this.subjects[0].id;
        }
        this.studentsList = this.users.filter(user => user.role === this.getStudentRole);
        this.adminsList = this.users.filter(user => user.role === this.getAdminRole);
        this.teachersList = this.users.filter(user => user.role === this.getTeacherRole);
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        assignSubjectToStudentsGroup() {
            const filteredUsersByYear = this.users.filter(user => user.year == this.chosenYear && user.role === this.getStudentRole);

            filteredUsersByYear.forEach(user => {
                axios.get("http://127.0.0.1:8000/api/users/" + user.id + "/subjects").then(resp => {
                    let index = 0
                    for (let j = 0; j < resp.data.length; j++) {
                        if (this.chosenSubjectId === resp.data[j].id) {
                            index++;
                        }
                    }
                    if (index == 0) {
                        axios.post("http://127.0.0.1:8000/api/users/" + user.id + "/subjects/" + this.chosenSubjectId);
                    } else {
                        console.log("Subject was written.");
                    }
                });
            });
        },
        unsubscribeSubject() {
            const filteredUsersByYear = this.users.filter(user => user.year == this.chosenYear && user.role === this.getStudentRole);
            filteredUsersByYear.forEach(user => {
                axios.get("http://127.0.0.1:8000/api/users/" + user.id + "/subjects").then(resp => {
                    let index = 0
                    for (let i = 0; i < resp.data.length; i++) {
                        if (this.chosenSubjectId === resp.data[i].id) {
                            index++;
                        }
                    }
                    if (index == 0) {
                        console.log("User doesn't have this subject.");
                    } else {
                        axios.delete("http://127.0.0.1:8000/api/users/" + user.id + "/subjects/" + this.chosenSubjectId);
                    }
                });
            });
        },
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
        },
        filterByYear() {
            if (parseInt(this.filteredYear) === 0) {
                this.studentsList = this.users.filter(user => user.role === this.getStudentRole);
            } else {
                this.studentsList = this.users.filter(user =>
                    user.year === this.filteredYear && user.role === this.getStudentRole);
            }
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent : 0.25em;

@import "./resources/sass/form_util_btns";
@import "./resources/sass/routerlink";
@import "./resources/sass/hover_effects";

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

input, select {
    margin-bottom : $indent * 2;
    margin-top    : 0;
}

.fade-enter-active, .fade-leave-active {
    transition : opacity 1s;
}

</style>
