<template>
    <div>
        <Confirm/>
        <h1 class="p-2 text-2xl text-white font-semibold">My subjects</h1>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="userSubjects.length !== 0">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Year</th>
                    <th scope="col">Abbreviation</th>
                    <th scope="col">Option</th>
                </tr>
                </thead>
                <tbody>
                <SubjectItem v-for="subject in userSubjects" :key="subject.id" :subject="subject" :option="option"
                             @edit-course="editCourseData" @delete-course-in-user="deleteCourseInUser"/>
                </tbody>
            </table>
        </div>
        <p v-else class="p-2 text-lg text-white font-semibold">You don't have any subject.
            <span v-if="getUser != null && getUser.role === getStudentRole">
                Click <router-link to="/subjectlist" class="link">here</router-link> to write a subject.
            </span>
        </p>

        <button v-if="getUser != null && getUser.role === getTeacherRole" id="openModalCreateSubjectWindowBtn"
                class="link" data-toggle="modal" data-target="#createSubjectModal">Create new subject
        </button>

        <div class="modal fade" id="createSubjectModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                Subject's name
                            </label>
                            <input id="name" v-model="curSubject.name"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="semester" class="block text-sm font-medium leading-5 text-gray-700">
                                Semester
                            </label>
                            <select id="semester" v-model="curSubject.semester"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option>ZS</option>
                                <option>LS</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="year" class="block text-sm font-medium leading-5 text-gray-700">
                                Year
                            </label>
                            <input id="year" v-model="curSubject.year" type="number" min="1" max="5"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="short_name" class="block text-sm font-medium leading-5 text-gray-700">
                                Short name
                            </label>
                            <input id="short_name" v-model="curSubject.short_name" maxlength="5"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="subject_desc" class="block text-sm font-medium leading-5 text-gray-700">
                                Subject description
                            </label>
                            <textarea id="subject_desc" v-model="curSubject.subject_desc" maxlength="255"
                                      class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </textarea>
                        </div>
                        <div class="warn-mess"><p id="warnMess" class="mess"></p></div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start" style="width: 50%">
                                <button @click="createNewSubject" id="createBtn" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                            <div class="btn-box end" style="width: 50%">
                                <button @click="cancelCreation" data-dismiss="modal" class="btn">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog"
             aria-labelledby="editSubjectModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubjectModalTitle">Edit subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="nameEdit" class="block text-sm font-medium leading-5 text-gray-700">
                                Subject's name
                            </label>
                            <input id="nameEdit" v-model="curSubject.name"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="semesterEdit" class="block text-sm font-medium leading-5 text-gray-700">
                                Semester
                            </label>
                            <select id="semesterEdit" v-model="curSubject.semester"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option>ZS</option>
                                <option>LS</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="yearEdit" class="block text-sm font-medium leading-5 text-gray-700">
                                Year
                            </label>
                            <input id="yearEdit" v-model="curSubject.year" type="number" min="1" max="5"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="shortNameEdit" class="block text-sm font-medium leading-5 text-gray-700">
                                Short name
                            </label>
                            <input id="shortNameEdit" v-model="curSubject.short_name" maxlength="5"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="subjectDesc" class="block text-sm font-medium leading-5 text-gray-700">
                                Subject description
                            </label>
                            <textarea id="subjectDesc" v-model="curSubject.subject_desc" maxlength="255"
                                      class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </textarea>
                        </div>
                        <div class="warn-mess"><p id="warnEditMess" class="mess"></p></div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start">
                                <button @click="saveSubjectChanges" id="editBtn" class="btn btn-primary">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="cancelCreation" data-dismiss="modal" class="btn">
                                    Cancel
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="deleteSubject" data-dismiss="modal" class="btn red">
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
import {mapGetters, mapActions} from 'vuex';
import Preloader from "../Preloader";
import SubjectItem from "./SubjectItem";
import Confirm from "../Confirm";

export default {
    name: "MySubjects",
    components: {
        Preloader, SubjectItem, Confirm
    },
    data() {
        return {
            loading: true,
            userSubjects: [],
            option: "",
            curSubject: {
                name: "",
                semester: "ZS",
                year: 1,
                short_name: "",
                subject_desc: "",
                id: ""
            },
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm", "getLoggedInUser"]),
        editCourseData(subjectId) {
            axios.get("http://127.0.0.1:8000/api/subjects/" + subjectId)
                .then(value => value.data)
                .then(value => {
                    this.curSubject = value;
                })
                .catch(error => this.saveErrors(error));
        },
        deleteCourseInUser(subjectId) {
            const userId = this.getUser.id;
            axios.delete("http://127.0.0.1:8000/api/users/" + userId + "/subjects/" + subjectId)
                .then(async () => {
                    this.userSubjects = this.userSubjects.filter(value => value.id !== subjectId);
                    this.mess = "Course has been deleted.";
                    this.confirm();
                })
                .catch(errors => this.saveErrors(errors));
        },
        async createNewSubject() {
            if (this.curSubject.name.trim() === "" || this.curSubject.short_name.trim() === ""
                || this.curSubject.subject_desc.trim() === "") {
                document.getElementById("warnMess").innerText = "All fields must be completed.";
            } else {
                // this.userSubjects.push(this.curSubject);
                // console.log(this.curSubject);
                await axios.post("http://127.0.0.1:8000/api/subjects", this.curSubject)
                    .then(async (resp) => {
                        await axios.post("http://127.0.0.1:8000/api/users/" + this.getUser.id + "/subjects/" + resp.data);
                    }).then(async () => {
                        await axios.get("http://127.0.0.1:8000/api/users/" + this.getUser.id + "/subjects")
                            .then(resp => resp.data).then(value => {
                                this.userSubjects = value;
                                this.prepareFormAfterAction();
                            })
                    });
            }
        },
        cancelCreation() {
            this.clearForm();
            document.getElementById("warnMess").innerText = "";
        },
        prepareFormAfterAction() {
            document.getElementById("warnMess").innerText = "";
            $('#createSubjectModal').modal('hide');
            this.clearForm();
            this.confirm();
        },
        clearForm() {
            this.curSubject.name = this.curSubject.short_name = this.curSubject.subject_desc = "";
            this.curSubject.semester = "ZS";
            this.curSubject.year = 1;
        },
        deleteSubject() {
            axios.delete("http://127.0.0.1:8000/api/subjects/" + this.curSubject.id)
                .then(() => {
                    this.userSubjects = this.userSubjects.filter(subject => subject.id !== this.curSubject.id);
                    this.clearForm();
                    this.confirm();
                });
        },
        saveSubjectChanges() {
            if (this.curSubject.name.trim() === "" || this.curSubject.short_name.trim() === ""
                || this.curSubject.subject_desc.trim() === "") {
                document.getElementById("warnEditMess").innerText = "All fields must be completed.";
            } else {
                axios.put("http://127.0.0.1:8000/api/subjects/" + this.curSubject.id, this.curSubject)
                    .then(() => {
                        axios.get("http://127.0.0.1:8000/api/subjects").then(resp => resp.data).then(value => {
                            this.userSubjects = value;
                        });
                        this.prepareFormAfterAction();
                    });
            }
        }
    },
    computed: {
        ...mapGetters(["getUser", "getTeacherRole", "getStudentRole", "getDeleteOperation", "getEditOperation", "getErrors"])
    },
    async mounted() {
        if (this.getUser == null) {
            await this.getLoggedInUser();
        }

        const userId = this.getUser.id;
        axios.get("http://127.0.0.1:8000/api/users/" + userId + "/subjects")
            .then(resp => resp.data)
            .then(value => {
                this.userSubjects = value;
                this.loading = false;
            })
            .catch(errors => this.saveErrors(errors));

        switch (this.getUser.role) {
            case this.getStudentRole :
                this.option = this.getDeleteOperation;
                break;
            case this.getTeacherRole :
                this.option = this.getEditOperation;
                break;
        }

    }
}
</script>

<style scoped="scoped" lang="scss">
$hoverColor      : #dde9f5;
$backgroundColor : white;
$margin          : 10px;

@import "./resources/sass/form_util_btns";

.table-container {
    text-align       : center;
    display          : table;
    background-color : $backgroundColor;
    color            : black;
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

.link {
    color : #cae9ff;

    &:hover {
        text-decoration : underline #cae9ff;
    }
}

//.btn-box {
//    &.start {
//        width : 50%;
//    }
//
//    &.end {
//        width : 50%;
//    }
//}

input, select {
    margin-bottom : $margin;
    margin-top    : 0;
}

.warn-mess {
    margin-top  : $margin;
    margin-left : $margin;
    color       : red;
}

.mess {
    font-size : 15px;
}
</style>
