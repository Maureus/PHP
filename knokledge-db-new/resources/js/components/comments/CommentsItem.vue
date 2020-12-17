<template>
    <div>
        <h6><span><b>{{ comment.user_name }}</b></span>, <span>{{ comment.created_at }}</span></h6>
        <p>{{ comment.text }}</p>
        <div>
            <button>reply</button>
            <button>edit</button>
            <button @click="$emit('delete-comment', comment.id)">delete</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "CommentsItem",
    props: {
        comment: {
            type: Object,
            required: true
        }
    },
    filters: {
        dateFormat(value) {
            if (!value) {
                return '';
            }
            const date = new Date(value);
            // console.log(date);
            return value;
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
</style>
