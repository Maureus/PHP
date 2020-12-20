<template>
    <div>
        <StudyMats class="pb-4"/>
        <Quizzes class="pb-4"/>
        <Comments class="pb-4"/>
    </div>
</template>

<script>
import StudyMats from "../studyMats/StudyMats";
import Quizzes from "../quizzesDir/Quizzes";
import Comments from "../comments/Comments";

export default {
    name: "SubjectContent",
    components: {
        StudyMats, Quizzes, Comments
    }
}
</script>

<style scoped>

</style>
