<template>
    <div>
        <h6><span><b>{{ comment.user_name }}</b></span>, <span>{{ comment.created_at }}</span></h6>
        <p>{{ comment.text }}</p>
        <div class="btn-container">
            <button>reply</button>
            <!--Don't change == to === while it is working-->
            <button v-if="getUser != null && getUser.id == comment.user_id"
                    @click="$emit('edit-comment', comment.id)">edit
            </button>
            <button v-if="getUser != null && (getUser.role === getAdminRole || getUser.id == comment.user_id)"
                    @click="$emit('delete-comment', comment.id)">delete
            </button>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "CommentsItem",
    props: {
        comment: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole"])
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

.btn-container {

}
</style>
