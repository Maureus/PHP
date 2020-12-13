<template>
    <div>
        <h1 class="p-2 text-2xl text-white font-semibold">Study materials</h1>
        <StudyMats class="pb-4"/>
        <Quizzes/>
        <button @click="getAppSize">SIZE</button>
    </div>
</template>

<script>
import StudyMats from "../studyMats/StudyMats";
import Quizzes from "../quizzesDir/Quizzes";

export default {
    name: "SubjectContent",
    components: {
        StudyMats, Quizzes
    },
    data() {
        return {
        }
    },
    methods: {
        getAppSize() {
            console.log(document.getElementById('app').clientHeight);
        }
    }
}
</script>

<style scoped>

</style>
