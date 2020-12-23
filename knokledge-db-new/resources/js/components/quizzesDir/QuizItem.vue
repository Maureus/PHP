<template>
    <tr>
        <td>
            <div v-if="getUser != null && (getUser.role === getTeacherRole || getUser.role === getAdminRole)"
                 class="hover-shadow-effect" style="padding: 0.75rem">
                <router-link class="whitespace-no-wrap text-right text-base leading-5 font-medium"
                             title="Click to open subject's detail"
                             :to="{name: 'Quiz', params: {quiz_id: quiz.id}}">{{ quiz.name }}
                </router-link>
            </div>
            <div v-if="!getUser">{{ quiz.name }}</div>
        </td>
        <td>{{ quiz.date_from }}</td>
        <td>{{ quiz.date_till }}</td>
        <td>{{ quiz.quiz_desc }}</td>
        <td>{{ quiz.num_questions }}</td>
        <td v-if="getUser.role === getStudentRole">
            <div class="hover-shadow-effect">
                <button @click="takeQuiz" :value="take"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ getTakeOperation | capitalizer }}
                </button>
            </div>
        </td>
        <td v-if="getUser.role === getTeacherRole || getUser.role === getAdminRole">
            <div class="hover-shadow-effect">
                <button @click="setNewItem" :value="edit" data-toggle="modal" data-target="#modalQuiz"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ getEditOperation | capitalizer }}
                </button>
            </div>
        </td>
        <td v-if="getUser.role === getTeacherRole || getUser.role === getAdminRole">
            <div class="hover-shadow-effect">
                <button @click="deleteQuiz"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ getDeleteOperation | capitalizer }}
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
import {mapGetters, mapActions} from "vuex";

export default {
    name: "QuizItem",
    data() {
        return {
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
            , "getWriteOperation", "getEditOperation", "getDeleteOperation", "getTakeOperation"
            , "getStudentRole", "getAdminRole", "getTeacherRole", "getQuiz"])
    },
    methods: {
        ...mapActions(['saveQuiz', 'saveErrors', 'saveQuiz']),
        takeQuiz() {
            this.$router.push({name: 'Quiz', params: {quiz_id: this.quiz.id}});
        },
        deleteQuiz() {
            axios.delete('http://127.0.0.1:8000/api/quizzes/' + this.quiz.id).then(res => {
                this.$emit('delete-quiz', this.quiz.id);
            }).catch(err => {
                this.saveErrors(err);
            });
        },
        setNewItem() {
            let newItem = JSON.parse(JSON.stringify(this.quiz));
            newItem.date_from = newItem.date_from.split(" ").join("T");
            newItem.date_till = newItem.date_till.split(" ").join("T")
            this.saveQuiz(newItem);
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
$hoverColor : #dde9f5;
$margin     : 10px;

@import "./resources/sass/hover_effects";

th {
    color            : white;
    padding          : 5px 10px;
    background-color : darken($color : #187fe2, $amount : 3%);
}

td {
    padding : 5px 10px;
}

</style>
