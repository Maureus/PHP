<template>
    <div>
        <h6><span><b>{{ comment.user_name }}</b></span>, <span>{{ comment.created_at }}</span></h6>
        <p>{{ comment.text }}</p>
        <div class="btn-container">
            <button class="btn">
                <i class="fas fa-comment"></i> reply
            </button>
            <!--Don't change == to === while it is working-->
            <button v-if="getUser != null && getUser.id == comment.user_id" class="btn"
                    @click="$emit('edit-comment', comment.id)"><i class="fas fa-edit"></i> edit
            </button>
            <button v-if="getUser != null && (getUser.role === getAdminRole || getUser.id == comment.user_id)"
                    class="btn" @click="$emit('delete-comment', comment.id)">
                <i class="fas fa-trash"></i> delete
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
$indent : 0.25em;

p {
    font-size : 16px;
}

.btn-container {
    font-size : 14px;

    .btn {
        padding : $indent/5;
    }
}
</style>
