<template>
    <div>
        <SearchField @search-area-text="setSearchAreaText"/>

        <transition name="fade">
            <div v-if="searchedList.length && searchAreaText.length !== 0">
                <table class="table-container">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Year</th>
                        <th scope="col">Abbreviation</th>
                        <th scope="col"
                            v-if="getUser != null && (getUser.role === getStudentRole || getUser.role === getAdminRole)">
                            Option
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <SubjectItem v-for="subject in searchedList" :key="subject.id" :subject="subject" :option="option"
                                 @assign-course="assignCourse" @edit-course="editSubjectData"/>
                    </tbody>
                </table>
            </div>
        </transition>

        <h1 class="p-2 text-2xl text-white font-semibold">Subjects</h1>
        <div class="h-full w-1/5 flex flex-col items-start justify-center" style="float: left; font-size: 18px">
            <div class="list-group">
                <button class="list-group-item list-group-item-action" @click="getYearValue($event.target.value)"
                        v-for="(year, id) in years" :key="id" :value="year">{{ year }}
                </button>
            </div>
        </div>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div style="float: left" v-else-if="filteredSubjects.length">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Year</th>
                    <th scope="col">Abbreviation</th>
                    <th scope="col"
                        v-if="getUser != null && (getUser.role === getStudentRole || getUser.role === getAdminRole)">
                        Option
                    </th>
                </tr>
                </thead>
                <tbody>
                <SubjectItem v-for="subject in filteredSubjects" :key="subject.id" :subject="subject" :option="option"
                             @assign-course="assignCourse" @edit-course="editSubjectData"/>
                </tbody>
            </table>
        </div>

        <Confirm/>

        <div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                Subject's name
                            </label>
                            <input id="name" v-model="curSubject.name" name="name"
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
                            <input id="year" name="year" v-model="curSubject.year" type="number" min="1" max="5"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="short_name" class="block text-sm font-medium leading-5 text-gray-700">
                                Short name
                            </label>
                            <input id="short_name" name="short_name" v-model="curSubject.short_name" maxlength="5"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="subject_desc" class="block text-sm font-medium leading-5 text-gray-700">
                                Subject description
                            </label>
                            <textarea id="subject_desc" name="subject_desc" v-model="curSubject.subject_desc"
                                      maxlength="255"
                                      class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </textarea>
                        </div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start">
                                <button @click="saveSubjectChanges" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="cancelEditingSubjectInfo" data-dismiss="modal" class="btn">
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
import {mapActions, mapGetters} from 'vuex';
import Preloader from "../Preloader";
import SubjectItem from "./SubjectItem";
import Confirm from "../Confirm";
import SearchField from "../SearchField";

export default {
    name: "Subjects",
    components: {
        Preloader, SubjectItem, Confirm, SearchField
    },
    data() {
        return {
            subjects: [],
            years: [],
            loading: true,
            btnYearValue: "",
            option: "",
            curSubject: {},
            searchAreaText: "",
            isShownSearchArea: false
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        getYearValue(value) {
            this.btnYearValue = value;
        },
        setSearchAreaText(searchAreaText) {
            this.searchAreaText = searchAreaText;
        },
        async assignCourse(subjectId) {
            const userId = this.getUser.id;
            await axios.post("http://127.0.0.1:8000/api/users/" + userId + "/subjects/" + subjectId)
                .then(() => {
                    this.confirm();
                })
                .catch(error => this.saveErrors(error));
        },
        editSubjectData(subjectId) {
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
                    this.curSubject = {};
                    this.confirm();
                });
        },
        cancelEditingSubjectInfo() {
            this.curSubject = {};
        },
        deleteSubject() {
            axios.delete("http://127.0.0.1:8000/api/subjects/" + this.curSubject.id)
                .then(() => {
                    this.subjects = this.subjects.filter(subject => subject.id !== this.curSubject.id);
                    this.curSubject = {};
                    this.confirm();
                });
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/subjects")
            .then(resp => resp.data).then(value => {
                this.subjects = value;
                this.loading = false;
            }).catch(errors => this.saveErrors(errors));

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
        ...mapGetters(["getStudentRole", "getAdminRole", "getWriteOperation", "getEditOperation", "getUser"]),
        filteredSubjects() {
            return this.subjects.filter(subject => {
                const subjectsCreationDate = subject.created_at.split("-");
                const yearValue = this.btnYearValue.split("/");
                return (subjectsCreationDate[0] === yearValue[0] && subjectsCreationDate[1] > 9)//from September of the previous year
                    || (subjectsCreationDate[0] === yearValue[1] && subjectsCreationDate[1] <= 8);//till August of the next year
            });
        },
        searchedList() {
            return this.subjects.filter(subject =>
                subject.name.toLowerCase().trim().startsWith(this.searchAreaText.trim().toLowerCase()));
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$fontSize        : 18px;
$hoverColor      : #dde9f5;
$backgroundColor : white;
$margin          : 10px;

@import "./resources/sass/form_util_btns";

.table-container {
    text-align       : center;
    display          : table;
    background-color : $backgroundColor;
    color            : black;
    font-size        : $fontSize;
    border-radius    : 7px;
    overflow         : hidden;
    border-collapse  : collapse;
    margin-bottom    : $margin;

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

input, select {
    margin-bottom : $margin;
    margin-top    : 0;
}
</style>
