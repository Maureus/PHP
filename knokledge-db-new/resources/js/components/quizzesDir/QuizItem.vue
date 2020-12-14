<template>
    <tr>
        <td>{{ quiz.name }}</td>
        <td>{{ quiz.date_from }}</td>
        <td>{{ quiz.date_till }}</td>
        <td>{{ quiz.quiz_desc }}</td>
        <td>{{ quiz.num_questions }}</td>
        <td v-if="getUser && option === 'student'">
            <div class="hover-shadow-effect">
                <button @click="takeQuiz" :value="take"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ "take" | capitalizer }}
                </button>
            </div>
        </td>
        <td v-if="getUser && (option === 'teacher' || option === 'admin')">
            <div class="hover-shadow-effect">
                <button :value="edit"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium btn-"
                        data-toggle="modal"
                        data-target="#modalQuiz">
                    {{ 'edit' | capitalizer }}
                </button>
            </div>
        </td>
        <td v-if="getUser && (option === 'teacher' || option === 'admin')">
            <div class="hover-shadow-effect">
                <button @click="deleteQuiz"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ 'delete' | capitalizer }}
                </button>
            </div>
        </td>

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
                            <input v-model="quiz.name" name="name" required
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date from
                            </label>
                            <input name="email" v-model="quiz.date_from" type="date"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Date till
                            </label>
                            <input v-model="quiz.date_till" type="date"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Description
                            </label>
                            <input v-model="quiz.quiz_desc" name="address"
                                   class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                Number of questions
                            </label>
                            <input v-model="quiz.num_questions" name="address"
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
    </tr>
</template>

<script>
import {mapGetters, mapActions} from "vuex";

export default {
    name: "QuizItem",
    data() {
        return {
            edit: 'edit',
            delete: 'delete',
            take: 'take'
        }
    },
    props: {
        quiz: {
            type: Object,
            required: true
        },
        option: {
            type: String,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getUser"
            , "getWriteOperation", "getEditOperation", "getDeleteOperation"
            , "getStudentRole", "getAdminRole", "getTeacherRole", "getQuiz"])
    },
    methods: {
        ...mapActions(['saveQuiz', 'saveErrors']),
        takeQuiz() {
            this.$router.push({name: 'Quiz', params: {quiz_id: this.quiz.id}});
        },
        saveQuiz() {
            axios.put('http://127.0.0.1:8000/api/quizzes/' + this.quiz.id, this.quiz).then(res => {
                this.$emit('edit-quiz', this.quiz.id);
            }).catch(err => {
                this.saveErrors(err);
            });
        },
        deleteQuiz() {
            axios.delete('http://127.0.0.1:8000/api/quizzes/' + this.quiz.id).then(res => {
                this.$emit('delete-quiz', this.quiz.id);
            }).catch(err => {
                this.saveErrors(err);
            });
        }
    },
    filters: {
        capitalizer(value) {
            return value ? value.replace(/\b\w/g, function (character) {
                return character.toUpperCase();
            }) : "";
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$hoverColor : #dde9f5;
$margin     : 10px;

@import "./resources/sass/hover_effects";

th {
    color            : white;
    padding          : 5px 10px;
    background-color : darken($color : #187fe2, $amount : 3%);
}

td {
    padding : 5px 10px;
}

button {
    &:focus {
        outline : none;
    }
}

.btn-container {
    display : flex;
}

.btn-box {
    padding-top : 50px;

    &.start {
        text-align : start;
        width      : 80%;
    }

    &.end {
        text-align   : end;
        margin-right : 0.5rem;
        width        : 20%;
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

    &:focus {
        outline : none;
    }
}
</style>
