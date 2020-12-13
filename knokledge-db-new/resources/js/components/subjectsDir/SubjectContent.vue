<template>
    <div>
        <h1 class="p-2 text-2xl text-white font-semibold">Study materials</h1>
        <StudyMats/>
    </div>
</template>

<script>
import StudyMats from "../studyMats/StudyMats";

export default {
    name: "SubjectContent",
    components: {
        StudyMats
    },
    data() {
        return {}
    }
}
</script>

<style scoped>

</style>
