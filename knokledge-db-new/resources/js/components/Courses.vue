<template>
    <div>
        <h1>Courses</h1>
        <preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <table v-else-if="this.courses.length !== 0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Semester</th>
                <th>Year</th>
                <th>Abbreviation</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="course in courses" :key="course.id">
                <td>{{ course.name }}</td>
                <td>{{ course.semester }}</td>
                <td>{{ course.year }}</td>
                <td>{{ course.short_name }}</td>
                <td>
                    <button style="background-color: white; padding: 10px">More</button>
                </td>
            </tr>
            </tbody>
        </table>
        <!--        <div v-for="course in courses">-->
        <!--            <p>{{ course.name }}</p>-->
        <!--        </div>-->
        <p v-else>Ups... Something went wrong \/(^_^)\/</p>
    </div>
</template>

<script>
import {mapActions} from 'vuex';
import Preloader from "./Preloader";

export default {
    name: "Courses",
    components: {
        Preloader
    },
    data() {
        return {
            courses: [],
            loading: true
        }
    },
    methods: {
        ...mapActions(["saveErrors"]),
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/subjects")
            .then(resp => resp.data)
            .then(value => {
                this.courses = value;
                this.loading = false;
            })
            .catch(errors => this.saveErrors(errors));
        // await fetch("http://127.0.0.1:8000/api/subjects").then(value => value.json()).then(json => this.courses = json).catch(errors => this.saveErrors(errors));
    }
}
</script>

<style scoped>

</style>
