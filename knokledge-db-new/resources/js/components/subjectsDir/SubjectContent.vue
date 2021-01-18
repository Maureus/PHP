<template>
    <div>
        <router-link class="link" to="/subjectlist">Back</router-link>
        <StudyMats class="pb-4"/>
        <Quizzes class="pb-4"/>
        <SubjectQuestion v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"/>
        <Comments class="pb-4"/>
    </div>
</template>

<script>
import StudyMats from "../studyMats/StudyMats";
import Quizzes from "../quizzesDir/Quizzes";
import Comments from "../comments/Comments";
import SubjectQuestion from "./SubjectQuestion";
import {mapGetters} from "vuex";

export default {
    name: "SubjectContent",
    components: {
        StudyMats, Quizzes, Comments, SubjectQuestion
    },
    computed: {
        ...mapGetters(["getStudentRole", "getTeacherRole", "getAdminRole", "getUser"]),
    }
}
</script>

<style scoped="scoped" lang="scss">
@import "./resources/sass/routerlink";
</style>
