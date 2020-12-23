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
                <QuestionItem v-for="question in questions" :key="question" :question="question"/>
                </tbody>
            </table>
        </div>
        <div class="flex w-100 justify-content-end pt-2"
             v-if="getUser && (getUser.role === getAdminRole || getUser.role === getTeacherRole)">
            <button class="btn-primary btn-lg" style="background-color: #1777d4" data-toggle="modal"
                    data-target="#">Add question
            </button>
        </div>
        <div v-else>
            <p class="p-2 text-lg text-white font-semibold">This quiz has not had questions yet.</p>
        </div>
        <Confirm/>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import Loader from "../Loader";
import QuestionItem from "./QuestionItem";
import Confirm from "../Confirm";

export default {
    name: "Quiz",
    components: {
        Loader, QuestionItem, Confirm
    },
    data() {
        return {
            quizId: this.$route.params.quiz_id,
            questions: [],
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
    }
}
</script>

<style scoped="scoped" lang="scss">
$hoverColor      : #dde9f5;
$backgroundColor : white;
$margin          : 10px;

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
</style>
