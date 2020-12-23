<template>
    <tr>
        <td>{{ question.name }}</td>
        <td>{{ question.answer_1 }}</td>
        <td>{{ question.answer_2 }}</td>
        <td>{{ question.answer_correct }}</td>
        <td>
            <div class="hover-shadow-effect">
                <button @click="$emit('edit-question', question.id)" data-toggle="modal"
                        data-target="#"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">Edit
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    name: "QuestionItem",
    props: {
        question: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
@import "resources/sass/hover_effects";
</style>
