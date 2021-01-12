<template>
    <div>
        <SearchField @search-area-text="setSearchAreaText"/>

        <transition name="fade">
            <div v-if="searchedList.length && searchAreaText.length !== 0">
                <table class="table-container">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Accessible from</th>
                        <th scope="col">Accessible to</th>
                        <th scope="col">Last update</th>
                        <th scope="col">Creator</th>
                        <th scope="col">Editor</th>
                        <th v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"
                            scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <StudyMatItem v-for="study_mat in searchedList" :key="study_mat.id" :study_mat="study_mat"
                                  @edit-study-mat="editStudyMatData"/>
                    </tbody>
                </table>
            </div>
        </transition>


        <h1 class="p-2 text-2xl text-white font-semibold">Study materials</h1>
        <div v-if="studyMats.length">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Accessible from</th>
                    <th scope="col">Accessible to</th>
                    <th scope="col">Last update</th>
                    <th scope="col">Creator</th>
                    <th scope="col">Editor</th>
                    <th v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"
                        scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <StudyMatItem v-for="study_mat in studyMats" :key="study_mat.id" :study_mat="study_mat"
                              @edit-study-mat="editStudyMatData"/>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p class="p-2 text-lg text-white font-semibold">This subject has not had study materials yet.</p>
        </div>

        <div class="flex w-100 justify-content-end pt-2"
             v-if="getUser && (getUser.role === getAdminRole || getUser.role === getTeacherRole)">
            <button class="btn-primary btn-lg" style="background-color: #1777d4" data-toggle="modal"
                    data-target="#createStudyMaterial" @click="setDateToDatePicker">Add study material
            </button>
        </div>

        <Confirm/>

        <div class="modal fade" id="editStudyMaterial" tabindex="-1" role="dialog"
             aria-labelledby="editStudyMaterialCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStudyMaterialCenterTitle">Edit study material</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                Study material's name
                            </label>
                            <input id="name" v-model="curStudyMat.name"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="file" class="block text-sm font-medium leading-5 text-gray-700">
                                Choose a new file
                            </label>
                            <input id="file" type="file" ref="myEditFile" @change="selectFileForEditing"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="date_from" class="block text-sm font-medium leading-5 text-gray-700">
                                Accessible from
                            </label>
                            <input id="date_from" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="date_till" class="block text-sm font-medium leading-5 text-gray-700">
                                Accessible to
                            </label>
                            <input id="date_till" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="warn-mess"><p id="warnEditMess" class="mess"></p></div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start">
                                <button @click="saveStudyMaterialChanges" class="btn btn-primary">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="cancelEditingStudyMatInfo" data-dismiss="modal" class="btn">
                                    Cancel
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="deleteStudyMaterial" data-dismiss="modal" class="btn red">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createStudyMaterial" tabindex="-1" role="dialog"
             aria-labelledby="createStudyMaterialModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createStudyMaterialModalCenterTitle">Create study material</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="nameCreateSM" class="block text-sm font-medium leading-5 text-gray-700">
                                Study material's name
                            </label>
                            <input id="nameCreateSM" v-model="curStudyMat.name"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="fileCreateSM" class="block text-sm font-medium leading-5 text-gray-700">
                                Choose a new file
                            </label>
                            <input id="fileCreateSM" type="file" ref="myAddFile" @change="selectFileForAdding"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="dateFrom" class="block text-sm font-medium leading-5 text-gray-700">
                                Accessible from
                            </label>
                            <input id="dateFrom" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="dateTill" class="block text-sm font-medium leading-5 text-gray-700">
                                Accessible to
                            </label>
                            <input id="dateTill" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="warn-mess"><p id="warnMess" class="mess"></p></div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start" style="width: 50%">
                                <button @click="createStudyMaterial" class="btn">
                                    Create
                                </button>
                            </div>
                            <div class="btn-box end" style="width: 50%">
                                <button @click="cancelEditingStudyMatInfo" data-dismiss="modal" class="btn">
                                    Cancel
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
import {mapActions, mapGetters} from "vuex";
import StudyMatItem from "./StudyMatItem";
import Confirm from "../Confirm";
import SearchField from "../SearchField";

export default {
    name: "StudyMats",
    components: {
        StudyMatItem, Confirm, SearchField
    },
    data() {
        return {
            studyMats: [],
            subject_id: this.$route.params.subject_id,
            curStudyMat: {
                name: "",
                file: null,
                date_from: "",
                date_till: "",
                subject_id: ""
            },
            searchAreaText: "",
            isShownSearchArea: false
        }
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/study_mats")
            .then(resp => resp.data).then(value => {
            this.studyMats = value;
        });
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole"]),
        searchedList() {
            return this.studyMats.filter(studMat =>
                studMat.name.toLowerCase().trim().startsWith(this.searchAreaText.trim().toLowerCase()));
        }
    },
    methods: {
        ...mapActions(["confirm"]),
        async editStudyMatData(studyMatId) {
            await axios.get("http://127.0.0.1:8000/api/study_mats/" + studyMatId)
                .then(value => value.data).then(value => {
                    this.curStudyMat = value;
                    document.getElementById('date_from').value = this.curStudyMat.date_from.split(" ").join("T");
                    document.getElementById('date_till').value = this.curStudyMat.date_till.split(" ").join("T");
                });
        },
        async saveStudyMaterialChanges() {
            const dateFrom = new Date(document.getElementById("date_from").value);
            const dateTo = new Date(document.getElementById("date_till").value);
            if (this.curStudyMat.name.trim() === "") {
                this.setWarnMsg("warnEditMess", "All fields must be completed.");
            } else if (this.curStudyMat.name.length > 255) {
                this.setWarnMsg("warnEditMess", "Max allowed length in name is 255 chars.");
            } else if (+dateFrom > +dateTo) {
                this.setWarnMsg("warnEditMess", "End date can't be earlier than start date.");
            } else {
                const formData = new FormData();
                formData.append('name', this.curStudyMat.name);
                if (this.curStudyMat.file) {
                    formData.append('file', this.curStudyMat.file, this.curStudyMat.file.name);
                }
                formData.append('date_from', document.getElementById('date_from').value.split("T").join(" "));
                formData.append('date_till', document.getElementById('date_till').value.split("T").join(" "));
                formData.append('id', this.curStudyMat.id);

                await axios.post("http://127.0.0.1:8000/api/study_mats/update", formData, {
                    headers: {'Content-Type': 'multipart/form-data'}
                }).then(async () => {
                    await axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/study_mats")
                        .then(resp => resp.data).then(value => {
                            this.studyMats = value;
                            this.prepareFormAfterAction("#editStudyMaterial");
                        });
                });
            }
        },
        setSearchAreaText(searchAreaText) {
            this.searchAreaText = searchAreaText;
        },
        cancelEditingStudyMatInfo() {
            this.clearForm();
            document.getElementById("warnMess").innerText = "";
        },
        clearForm() {
            this.curStudyMat.name = "";
            this.curStudyMat.file = null;
            document.getElementById("fileCreateSM").value = "";
        },
        deleteStudyMaterial() {
            axios.delete("http://127.0.0.1:8000/api/study_mats/" + this.curStudyMat.id)
                .then(() => {
                    this.studyMats = this.studyMats.filter(study_mat => study_mat.id !== this.curStudyMat.id);
                    this.clearForm();
                    this.confirm();
                });
        },
        selectFileForAdding() {
            this.curStudyMat.file = this.$refs.myAddFile.files[0];
        },
        selectFileForEditing() {
            this.curStudyMat.file = this.$refs.myEditFile.files[0];
        },
        setWarnMsg(warnFieldId, text) {
            document.getElementById(warnFieldId).innerText = text;
            this.eraseWarnMess(warnFieldId);
        },
        async createStudyMaterial() {
            const dateFrom = new Date(document.getElementById("dateFrom").value);
            const dateTo = new Date(document.getElementById("dateTill").value);
            if (this.curStudyMat.name.trim() === "" || this.curStudyMat.file == null) {
                this.setWarnMsg("warnMess", "All fields must be completed.");
            } else if (this.curStudyMat.name.length > 255) {
                this.setWarnMsg("warnMess", "Max allowed length in name is 255 chars.");
            } else if (+dateFrom > +dateTo) {
                this.setWarnMsg("warnMess", "End date can't be earlier than start date.");
            } else {
                const formData = new FormData();
                formData.append('name', this.curStudyMat.name);
                if (this.curStudyMat.file) {
                    formData.append('file', this.curStudyMat.file, this.curStudyMat.file.name);
                }
                formData.append('date_from', document.getElementById('dateFrom').value.split("T").join(" "));
                formData.append('date_till', document.getElementById('dateTill').value.split("T").join(" "));
                formData.append('subject_id', this.subject_id);
                await axios.post("http://127.0.0.1:8000/api/study_mats", formData, {
                    headers: {'Content-Type': 'multipart/form-data'}
                }).then(async () => {
                    await axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/study_mats")
                        .then(resp => resp.data).then(value => {
                            this.studyMats = value;
                            this.prepareFormAfterAction("#createStudyMaterial");
                        });
                });
            }
        },
        eraseWarnMess(id) {
            setTimeout(() => {
                document.getElementById(id).innerText = "";
            }, 3000);
        },
        formatDateValues(value) {
            return value < 10 ? "0" + value : value;
        },
        prepareFormAfterAction(modalId) {
            $(modalId).modal('hide');
            this.clearForm();
            this.confirm();
        },
        setDateToDatePicker() {
            const date = new Date();
            const year = date.getFullYear();
            const month = this.formatDateValues(date.getMonth() + 1);
            const day = this.formatDateValues(date.getDate());
            const hour = this.formatDateValues(date.getHours());
            const minute = this.formatDateValues(date.getMinutes());
            const formattedDate = year + "-" + month + "-" + day + "T" + hour + ":" + minute;
            document.getElementById('dateFrom').value = formattedDate;
            document.getElementById('dateTill').value = formattedDate;
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
    margin           : auto;
    width            : 100%;

    tr {
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

        th {
            color            : white;
            background-color : darken($color : #187fe2, $amount : 3%);
            overflow-wrap    : break-word;
            max-width        : 250px;
            min-width        : 100px;
        }
    }
}

input {
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
