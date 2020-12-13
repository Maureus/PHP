<template>
    <div>
        <div v-if="study_mats.length">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Accessible to</th>
                    <th scope="col">Last update</th>
                    <th scope="col">Creator</th>
                    <th scope="col">Editor</th>
                    <th v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"
                        scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <StudyMatItem v-for="study_mat in study_mats" :key="study_mat.id" :study_mat="study_mat"
                              @edit-study-mat="editStudyMatData"/>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p class="p-2 text-lg text-white font-semibold">This subject has not had study materials yet.</p>
        </div>

        <Confirm :mess="mess"/>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
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
                                Study material's name
                            </label>
                            <input id="name" v-model="curStudyMat.name" name="name" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start">
                                <button @click="saveStudyMaterialChanges" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="cancelEditingSubjectInfo" data-dismiss="modal" class="btn">
                                    Cancel
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="deleteStudyMaterials" data-dismiss="modal" class="btn red">
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
import {mapGetters, mapActions} from "vuex";
import StudyMatItem from "./StudyMatItem";
import Confirm from "../Confirm";

export default {
    name: "StudyMats",
    components: {
        StudyMatItem, Confirm
    },
    data() {
        return {
            study_mats: [],
            subject_id: this.$route.params.subject_id,
            mess: "",
            editStudyMat: false,
            curStudyMat: {},
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/study_mats")
            .then(resp => resp.data).then(value => {
                this.study_mats = value;
                this.loading = false;
            });
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole"])
    },
    methods: {
        ...mapActions(["confirm"]),
        editStudyMatData(studyMatId) {
            this.editStudyMat = true;
            axios.get("http://127.0.0.1:8000/api/study_mats/" + studyMatId)
                .then(value => value.data)
                .then(value => {
                    this.curStudyMat = value;
                });
        },
        saveStudyMaterialChanges() {
            // axios.put("http://127.0.0.1:8000/api/study_mats/" + this.curStudyMat.id, this.curStudyMat)
            //     .then(() => {
            //         axios.get("http://127.0.0.1:8000/api/study_mats").then(resp => resp.data).then(value => {
            //             this.study_mats = value;
            //         });
            //         this.editStudyMat = false;
            //         this.curStudyMat = {};
            //         this.mess = "Study material has been changed.";
            //         this.confirm();
            //     });
        },
        cancelEditingSubjectInfo() {
            this.editStudyMat = false;
            this.curStudyMat = {};
        },
        deleteStudyMaterials() {
            axios.delete("http://127.0.0.1:8000/api/study_mats/" + this.curStudyMat.id)
                .then(() => {
                    this.study_mats = this.study_mats.filter(study_mat => study_mat.id !== this.curStudyMat.id);
                    this.editStudyMat = false;
                    this.curStudyMat = {};
                    this.mess = "Study material has been deleted.";
                    this.confirm();
                });
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
    margin           : auto;
    //table-layout     : fixed;
    width            : 100%;

    tr {
        //margin      : $margin/2 0;
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
</style>
