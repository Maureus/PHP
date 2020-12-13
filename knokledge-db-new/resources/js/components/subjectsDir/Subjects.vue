<template>
    <div>
        <Confirm :mess="mess"/>
        <h1 class="p-2 text-2xl text-white font-semibold">Subjects</h1>
        <div class="h-full w-1/5 flex flex-col items-start justify-center" style="float: left; font-size: 18px">
            <div class="list-group">
                <button class="list-group-item list-group-item-action" @click="getYearValue($event.target.value)"
                        v-for="(year, id) in years" :key="id" :value="year">{{ year }}
                </button>
            </div>
        </div>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div style="float: left" v-else-if="filteredCourses.length">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Year</th>
                    <th scope="col">Abbreviation</th>
                    <th scope="col"
                        v-if="(getUser != null && (getUser.role === getStudentRole || getUser.role === getAdminRole))">
                        Option
                    </th>
                </tr>
                </thead>
                <tbody>
                <SubjectItem v-for="subject in filteredCourses" :key="subject.id" :subject="subject" :option="option"
                             @assign-course="assignCourse" @edit-course="editSubjectData"/>
                </tbody>
            </table>
        </div>
        <div v-if="editSubject"
             class="absolute inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
            <div class="flex-column items-center justify-center w-2/5 bg-white border-0 rounded">
                <h3 class="text-center pt-4 text-lg">Edit subject</h3>
                <form @submit.prevent="saveSubjectChanges">
                    <div class="col-span-6 sm:col-span-4">
                        <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                            Subject's name
                        </label>
                        <input id="name" v-model="curSubject.name" name="name" required
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label for="semester" class="block text-sm font-medium leading-5 text-gray-700">
                            Semester
                        </label>
                        <input id="semester" name="semester" v-model="curSubject.semester" required
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label for="year" class="block text-sm font-medium leading-5 text-gray-700">
                            Year
                        </label>
                        <input id="year" name="year" v-model="curSubject.year" required
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label for="short_name" class="block text-sm font-medium leading-5 text-gray-700">
                            Short name
                        </label>
                        <input id="short_name" name="short_name" v-model="curSubject.short_name" required
                               class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <label for="subject_desc" class="block text-sm font-medium leading-5 text-gray-700">
                            Subject description
                        </label>
                        <textarea id="subject_desc" name="subject_desc" v-model="curSubject.subject_desc" required
                                  class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </textarea>
                    </div>
                    <div class="btn-container">
                        <div class="btn-box start">
                            <button type="submit" class="btn">
                                Confirm
                            </button>
                        </div>
                        <div class="btn-box end">
                            <button @click="cancelEditingSubjectInfo" type="button" class="btn">
                                Cancel
                            </button>
                        </div>
                        <div class="btn-box end">
                            <button @click="deleteSubject" type="button" class="btn red">
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
import {mapActions, mapGetters} from 'vuex';
import Preloader from "../Preloader";
import SubjectItem from "./SubjectItem";
import Confirm from "../Confirm";

export default {
    name: "Subjects",
    components: {
        Preloader, SubjectItem, Confirm
    },
    data() {
        return {
            subjects: [],
            years: [],
            loading: true,
            btnYearValue: "",
            option: "",
            mess: "",
            editSubject: false,
            curSubject: {},
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        getYearValue(value) {
            this.btnYearValue = value;
        },
        async assignCourse(subjectId) {
            const userId = this.getUser.id;
            await axios.post("http://127.0.0.1:8000/api/users/" + userId + "/subjects/" + subjectId)
                .then(() => {
                    this.mess = "Course has been written.";
                    this.confirm();
                })
                .catch(error => this.saveErrors(error));
        },
        editSubjectData(subjectId) {
            this.editSubject = true;
            axios.get("http://127.0.0.1:8000/api/subjects/" + subjectId)
                .then(value => value.data)
                .then(value => {
                    this.curSubject = value;
                })
                .catch(error => this.saveErrors(error));
        },
        saveSubjectChanges() {
            axios.put("http://127.0.0.1:8000/api/subjects/" + this.curSubject.id, this.curSubject)
                .then(() => {
                    axios.get("http://127.0.0.1:8000/api/subjects").then(resp => resp.data).then(value => {
                        this.subjects = value;
                        this.loading = false;
                    });
                    this.editSubject = false;
                    this.curSubject = {};
                    this.mess = "User has been changed.";
                    this.confirm();
                });
        },
        cancelEditingSubjectInfo() {
            this.editSubject = false;
            this.curSubject = {};
        },
        deleteSubject() {
            axios.delete("http://127.0.0.1:8000/api/subjects/" + this.curSubject.id)
                .then(() => {
                    this.subjects = this.subjects.filter(subject => subject.id !== this.curSubject.id);
                    this.editSubject = false;
                    this.curSubject = {};
                    this.mess = "Subject has been deleted.";
                    this.confirm();
                });
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/subjects")
            .then(resp => resp.data)
            .then(value => {
                this.subjects = value;
                this.loading = false;
            })
            .catch(errors => this.saveErrors(errors));

        for (let i = 2020; i >= 2018; i--) {
            this.years.push(i + '/' + (i + 1));
        }
        if (this.getUser != null) {
            switch (this.getUser.role) {
                case this.getStudentRole :
                    this.option = this.getWriteOperation;
                    break;
                case this.getAdminRole :
                    this.option = this.getEditOperation;
                    break;
            }
        }
    },
    computed: {
        ...mapGetters(["getStudentRole", "getStudentRole", "getAdminRole", "getWriteOperation", "getEditOperation", "getUser"]),
        //TODO add more exact year and month filter
        filteredCourses() {
            return this.subjects.filter(value => value.created_at.split("-")[0] === this.btnYearValue.split("/")[0]);
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$fontSize        : 18px;
$hoverColor      : #dde9f5;
$backgroundColor : white;
$margin          : 10px;

.table-container {
    text-align       : center;
    display          : table;
    background-color : $backgroundColor;
    color            : black;
    font-size        : $fontSize;
    border-radius    : 7px;
    overflow         : hidden;
    border-collapse  : collapse;

    tr {
        margin      : 5px 0;
        line-height : 2.1875em;

        &:nth-child(odd) {
            background-color : $backgroundColor;
        }

        &:nth-child(even) {
            background-color : darken($color : $backgroundColor, $amount : 5%);
        }

        &:hover {
            background-color : darken($color : $hoverColor, $amount : 2%);
        }
    }

    th {
        padding          : 5px 10px;
        color            : white;
        background-color : darken($color : #187fe2, $amount : 3%);
    }
}

button {
    &:focus {
        outline : none;
    }
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
}
</style>
