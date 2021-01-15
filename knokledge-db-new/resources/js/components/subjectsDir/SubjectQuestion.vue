<template>
    <div>
        <button class="btn-primary btn-lg" style="background-color: #1777d4" data-toggle="modal"
                data-target="#addQuestionToSubjectModal">Add new question to list
        </button>

        <div class="modal fade" id="addQuestionToSubjectModal" tabindex="-1" role="dialog"
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
                        <div class="warn-mess"><p id="warnMessCreation" class="mess"></p></div>
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
    </div>
</template>

<script>
export default {
    name: "SubjectQuestion",
    data() {
        return {
            subjectId: this.$route.params.subject_id,
            curQuestion: {
                name: "",
                answer_1: "",
                answer_2: "",
                answer_correct: "",
                id: "",
                subject_id: ""
            }
        }
    },
    methods: {
        cancel() {
            this.clearForm();
        },
        clearForm() {
            document.getElementById("warnMessCreation").innerText = "";
            this.curQuestion.name = this.curQuestion.answer_1 = this.curQuestion.answer_2 = "";
            if (document.getElementById("answer2Radio").checked) {
                document.getElementById("answer2Radio").checked = false;
            }
            document.getElementById("answer1Radio").checked = true;
        },
        setCheckedValue() {
            const elements = document.getElementsByName("correctAnswer");
            elements.forEach(el => {
                if (el.checked) {
                    this.curQuestion.answer_correct = el.value;
                }
            });
        },
        prepareFormAfterAction(modalId) {
            $(modalId).modal('hide');
            this.clearForm();
            this.confirm();
        },
        eraseWarnMess(id) {
            setTimeout(() => {
                document.getElementById(id).innerText = "";
            }, 3000);
        },
        createNewQuestion() {
            if (this.curQuestion.name.trim() === "" || this.curQuestion.answer_1.trim() === ""
                || this.curQuestion.answer_2.trim() === "") {
                document.getElementById("warnMessCreation").innerText = "All fields must be completed.";
                this.eraseWarnMess("warnMessCreation");
            } else {
                this.setCheckedValue();
                this.curQuestion.subject_id = this.subjectId;
                axios.post("http://127.0.0.1:8000/api/questions", this.curQuestion)
                    .then(() => this.prepareFormAfterAction("#addQuestionToSubjectModal"));
            }
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent : 0.25em;
@import "./resources/sass/form_util_btns";

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
</style>
