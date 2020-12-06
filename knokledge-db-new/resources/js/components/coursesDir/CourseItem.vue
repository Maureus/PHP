<template>
    <tr>
        <td>{{ course.name }}</td>
        <td>{{ course.semester }}</td>
        <td>{{ course.year }}</td>
        <td>{{ course.short_name }}</td>
        <td v-if="getUser != null && getUser.role === 'student'" @click="this.$emit('assign-course', course.id)">
            <button class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">{{ option }}
            </button>
        </td>
        <td v-else-if="getUser != null && getUser.role === 'admin'">
            <button class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">{{ option }}
            </button>
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
        },
        option: {
            type: String,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getUser"])
    }
}
</script>

<style scoped="scoped" lang="scss">
$fontSize   : 18px;
$hoverColor : #dde9f5;

button {
    font-size : $fontSize;

    &:hover {
        font-weight                : bold;
        background-color           : darken($color : $hoverColor, $amount : 10%);
        box-shadow                 : darken($color: $hoverColor, $amount: 5%) -1px 1px,
        darken($color: $hoverColor, $amount: 5%) -2px 2px,
        darken($color: $hoverColor, $amount: 5%) -3px 3px,
        darken($color: $hoverColor, $amount: 5%) -4px 4px,
        darken($color: $hoverColor, $amount: 5%) -5px 5px;
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
    font-size : $fontSize;
    padding   : 5px 10px;
}

button {
    &:focus {
        outline : none;
    }
}
</style>
