<template>
    <div>
        <StudyMats class="pb-4"/>
        <Quizzes/>
        <Comments/>
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
