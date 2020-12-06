<template>
    <tr>
        <td>{{ course.name }}</td>
        <td>{{ course.semester }}</td>
        <td>{{ course.year }}</td>
        <td>{{ course.short_name }}</td>
        <td v-if="getUser != null && getUser.role === 'student'">
            <button>Write</button>
        </td>
    </tr>
</template>

<script>
export default {
    name: "CourseItem",
    props: {
        course: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped>
td {
    padding-left: 10px;
    padding-right: 10px;
}
</style>
