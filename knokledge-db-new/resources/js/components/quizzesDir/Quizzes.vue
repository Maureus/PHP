<template>
    <div>
        <Confirm :mess="mess"/>
        <h1 class="p-2 text-2xl text-white font-semibold">My courses</h1>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="userCourses.length !== 0">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">From</th>
                    <th scope="col">Till</th>
                    <th scope="col">Description</th>
                    <th scope="col">Number of questions</th>
                    <!--                    <th scope="col">Study materials</th>-->
                    <th scope="col">Option</th>
                </tr>
                </thead>
                <tbody>
                <QuizItem v-for="quiz in quizzes" :key="quiz.id" :quiz="quiz" :option="option"
                             @edit-course="editCourse" @delete-course-in-user="deleteCourseInUser"/>
                </tbody>
            </table>
        </div>
        <p v-else-if="loading === false" class="p-2 text-lg text-white font-semibold">You don't have any subject.
            <span v-if="getUser != null && getUser.role === getStudentRole">
                Click <router-link to="/subjectlist" class="link">here</router-link> to write a subject.
            </span>
            <!--TODO think about teacher's role-->
            <span v-else-if="getUser != null && getUser.role === getTeacherRole">
                Click <router-link to="/" class="link">here</router-link> for applying to start a subject.
            </span>
        </p>
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
            subject_id: '',
            option: ''
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        editCourse(subjectId) {
            console.log("Course is editing");
        },
        deleteCourseInUser(subjectId) {
            const userId = this.getUser.id;
            axios.delete("http://127.0.0.1:8000/api/users/" + userId + "/subjects/" + subjectId)
                .then(async () => {
                    this.userCourses = this.userCourses.filter(value => value.id !== subjectId);
                    this.mess = "Course has been deleted.";
                    this.confirm();
                })
                .catch(errors => this.saveErrors(errors));
        }
    },
    computed: {
        ...mapGetters(["getUser", "getTeacherRole", "getStudentRole", "getDeleteOperation", "getEditOperation", "getErrors"])
    },
    async mounted() {
        this.loading = true;
        const userId = this.getUser.id;
        axios.get("http://127.0.0.1:8000/api/users/" + userId + "/subjects")
            .then(resp => {
                this.quizzes = resp.data;
                this.loading = false;
            })
            .catch(errors => this.saveErrors(errors));

        switch (this.getUser.role) {
            case this.getStudentRole :
                this.option = this.getDeleteOperation;
                break;
            case this.getTeacherRole :
                this.option = this.getEditOperation;
                break;
        }

    }
}
</script>

<style scoped>

</style>
