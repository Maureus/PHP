<template>
    <tr>
        <td>{{ quizName }}</td>
        <td>{{ subjectName }}</td>
        <td>{{ result.result }}</td>
    </tr>
</template>

<script>

export default {
    name: "QuizResult",
    props: {
        result: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            quizName: "",
            subjectName: ""
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/quizzes/" + this.result.quiz_id)
            .then(resp => resp.data).then(async value => {
                this.quizName = value.name;
                await axios.get("http://127.0.0.1:8000/api/subjects/" + value.subject_id)
                    .then(resp => resp.data).then(value => {
                        this.subjectName = value.name;
                    });
            });
    }
}
</script>

<style scoped="scoped" lang="scss">
</style>
