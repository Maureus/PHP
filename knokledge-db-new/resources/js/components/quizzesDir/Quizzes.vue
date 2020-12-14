<template>
    <div>
        <Confirm :mess="mess"/>
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
                    <th v-if="getUser.role === getAdminRole || getUser.role === getTeacherRole"
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
            <p class="p-2 text-lg text-white font-semibold">No quizzes</p>
        </div>

        <div v-if="loading === false && (getUser.role === getTeacherRole || getUser.role === getAdminRole)"
             class="flex w-100 justify-content-end pt-2">
            <button data-toggle="modal" data-target="#modalAddQuiz"
                    class="btn-primary bg-danger btn-lg">
                Add Quiz
            </button>
        </div>

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
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Quiz name
                            </label>
                            <input v-model="newQuiz.name" name="name" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Quiz type
                            </label>
                            <input v-model="newQuiz.type" name="type" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date from
                            </label>
                            <input name="email" v-model="newQuiz.date_from" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date till
                            </label>
                            <input v-model="newQuiz.date_till" type="datetime-local"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Description
                            </label>
                            <input v-model="newQuiz.quiz_desc" name="quiz_desc"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Number of questions
                            </label>
                            <input v-model="newQuiz.num_questions" name="num_questions"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Points for question
                            </label>
                            <input v-model="newQuiz.points_fq" name="points_fq"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Category
                            </label>
                            <select v-model="newQuiz.category_id"
                                    class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                    name="category_id">
                                <option value="1">Category 1</option>
                                <option value="2">Category 2</option>
                                <option value="3">Category 3</option>
                                <option value="4">Category 4</option>
                            </select>
                        </div>
                        <div class="btn-container">
                            <div class="btn-box start">
                                <button @click="addQuiz" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
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
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Quiz name
                            </label>
                            <input v-model="getQuiz.name" name="name" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date from
                            </label>
                            <input name="email" v-model="getQuiz.date_from" type="date"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date till
                            </label>
                            <input v-model="getQuiz.date_till" type="date"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Description
                            </label>
                            <input v-model="getQuiz.quiz_desc" name="address"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Number of questions
                            </label>
                            <input v-model="getQuiz.num_questions" name="address"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="btn-container">
                            <div class="btn-box start">
                                <button @click="saveQuiz" data-dismiss="modal" class="btn">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
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

export default {
    name: "Quizzes",
    components: {
        Preloader, QuizItem, Confirm
    },
    data() {
        return {
            loading: false,
            quizzes: [],
            option: '',
            mess: '',
            subject_id: this.$route.params.subject_id,
            newQuiz: {},
            loadedQuiz: {}
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm", 'saveQuiz']),
        async saveQuiz() {
            await axios.put('http://127.0.0.1:8000/api/quizzes/' + this.getQuiz.id, this.getQuiz).then(async () => {
                await this.editQuiz();
            }).catch(err => {
                this.saveErrors(err);
            });
        },
        editQuiz() {
            Object.assign(this.quizzes[this.quizzes.findIndex(quiz => quiz.id === this.getQuiz.id)], this.getQuiz);
            this.mess = "Quiz has been edited.";
            this.confirm();
        },
        reformList(quiz_id) {
            this.quizzes = this.quizzes.filter(quiz => quiz.id !== quiz_id);
            this.mess = "Quiz has been deleted.";
            this.confirm();
        },
        async addQuiz() {
            this.newQuiz.subject_id = this.subject_id;
            this.newQuiz.date_from = this.newQuiz.date_from.split("T").join(" ") + ":00";
            this.newQuiz.date_till = this.newQuiz.date_till.split("T").join(" ") + ":00";

            await axios.post('http://127.0.0.1:8000/api/quizzes/', this.newQuiz).then(async () => {
                await axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/quizzes")
                    .then(resp => {
                        this.quizzes = resp.data;
                        this.mess = "Quiz has been added.";
                        this.confirm();
                    })
                    .catch(errors => this.saveErrors(errors));
            }).catch(err => {
                this.saveErrors(err);
            });

        }
    },
    computed: {
        ...mapGetters([
            "getUser",
            "getAdminRole",
            "getTeacherRole",
            "getStudentRole",
            "getDeleteOperation",
            "getEditOperation",
            "getErrors",
            'getQuiz'
        ])
    },
    async mounted() {
        axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/quizzes")
            .then(resp => {
                this.quizzes = resp.data;
            })
            .catch(errors => this.saveErrors(errors));

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
$fontSize: 18px;
$hoverColor: #dde9f5;
$backgroundColor: white;
$margin: 10px;

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
    width            : 100%;

    tr {
        line-height : 2.1875em;


        &:nth-child(odd) {
            background-color: $backgroundColor;
        }

        &:nth-child(even) {
            background-color: darken($color: $backgroundColor, $amount: 5%);
        }

        &:hover {
            background-color: darken($color: $hoverColor, $amount: 2%);
        }

        th {
            color: white;
            background-color: darken($color: #187fe2, $amount: 3%);
            overflow-wrap: break-word;
            max-width: 250px;
            min-width: 100px;
        }
    }
}

.btn-container {
    display: flex;
}

.btn-box {
    padding-top: 50px;

    &.start {
        text-align: start;
        width: 80%;
    }

    &.end {
        text-align: end;
        margin-right: 0.5rem;
        width: 20%;
    }
}

.btn {
    width: 100px;
    height: auto;
    font-size: 14px;
    margin-bottom: $margin * 1.5;
    color: white;
    background-color: #6875f5;

    &.red {
        background-color: #f05252;

        &:hover {
            background-color: #e02424;
        }
    }

    &:hover {
        background-color: #5850ec;
    }

    &:focus {
        outline: none;
    }
}

</style>
