<template>
    <div class="about-container">
        <div class="about">
            This is a web application was made by Nikita Yarosh and Andrii Andrusenko how as a semester work for IDAS2.
        </div>
    </div>
</template>

<script>
export default {
    name: "About.vue"
}
</script>

<style scoped>
.about-container {
    display         : flex;
    align-items     : center;
    justify-content : center;
    height          : 80vh;
}

.about {
    color      : white;
    width      : 60%;
    margin     : auto;
    text-align : center;
    font-size  : 2.5em;
}
</style>
