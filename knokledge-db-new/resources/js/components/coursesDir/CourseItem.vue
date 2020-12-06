<template>
    <tr>
        <td>{{ course.name }}</td>
        <td>{{ course.semester }}</td>
        <td>{{ course.year }}</td>
        <td>{{ course.short_name }}</td>
        <td v-if="getUser != null && getUser.role === 'student'">
            <button class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">Write</button>
        </td>
        <td v-else-if="getUser != null && getUser.role === 'admin'"
            class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
            <button class="text-indigo-600 hover:text-indigo-900">Edit</button>
        </td>
    </tr>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "CourseItem",
    props: {
        course: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getUser"])
    },
}
</script>

<style scoped="scoped" lang="scss">
button {
    font-size : 18px;

    &:hover {
        background-color           : #ccc;
        font-weight                : bold;
        box-shadow                 : #ccc -1px 1px,
        darken($color: #ccc, $amount: 2%) -2px 2px,
        darken($color: #ccc, $amount: 4%) -3px 3px,
        darken($color: #ccc, $amount: 6%) -4px 4px,
        darken($color: #ccc, $amount: 8%) -5px 5px;
        transform                  : translate3d(5px, -5px, 0);

        transition-delay           : 0s;
        transition-duration        : 0.5s;
        transition-property        : all;
        transition-timing-function : linear;
    }
}

th {
    color            : white;
    padding          : 5px 10px;
    background-color : darken($color : #187fe2, $amount : 3%);
}

td {
    font-size : 18px;
    padding   : 5px 10px;
}
</style>
