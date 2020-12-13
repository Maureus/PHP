<template>
    <tr>
        <td>{{ quiz.name }}</td>
        <td>{{ quiz.date_from }}</td>
        <td>{{ quiz.date_till }}</td>
        <td>{{ quiz.quiz_desc }}</td>
        <td>{{ quiz.num_questions }}</td>
        <td v-if="getUser && option === 'student'">
            <div class="hover-shadow-effect">
                <button @click="subjectUtility($event.target.value)" :value="take"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ "take" | capitalizer }}
                </button>
            </div>
        </td>
        <td v-if="getUser && (option === 'teacher' || option === 'admin')">
            <div class="hover-shadow-effect">
                <button @click="subjectUtility($event.target.value)" :value="edit"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium btn-">
                    {{ 'edit' | capitalizer }}
                </button>
            </div>
            <div class="hover-shadow-effect">
                <button @click="subjectUtility($event.target.value)" :value="option"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ 'delete' | capitalizer }}
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
import {mapGetters, mapActions} from "vuex";

export default {
    name: "QuizItem",
    data () {
        return  {
            edit: 'edit',
            delete: 'delete',
            take: 'take'
        }
    },
    props: {
        quiz: {
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
            , "getStudentRole", "getAdminRole", "getTeacherRole", "getQuiz"])
    },
    methods: {
        ...mapActions(['saveQuiz']),
        subjectUtility(value) {
            switch (value) {
                case this.edit :
                    this.$emit('edit-quiz', this.quiz.id);
                    break;
                case this.delete :
                    this.$emit('delete-quiz', this.quiz.id);
                    break;
                case this.take :
                    this.$router.push({name: 'Quiz', params: {quiz_id: this.quiz.id} });
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

* {
    font-size : $fontSize;
}

.hover-shadow-effect {
    background-color: #6cb2eb;
    color: white;
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
    padding : 5px 10px;
}

button {
    &:focus {
        outline : none;
    }
}
</style>
