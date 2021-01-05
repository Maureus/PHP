<template>
    <div>
        <h1 class="text-2xl text-white font-semibold">User's quizzes</h1>
        <Loader v-if="results === []"/>
        <table v-else-if="results.length !== 0" class="table-container">
            <thead>
            <tr>
                <th scope="col">Quiz's name</th>
                <th scope="col">Subject</th>
                <th scope="col">Score</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(result, index) in results" :key="index">
                <td>{{ result.quiz_name }}</td>
                <td>{{ result.subject_name }}</td>
                <td>{{ result.result }}</td>
            </tr>
            </tbody>
        </table>
        <p v-else class="p-2 text-lg text-white font-semibold">No quiz has been passed.</p>
    </div>
</template>

<script>
import Loader from "../Loader";

export default {
    name: "UserQuizzes",
    components: {
        Loader
    },
    data() {
        return {
            userId: this.$route.params.user_id,
            results: []
        }
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/user/" + this.userId + "/results")
            .then(resp => resp.data).then(value => {
            this.results = value;
        });
    }
}
</script>

<style scoped="scoped" lang="scss">
@import "./resources/sass/table";
</style>
