<template>
    <div>
        <router-link class="link" :to="{name: 'SubjectContent', params: {subject_id: this.subjectId}}">
            Back
        </router-link>
        <div v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)">
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
            <div v-else>
                <p class="p-2 text-lg text-white font-semibold">This quiz has not had questions yet.</p>
            </div>
            <div class="flex w-100 justify-content-end pt-2"
                 v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)">
                <button class="btn-primary btn-lg mx-1" style="background-color: #1777d4" data-toggle="modal"
                        data-target="#chooseQuestionModal">Add/Delete question from list
                </button>
                <button class="btn-primary btn-lg mx-1" style="background-color: #1777d4" data-toggle="modal"
                        data-target="#createQuestionModal">Add new question
                </button>
            </div>

            <Confirm/>

            <div class="modal fade" id="chooseQuestionModal" tabindex="-1" role="dialog"
                 aria-labelledby="assignSubjectToStudentsModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignSubjectToStudentsModalCenterTitle">
                                Choose question
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="focus:outline-none">&times;</span>
                            </button>
                        </div>

                        <div class="pr-2 pl-2 pt-2">
                            <div class="col-span-6 sm:col-span-4 mx-2">
                                <label for="subjects" class="block text-sm font-medium leading-5 text-gray-700">
                                    Questions
                                </label>
                                <select id="subjects" v-model="chosenQuestionId"
                                        class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    <option v-for="question in questionsList" :key="question.id" :value="question.id">
                                        {{ question.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="warn-mess"><p id="warnMessAddingFromList" class="mess"></p></div>
                            <div class="btn-container mx-2">
                                <div class="btn-box start">
                                    <button @click="assignQuestionToQuiz" class="btn">Confirm</button>
                                </div>
                                <div class="btn-box end">
                                    <button data-dismiss="modal" class="btn">Cancel</button>
                                </div>
                                <div class="btn-box end">
                                    <button @click="deleteQuestionFromList" class="btn red">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="createQuestionModal" tabindex="-1" role="dialog"
                 aria-labelledby="creationQuestionModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="creationQuestionModalCenterTitle">Create new question</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="focus:outline-none">&times;</span>
                            </button>
                        </div>

                        <div class="pr-2 pl-2 pt-2">
                            <div class="col-span-6 sm:col-span-4 mx-2">
                                <label for="questionName" class="block text-sm font-medium leading-5 text-gray-700">
                                    Question's text
                                </label>
                                <textarea id="questionName" v-model="curQuestion.name"
                                          class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </textarea>
                            </div>
                            <div class="col-span-6 sm:col-span-4 mx-2">
                                <label for="answer1" class="block text-sm font-medium leading-5 text-gray-700">
                                    Answer 1
                                </label>
                                <input id="answer1" v-model="curQuestion.answer_1"
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
                                <label class="text-sm font-medium leading-5 text-gray-700 mb-0">
                                    Correct answer:
                                </label>
                                <label for="answer1Radio"
                                       class="text-sm font-medium leading-5 text-gray-700 mb-0">1</label>
                                <input id="answer1Radio" type="radio" name="correctAnswer" :value="curQuestion.answer_1"
                                       class="mr-2 mb-0" checked>
                                <label for="answer2Radio"
                                       class="text-sm font-medium leading-5 text-gray-700 mb-0">2</label>
                                <input id="answer2Radio" type="radio" name="correctAnswer" :value="curQuestion.answer_2"
                                       class="mr-2 mb-0">
                            </div>
                            <div class="warn-mess"><p id="warnMess" class="mess"></p></div>
                            <div class="btn-container mx-2">
                                <div class="btn-box start" style="width: 50%">
                                    <button @click="createNewQuestion" id="createBtn" class="btn btn-primary">
                                        Create
                                    </button>
                                </div>
                                <div class="btn-box end" style="width: 50%">
                                    <button @click="cancel" data-dismiss="modal" class="btn">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog"
                 aria-labelledby="editingQuestionModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editingQuestionModalCenterTitle">Edit question</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="focus:outline-none">&times;</span>
                            </button>
                        </div>

                        <div class="pr-2 pl-2 pt-2">
                            <div class="col-span-6 sm:col-span-4 mx-2">
                                <label for="nameEdit" class="block text-sm font-medium leading-5 text-gray-700">
                                    Question's text
                                </label>
                                <input id="nameEdit" v-model="curQuestion.name"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                            </div>
                            <div class="col-span-6 sm:col-span-4 mx-2">
                                <label for="answer1Edit" class="block text-sm font-medium leading-5 text-gray-700">
                                    Answer 1
                                </label>
                                <input id="answer1Edit" ref="myFile" v-model="curQuestion.answer_1"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                            </div>
                            <div class="col-span-6 sm:col-span-4 mx-2">
                                <label for="answer2Edit" class="block text-sm font-medium leading-5 text-gray-700">
                                    Answer 2
                                </label>
                                <input id="answer2Edit" v-model="curQuestion.answer_2"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                            </div>
                            <div class="col-span-6 sm:col-span-4 mx-2">
                                <p class="text-sm font-medium leading-5 text-gray-700 mb-0">
                                    Correct answer:
                                </p>
                                <label for="answer1RadioEdit"
                                       class="text-sm font-medium leading-5 text-gray-700 mb-0">1</label>
                                <input id="answer1RadioEdit" type="radio" name="correctAnswer"
                                       :value="curQuestion.answer_1" class="mr-2 mb-0">
                                <label for="answer2RadioEdit"
                                       class="text-sm font-medium leading-5 text-gray-700 mb-0">2</label>
                                <input id="answer2RadioEdit" type="radio" name="correctAnswer"
                                       :value="curQuestion.answer_2" class="mr-2 mb-0">
                            </div>
                            <div class="warn-mess"><p id="warnEditMess" class="mess"></p></div>
                            <div class="btn-container mx-2">
                                <div class="btn-box start">
                                    <button @click="saveQuestionChanges" class="btn btn-primary">
                                        Confirm
                                    </button>
                                </div>
                                <div class="btn-box end">
                                    <button @click="cancel" data-dismiss="modal" class="btn">
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

            <h1 class="p-2 text-2xl text-white font-semibold">Users' results</h1>
            <Loader v-if="results === []"/>
            <table v-else-if="results.length !== 0" class="table-container">
                <thead>
                <tr>
                    <th scope="col">User's name</th>
                    <th scope="col">Quiz's name</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Score</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(result, index) in results" :key="index">
                    <td>{{ result.user_name }}</td>
                    <td>{{ result.quiz_name }}</td>
                    <td>{{ result.subject_name }}</td>
                    <td>{{ result.result }}</td>
                </tr>
                </tbody>
            </table>
            <p v-else class="p-2 text-lg text-white font-semibold">No one has passed quiz.</p>
        </div>

        <div v-else-if="getUser != null && getUser.role == getStudentRole">
            <div v-for="question in questions" :key="question.id" class="question">
                <p>{{ question.name }}</p>
                <div>
                    <input :id="'answer1-' + question.id" type="radio" :name="'answer-' + question.id"
                           :value="question.answer_1">
                    <label>{{ question.answer_1 }}</label>
                </div>

                <div>
                    <input :id="'answer2-' + question.id" type="radio" :name="'answer-' + question.id"
                           :value="question.answer_2">
                    <label>{{ question.answer_2 }}</label>
                </div>
            </div>
            <div class="flex w-100 justify-content-start pt-2 ml-2" v-if="questions.length !== 0">
                <button data-toggle="modal" data-target="#modalAddQuiz" @click="sendResults"
                        class="btn-primary btn-lg" style="background-color: #1777d4; margin-bottom: 10px">
                    Finish
                </button>
            </div>
            <p v-else class="p-2 text-lg text-white font-semibold">No questions in current quiz</p>
            <Confirm/>
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
            curQuiz: {},
            questions: [],
            curQuestion: {
                name: "",
                answer_1: "",
                answer_2: "",
                answer_correct: "",
                id: "",
                subject_id: ""
            },
            results: [],
            subjectId: "",
            chosenQuestionId: "",
            questionsList: []
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/questions")
            .then(resp => this.questions = resp.data);

        await axios.get("http://127.0.0.1:8000/api/quizzes/" + this.quizId)
            .then(resp => this.curQuiz = resp.data);
        this.subjectId = this.curQuiz.subject_id;

        await axios.get("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/results")
            .then(resp => this.results = resp.data);

        await axios.get("http://127.0.0.1:8000/api/subject/" + this.subjectId + "/questions")
            .then(resp => this.questionsList = resp.data);
        if (this.questionsList.length) {
            this.chosenQuestionId = this.questionsList[0].id;
        }
    },
    computed: {
        ...mapGetters(["getUser", "getTeacherRole", "getAdminRole", "getStudentRole"])
    },
    methods: {
        ...mapActions(["confirm", "hide"]),
        deleteQuestionFromList() {
            const questionId = this.chosenQuestionId;
            axios.delete("http://127.0.0.1:8000/api/questions/" + questionId)
                .then(() => {
                    this.questionsList = this.questionsList.filter(question => question.id !== questionId);
                    // this.questions = this.questions.filter(question => question.id !== this.chosenQuestionId);
                    if (this.questionsList.length) {
                        this.chosenQuestionId = this.questionsList[0].id;
                    }

                    axios.delete("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/question/" + questionId)
                        .then(() => this.questions = this.questions.filter(question => question.id !== questionId));
                });
        },
        assignQuestionToQuiz() {
            if (parseInt(this.curQuiz.num_questions) === this.questions.length) {
                document.getElementById("warnMessAddingFromList").innerText = "No more questions adding allowed";
                this.eraseWarnMess("warnMessAddingFromList");
            } else {
                axios.post("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/question/" + this.chosenQuestionId)
                    .then(() => {
                        axios.get("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/questions")
                            .then(resp => this.questions = resp.data);
                    });
            }
        },
        sendResults() {
            const elements = document.querySelectorAll("input");
            let score = 0;
            elements.forEach(el => {
                const questionId = el.name.split("-")[1];
                this.questions.forEach(question => {
                    if (question.id === questionId) {
                        if (el.checked && el.value === question.answer_correct) {
                            score++;
                        }
                    }
                });
            });
            axios.post("http://127.0.0.1:8000/api/quiz/results", {
                quiz_id: this.quizId,
                result: score * this.curQuiz.points_fq
            }).then(() => {
                this.confirm();
                setTimeout(() => {
                    this.hide();
                    this.$router.push({name: 'Profile'});
                }, 3000);
            });
        },
        editQuestionData(questionId) {
            axios.get("http://127.0.0.1:8000/api/questions/" + questionId).then(value => value.data).then(value => {
                this.curQuestion = value;
                if (this.curQuestion.answer_correct === this.curQuestion.answer_1) {
                    document.getElementById("answer1RadioEdit").checked = true;
                } else {
                    document.getElementById("answer2RadioEdit").checked = true;
                }
            });
        },
        saveQuestionChanges() {
            if (this.curQuestion.name.trim() === "" || this.curQuestion.answer_1.trim() === ""
                || this.curQuestion.answer_2.trim() === "") {
                document.getElementById("warnEditMess").innerText = "All fields must be completed.";
                this.eraseWarnMess("warnEditMess");
            } else {
                this.setCheckedValue();
                axios.put("http://127.0.0.1:8000/api/questions/" + this.curQuestion.id, this.curQuestion).then(() => {
                    axios.get("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/questions")
                        .then(resp => resp.data).then(value => {
                        this.questions = value;
                        this.prepareFormAfterAction("#editQuestionModal");
                    });
                });
            }
        },
        prepareFormAfterAction(modalId) {
            $(modalId).modal('hide');
            this.clearForm();
            this.confirm();
        },
        clearForm() {
            document.getElementById("warnMess").innerText = "";
            this.curQuestion.name = this.curQuestion.answer_1 = this.curQuestion.answer_2 = "";
            if (document.getElementById("answer2Radio").checked) {
                document.getElementById("answer2Radio").checked = false;
            }
            document.getElementById("answer1Radio").checked = true;
        },
        cancel() {
            this.clearForm();
        },
        eraseWarnMess(id) {
            setTimeout(() => {
                document.getElementById(id).innerText = "";
            }, 3000);
        },
        deleteQuestion() {
            axios.delete("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/question/" + this.curQuestion.id)
                .then(() => {
                    //this.questionsList = this.questionsList.filter(question => question.id !== this.curQuestion.id);
                    this.questions = this.questions.filter(question => question.id !== this.curQuestion.id);
                    this.clearForm();
                    this.confirm();
                });
        },
        setCheckedValue() {
            const elements = document.getElementsByName("correctAnswer");
            elements.forEach(el => {
                if (el.checked) {
                    this.curQuestion.answer_correct = el.value;
                }
            });
        },
        createNewQuestion() {
            axios.get("http://127.0.0.1:8000/api/quizzes/" + this.quizId).then(resp => resp.data).then(value => {
                if (parseInt(value.num_questions) === this.questions.length) {
                    document.getElementById("warnMess").innerText = "No more questions adding allowed";
                    this.eraseWarnMess("warnMess");
                } else {
                    if (this.curQuestion.name.trim() === "" || this.curQuestion.answer_1.trim() === ""
                        || this.curQuestion.answer_2.trim() === "") {
                        document.getElementById("warnMess").innerText = "All fields must be completed.";
                        this.eraseWarnMess("warnMess");
                    } else {
                        this.setCheckedValue();
                        this.curQuestion.subject_id = this.subjectId;
                        axios.post("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/question", this.curQuestion)
                            .then(() => {
                                axios.get("http://127.0.0.1:8000/api/quiz/" + this.quizId + "/questions")
                                    .then(resp => resp.data).then(value => {
                                    this.questions = value;
                                    this.prepareFormAfterAction("#createQuestionModal");

                                    axios.get("http://127.0.0.1:8000/api/subject/" + this.subjectId + "/questions")
                                        .then(resp => this.questionsList = resp.data);
                                });
                            });
                    }
                }
            });
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent : 0.25em;

@import "./resources/sass/form_util_btns";
@import "./resources/sass/table";
@import "./resources/sass/routerlink";

.warn-mess {
    margin-top  : $indent * 2;
    margin-left : $indent * 2;
    color       : red;
}

.mess {
    font-size : 15px;
}

input {
    margin-bottom : $indent * 2;
    margin-top    : 0;
}

.question {
    background-color : white;
    border-radius    : 10px;
    padding          : $indent * 3;
    margin           : $indent * 2;
}
</style>
