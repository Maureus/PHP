<template>
    <div>
        <Confirm :mess="mess"/>
        <h1 class="p-2 text-2xl text-white font-semibold">My courses</h1>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-else-if="userCourses.length !== 0">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Year</th>
                    <th scope="col">Abbreviation</th>
                    <th scope="col">Study materials</th>
                    <th scope="col">Option</th>
                </tr>
                </thead>
                <tbody>
                <CourseItem v-for="course in userCourses" :key="course.id" :course="course" :option="option"
                            @edit-course="editCourse" @delete-course-in-user="deleteCourseInUser"/>
                </tbody>
            </table>
        </div>
        <p v-else-if="loading === false" class="p-2 text-lg text-white font-semibold">You don't have any course.
            <span v-if="getUser != null && getUser.role === getStudentRole">
                Click <router-link to="/dashboard/courseslist" class="link">here</router-link> to write a course.
            </span>
            <!--TODO think about teacher's role-->
            <span v-else-if="getUser != null && getUser.role === getTeacherRole">
                Click <router-link to="/" class="link">here</router-link> for applying to start a course.
            </span>
        </p>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import Preloader from "../Preloader";
import CourseItem from "./CourseItem";
import Confirm from "../Confirm";

export default {
    name: "MyCourses",
    components: {
        Preloader, CourseItem, Confirm
    },
    data() {
        return {
            loading: true,
            userCourses: [],
            option: '',
            mess: ''
        }
    },
    methods: {
        ...mapActions(["saveErrors", "confirm", "getLoggedInUser"]),
        editCourse(subjectId) {
            console.log("Course is editing");
        },
        deleteCourseInUser(subjectId) {
            const userId = this.getUser.id;
            axios.delete("http://127.0.0.1:8000/api/users/" + userId + "/subjects/" + subjectId)
                .then(async () => {
                    this.userCourses = this.userCourses.filter(value => value.id !== subjectId);
                    this.mess = "Course has been deleted.";
                    this.confirm();
                })
                .catch(errors => this.saveErrors(errors));
        }
    },
    computed: {
        ...mapGetters(["getUser", "getTeacherRole", "getStudentRole", "getDeleteOperation", "getEditOperation"])
    },
    async mounted() {
        if (this.getUser == null) {
            await this.getLoggedInUser();
            // console.log(this.getUser)
            // if (this.getUser == null) {
            //TODO change it
            // await this.$router.push({name: 'Login'});
            // }
        }

        const userId = this.getUser.id;
        axios.get("http://127.0.0.1:8000/api/users/" + userId + "/subjects")
            .then(resp => resp.data)
            .then(value => {
                this.userCourses = value;
                this.loading = false;
            })
            .catch(errors => this.saveErrors(errors));

        switch (this.getUser.role) {
            case this.getStudentRole :
                this.option = this.getDeleteOperation;
                break;
            case this.getTeacherRole :
                this.option = this.getEditOperation;
                break;
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

.link {
    color : #cae9ff;

    &:hover {
        text-decoration : underline #cae9ff;
    }
}
</style>
