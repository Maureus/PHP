<template>
    <div>
        <SearchField @search-area-text="setSearchAreaText"/>

        <transition name="fade">
            <div v-if="searchedList.length && searchAreaText.length !== 0">
                <h1 class="p-2 text-2xl text-white font-semibold">Searched quizzes</h1>
                <table class="table-container">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">From</th>
                        <th scope="col">Till</th>
                        <th scope="col">Description</th>
                        <th scope="col">Number of questions</th>
                        <th scope="col"></th>
                        <th v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"
                            scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <QuizItem v-for="quiz in searchedList" :key="quiz.id" :quiz="quiz" :option="option"
                              @edit-quiz="editQuiz" @delete-quiz="reformList"/>
                    </tbody>
                </table>
            </div>
        </transition>

        <h1 class="p-2 text-2xl text-white font-semibold">Quizzes</h1>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="quizzes.length !== 0">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">From</th>
                    <th scope="col">Till</th>
                    <th scope="col">Description</th>
                    <th scope="col">Number of questions</th>
                    <th scope="col"></th>
                    <th v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"
                        scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <QuizItem v-for="quiz in quizzes" :key="quiz.id" :quiz="quiz" :option="option"
                          @edit-quiz="editQuiz" @delete-quiz="reformList"/>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p class="p-2 text-lg text-white font-semibold">This subject has not had quizzes yet.</p>
        </div>

        <div v-if="loading === false && (getUser.role === getTeacherRole || getUser.role === getAdminRole)"
             class="flex w-100 justify-content-end pt-2">
            <button data-toggle="modal" data-target="#modalAddQuiz"
                    class="btn-primary btn-lg" style="background-color: #1777d4; margin-bottom: 10px">
                Add Quiz
            </button>
        </div>

        <Confirm/>

        <div class="modal fade" id="modalAddQuiz" tabindex="-1" role="dialog"
             aria-labelledby="modalAddQuiz" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddQuizTitle">Add quiz</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Quiz's name
                            </label>
                            <input v-model="newQuiz.name" name="name" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Quiz type
                            </label>
                            <select v-model="newQuiz.type"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                    name="category_id">
                                <option value="list">list</option>
                                <option value="manual" selected>manual</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date from
                            </label>
                            <input name="email" v-model="newQuiz.date_from" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date till
                            </label>
                            <input v-model="newQuiz.date_till" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Description
                            </label>
                            <input v-model="newQuiz.quiz_desc" name="quiz_desc"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Number of questions
                            </label>
                            <input v-model="newQuiz.num_questions" name="num_questions" type="number" min="1" max="30"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Points for question
                            </label>
                            <input v-model="newQuiz.points_fq" name="points_fq" type="number" min="1" max="10"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Category
                            </label>
                            <select v-model="newQuiz.category_id"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                    name="category_id">
                                <option value="1" selected>Category 1</option>
                                <option value="2">Category 2</option>
                                <option value="3">Category 3</option>
                                <option value="4">Category 4</option>
                            </select>
                        </div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start" style="width: 50%">
                                <button @click="addQuiz" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end" style="width: 50%">
                                <button data-dismiss="modal" class="btn red">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalQuiz" tabindex="-1" role="dialog"
             aria-labelledby="modalQuiz" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalQuizTitle">Edit quiz</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Quiz name
                            </label>
                            <input v-model="getQuiz.name" name="name" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date from
                            </label>
                            <input v-model='getQuiz.date_from' type="datetime-local" id="quiz-date-from"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date till
                            </label>
                            <input v-model="getQuiz.date_till" type="datetime-local" id="quiz-date-till"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Description
                            </label>
                            <input v-model="getQuiz.quiz_desc" name="address"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Number of questions
                            </label>
                            <input v-model="getQuiz.num_questions" name="address" type="number" min="1" max="30"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Points for question
                            </label>
                            <input v-model="getQuiz.points_fq" name="address" type="number" min="1" max="10"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start" style="width: 50%">
                                <button @click="saveQuiz" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end" style="width: 50%">
                                <button data-dismiss="modal" class="btn red">
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
import {mapGetters, mapActions} from 'vuex';
import Preloader from "../Preloader";
import Confirm from "../Confirm";
import QuizItem from "./QuizItem";
import SearchField from "../SearchField";

export default {
    name: "Quizzes",
    components: {
        Preloader, QuizItem, Confirm, SearchField
    },
    data() {
        return {
            loading: false,
            quizzes: [],
            option: '',
            subject_id: this.$route.params.subject_id,
            newQuiz: {
                points_fq: 1,
                num_questions: 10,
                date_from: Date.now().toString(),
                date_till: Date.now().toString() + 1
            },
            loadedQuiz: {},
            defaultNumber: 1,
            searchAreaText: "",
            isShownSearchArea: false
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm", 'saveQuiz']),
        async saveQuiz() {
            this.getQuiz.date_from = this.getQuiz.date_from.split("T").join(" ");
            this.getQuiz.date_till = this.getQuiz.date_till.split("T").join(" ");
            await axios.put('http://127.0.0.1:8000/api/quizzes/' + this.getQuiz.id, this.getQuiz).then(async () => {
                await this.editQuiz();
            }).catch(err => {
                this.saveErrors(err);
            });
        },
        editQuiz() {
            Object.assign(this.quizzes[this.quizzes.findIndex(quiz => quiz.id === this.getQuiz.id)], this.getQuiz);
            this.confirm();
        },
        reformList(quiz_id) {
            this.quizzes = this.quizzes.filter(quiz => quiz.id !== quiz_id);
            this.confirm();
        },
        async addQuiz() {
            this.newQuiz.subject_id = this.subject_id;
            this.newQuiz.date_from = this.newQuiz.date_from.split("T").join(" ");
            this.newQuiz.date_till = this.newQuiz.date_till.split("T").join(" ");

            await axios.post('http://127.0.0.1:8000/api/quizzes/', this.newQuiz).then(async () => {
                await axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/quizzes")
                    .then(resp => {
                        this.quizzes = resp.data;
                        this.confirm();
                    })
                    .catch(errors => this.saveErrors(errors));
            }).catch(err => {
                this.saveErrors(err);
            });
        },
        setSearchAreaText(searchAreaText) {
            this.searchAreaText = searchAreaText;
        },
        formatDateValues(value) {
            return value < 10 ? "0" + value : value;
        },
        setDates() {
            const date = new Date();
            const year = date.getFullYear();
            const month = this.formatDateValues(date.getMonth() + 1);
            const day1 = this.formatDateValues(date.getDate());
            const day2 = this.formatDateValues(date.getDate() + 7);
            const hour = this.formatDateValues(date.getHours());
            const minute = this.formatDateValues(date.getMinutes());
            this.newQuiz.date_from = year + "-" + month + "-" + day1 + "T" + hour + ":" + minute;
            this.newQuiz.date_till = year + "-" + month + "-" + day2 + "T" + hour + ":" + minute;
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole", "getStudentRole",
            "getDeleteOperation", "getEditOperation", "getErrors", "getQuiz"
        ]),
        searchedList() {
            return this.quizzes.filter(quiz =>
                quiz.name.toLowerCase().trim().startsWith(this.searchAreaText.trim().toLowerCase()));
        }
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/quizzes")
            .then(resp => {
                this.quizzes = resp.data;
            })
            .catch(errors => this.saveErrors(errors));

        this.setDates();

        switch (this.getUser.role) {
            case this.getStudentRole :
                this.option = "student";
                break;
            case this.getTeacherRole :
                this.option = "teacher";
                break;
            case this.getAdminRole :
                this.option = "admin";
                break;
        }

    }
}
</script>

<style scoped="scoped" lang="scss">
$hoverColor      : #dde9f5;
$backgroundColor : white;
$margin          : 10px;

@import "resources/sass/form_util_btns";

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

input, select {
    margin-bottom : $margin;
    margin-top    : 0;
}

</style>
