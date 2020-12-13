<template>
    <div>
        <Confirm :mess="mess"/>
        <h1 class="p-2 text-2xl text-white font-semibold">Quizzes</h1>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="quizzes.length !== 0">
<!--            <div v-if=""-->
<!--                 class="w-100 h-100 inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">-->
<!--                <div class="flex-column items-center justify-center w-1/5 bg-white border-0 rounded">-->
<!--                    <p class="text-center pt-4 text-lg">{{ mess }}</p>-->
<!--                    <div class="flex items-center pt-4 justify-end w-full pr-2">-->
<!--                        <button @click="confirm"-->
<!--                                class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2-->
<!--                            focus:outline-none hover:bg-indigo-600 rounded text-xs mb-2">-->
<!--                            Confirm-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">From</th>
                    <th scope="col">Till</th>
                    <th scope="col">Description</th>
                    <th scope="col">Number of questions</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <QuizItem v-for="quiz in quizzes" :key="quiz.id" :quiz="quiz" :option="option"
                             @edit-quiz="editQuiz" @delete-quiz="deleteQuiz"/>
                </tbody>
            </table>
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
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        editQuiz(quizID) {
            console.log("Quiz is editing");
        },
        deleteQuiz(quizID) {
            axios.delete("http://127.0.0.1:8000/api/quizzes/" + quizID)
                .then(async () => {
                    this.quizzes = this.quizzes.filter(value => value.id !== quizID);
                    this.mess = "Quiz has been deleted.";
                    this.confirm();
                })
                .catch(errors => this.saveErrors(errors));
        }
    },
    computed: {
        ...mapGetters(["getUser", "getTeacherRole", "getStudentRole", "getDeleteOperation", "getEditOperation", "getErrors"])
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
        }

    }
}
</script>

<style scoped="scoped" lang="scss">
$fontSize        : 18px;
$hoverColor      : #dde9f5;
$backgroundColor : white;
$margin          : 10px;

.table-container {
    text-align       : center;
    display          : table;
    background-color : $backgroundColor;
    color            : black;
    font-size        : $fontSize;
    border-radius    : 7px;
    overflow         : hidden;
    border-collapse  : collapse;
    //white-space      : nowrap;
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

</style>
