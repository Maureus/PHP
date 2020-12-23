<template>
    <tr>
        <td>{{ question.name }}</td>
        <td>{{ question.answer_1 }}</td>
        <td>{{ question.answer_2 }}</td>
        <td>{{ question.answer_correct }}</td>
        <td>
            <div class="hover-shadow-effect">
                <button @click="$emit('edit-question', question.id)" data-toggle="modal"
                        data-target="#editQuestion"
                        class="px-4 py-3 whitespace-no-wrap text-right text-base leading-5 font-medium">Edit
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
$padding : 10px;
@import "resources/sass/hover_effects";

td {
    padding       : $padding;
    overflow-wrap : break-word;
}
</style>
