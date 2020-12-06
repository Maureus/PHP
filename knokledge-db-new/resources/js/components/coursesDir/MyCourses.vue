<template>
    <div>
        <h1 class="p-2 text-2xl text-white font-semibold">My courses</h1>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="this.userCourses.length">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Year</th>
                    <th scope="col">Abbreviation</th>
                    <th scope="col">Option</th>
                </tr>
                </thead>
                <tbody>
                <CourseItem v-for="course in userCourses" :key="course.id" :course="course" :option="option"/>
                </tbody>
            </table>
        </div>
        <p v-else class="p-2 text-xl text-white font-semibold">You don't have any course.
            <router-link to="" v-if="this.getUser != null && this.getUser.role === 'teacher'">Apply to start a course
            </router-link>
        </p>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import Preloader from "../Preloader";
import CourseItem from "./CourseItem";

export default {
    name: "MyCourses",
    components: {
        Preloader, CourseItem
    },
    data() {
        return {
            loading: true,
            userCourses: [],
            option: ''
        }
    },
    methods: {
        ...mapActions(["saveErrors"]),
    },
    computed: {
        ...mapGetters(['getUser']),
        setOption() {
            if (this.getUser != null) {
                switch (this.getUser.role) {
                    case 'student' :
                        this.option = 'delete';
                        break;
                    case 'teacher' :
                        this.option = 'edit';
                        break;
                }
            }
        }
    },
    async mounted() {
        if (this.getUser == null) {
            await this.$router.push({name: 'Login'});
        } else {
            const userId = this.getUser.id;
            await axios.get("http://127.0.0.1:8000/api/users/" + userId + "/subjects")
                .then(resp => resp.data)
                .then(value => {
                    this.userCourses = value;
                    this.loading = false;
                })
                .catch(errors => this.saveErrors(errors));

            switch (this.getUser.role) {
                case 'student' :
                    this.option = 'Delete';
                    break;
                case 'teacher' :
                    this.option = 'Edit';
                    break;
            }
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$fontSize        : 18px;
$hoverColor      : #dde9f5;
$backgroundColor : white;

.table-container {
    text-align       : center;
    display          : table;
    background-color : $backgroundColor;
    color            : black;
    font-size        : $fontSize;
    border-radius    : 7px;
    overflow         : hidden;
    border-collapse  : collapse;

    tr {
        margin      : 5px 0;
        line-height : 2.1875em;

        &:nth-child(odd) {
            background-color : $backgroundColor;
        }

        &:nth-child(even) {
            background-color : darken($color : $backgroundColor, $amount : 5%);
        }

        &:hover {
            background-color : darken($color : $hoverColor, $amount : 2%);
        }
    }

    th {
        padding          : 5px 10px;
        color            : white;
        background-color : darken($color : #187fe2, $amount : 3%);
    }
}

button {
    &:focus {
        outline : none;
    }
}
</style>
