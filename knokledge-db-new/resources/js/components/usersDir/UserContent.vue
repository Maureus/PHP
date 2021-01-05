<template>
    <div>
        <router-link class="link" to="/userlist">Back</router-link>
        <UserQuizzes/>
    </div>
</template>

<script>
import UserQuizzes from "./UserQuizzes";

export default {
    name: "UserContent",
    components: {
        UserQuizzes
    }
}
</script>

<style scoped="scoped" lang="scss">
@import "./resources/sass/routerlink";
</style>
