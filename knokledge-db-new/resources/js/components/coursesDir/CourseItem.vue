<template>
    <tr>
        <td>{{ course.name }}</td>
        <td>{{ course.semester }}</td>
        <td>{{ course.year }}</td>
        <td>{{ course.short_name }}</td>
        <td v-if="getUser && option !== ''">
            <button @click="subjectUtility($event.target.value)" :value="option"
                    class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                {{ option | capitalizer }}
            </button>
        </td>
        <!--        <td v-else-if="getUser != null && getUser.role === 'admin'">-->
        <!--            <button class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">-->
        <!--                {{ option }}-->
        <!--            </button>-->
        <!--        </td>-->
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
        ...mapGetters(["getUser"
            , "getWriteOperation", "getEditOperation", "getDeleteOperation"
            , "getStudentRole", "getAdminRole", "getTeacherRole"])
    },
    methods: {
        subjectUtility(value) {
            // console.log(value);
            switch (value) {
                case this.getWriteOperation :
                    this.$emit('assign-course', this.course.id);
                    break;
                case this.getEditOperation :
                    this.$emit('edit-course', this.course.id);
                    break;
                case this.getDeleteOperation :
                    this.$emit('delete-course-in-user', this.course.id);
                    break;
            }
        }
    },
    filters: {
        capitalizer(value) {
            return value ? value.replace(/\b\w/g, function (character) {
                return character.toUpperCase();
            }) : "";
        }
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
