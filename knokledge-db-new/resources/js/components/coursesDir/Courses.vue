<template>
    <div>
        <h1 class="p-2 text-2xl text-white font-semibold">Courses</h1>
        <div class="h-full w-1/5 flex flex-col items-start justify-center" style="float: left; font-size: 18px">
            <div class="list-group">
                <button class="list-group-item list-group-item-action" @click="getYearValue" v-for="(year, id) in years"
                        :key="id" :value="year">{{ year }}
                </button>
            </div>
        </div>
        <preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div style="float: left" v-else-if="this.filteredCourses.length">
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Year</th>
                    <th scope="col">Abbreviation</th>
                    <th scope="col"
                        v-if="(getUser != null && (getUser.role === 'student' || getUser.role === 'admin'))">Option
                    </th>
                </tr>
                </thead>
                <tbody>
                <CourseItem v-for="course in filteredCourses" :key="course.id" :course="course" :option="option"
                            @assign-course="assignCourse"/>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import Preloader from "../Preloader";
import CourseItem from "./CourseItem";

export default {
    name: "Courses",
    components: {
        Preloader, CourseItem
    },
    data() {
        return {
            courses: [],
            loading: true,
            btnYearValue: '',
            years: [],
            option: ''
        }
    },
    methods: {
        ...mapActions(["saveErrors"]),
        getYearValue(event) {
            // console.log(event.target.value);
            this.btnYearValue = event.target.value;
        },
        async assignCourse(subjectId) {
            console.log(subjectId);
            const userId = this.getUser.id;
            console.log(userId);
            await axios.post("http://127.0.0.1:8000/api/users/" + userId + "/subjects/" + subjectId);
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/subjects")
            .then(resp => resp.data)
            .then(value => {
                this.courses = value;
                this.loading = false;
            })
            .catch(errors => this.saveErrors(errors));

        for (let i = 2020; i >= 2018; i--) {
            this.years.push(i + '/' + (i + 1));
            // console.log(i + '/' + (i + 1));
        }
        if (this.getUser != null) {
            switch (this.getUser.role) {
                case 'student' :
                    this.option = 'Write';
                    break;
                case 'admin' :
                    this.option = 'Edit';
                    break;
            }
        }
    },
    computed: {
        ...mapGetters(["getUser"]),
        filteredCourses() {
            return this.courses.filter(value => value.created_at.split("-")[0] === this.btnYearValue.split("/")[0]);
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
