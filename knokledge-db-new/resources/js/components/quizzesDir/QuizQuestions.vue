<template>
    <div>
        <h1 class="p-2 text-2xl text-white font-semibold">Questions</h1>
        <Loader v-if="questions === []"/>
        <div v-else-if="questions.length !== 0">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Question text</th>
                    <th scope="col">Answer 1</th>
                    <th scope="col">Answer 2</th>
                    <th scope="col">Correct answer</th>
                    <th scope="col"
                        v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"></th>
                </tr>
                </thead>
                <tbody>
                <QuestionItem v-for="question in questions" :key="question.id" :question="question"
                              @edit-question="editQuestionData"/>
                </tbody>
            </table>
        </div>
        <div class="flex w-100 justify-content-end pt-2"
             v-if="getUser && (getUser.role === getAdminRole || getUser.role === getTeacherRole)">
            <button class="btn-primary btn-lg" style="background-color: #1777d4" data-toggle="modal"
                    data-target="#">Add new question
            </button>
        </div>
        <div v-else>
            <p class="p-2 text-lg text-white font-semibold">This quiz has not had questions yet.</p>
        </div>
        <Confirm/>

        <div class="modal fade" id="editQuestion" tabindex="-1" role="dialog"
             aria-labelledby="editingQuestionModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editingQuestionModalCenterTitle">Edit study material</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="focus:outline-none">&times;</span>
                        </button>
                    </div>

                    <div class="pr-2 pl-2 pt-2">
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                Question's text
                            </label>
                            <input id="name" v-model="curQuestion.name"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="answer1" class="block text-sm font-medium leading-5 text-gray-700">
                                Answer 1
                            </label>
                            <input id="answer1" ref="myFile" v-model="curQuestion.answer_1"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="answer2" class="block text-sm font-medium leading-5 text-gray-700">
                                Answer 2
                            </label>
                            <input id="answer2" v-model="curQuestion.answer_2"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4 mx-2">
                            <label for="correctAnswer" class="block text-sm font-medium leading-5 text-gray-700">
                                Correct answer
                            </label>
                            <input id="correctAnswer" v-model="curQuestion.answer_correct"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="warn-mess"><p id="warnEditMess" class="mess"></p></div>
                        <div class="btn-container mx-2">
                            <div class="btn-box start">
                                <button @click="saveQuestionChanges" class="btn btn-primary">
                                    Confirm
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="cancelEditingQuestionInfo" data-dismiss="modal" class="btn">
                                    Cancel
                                </button>
                            </div>
                            <div class="btn-box end">
                                <button @click="deleteQuestion" data-dismiss="modal" class="btn red">
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
import {mapActions, mapGetters} from "vuex";
import Loader from "../Loader";
import QuestionItem from "./QuestionItem";
import Confirm from "../Confirm";

export default {
    name: "QuizQuestions",
    components: {
        Loader, QuestionItem, Confirm
    },
    data() {
        return {
            quizId: this.$route.params.quiz_id,
            questions: [],
            curQuestion: {
                name: "",
                answer_1: "",
                answer_2: "",
                answer_correct: "",
                id: ""
            }
        }
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/questions")
            .then(resp => resp.data).then(value => {
            this.questions = value;
        });
    },
    computed: {
        ...mapGetters(["getUser", "getTeacherRole", "getAdminRole"])
    },
    methods: {
        ...mapActions(["confirm"]),
        editQuestionData(questionId) {
            axios.get("http://127.0.0.1:8000/api/questions/" + questionId)
                .then(value => value.data).then(value => {
                this.curQuestion = value;
            });
        },
        saveQuestionChanges() {
            if (this.curQuestion.name.trim() === "" || this.curQuestion.answer_1.trim() === ""
                || this.curQuestion.answer_2.trim() === "" || this.curQuestion.answer_correct.trim() === "") {
                document.getElementById("warnEditMess").innerText = "All fields must be completed.";
            } else {
                axios.put("http://127.0.0.1:8000/api/questions/" + this.curQuestion.id, this.curQuestion).then(() => {
                    axios.get("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/questions")
                        .then(resp => resp.data).then(value => {
                        this.questions = value;
                        this.prepareFormAfterAction("#editQuestion");
                    });
                });
            }
        },
        prepareFormAfterAction(modalId) {
            document.getElementById("warnEditMess").innerText = "";
            $(modalId).modal('hide');
            this.clearForm();
            this.confirm();
        },
        clearForm() {
            this.curQuestion.name = this.curQuestion.answer_1 = this.curQuestion.answer_2 = this.curQuestion.answer_correct = "";
        },
        cancelEditingQuestionInfo() {
            this.clearForm();
        },
        deleteQuestion() {

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

.warn-mess {
    margin-top  : $margin;
    margin-left : $margin;
    color       : red;
}

.mess {
    font-size : 15px;
}

</style>
