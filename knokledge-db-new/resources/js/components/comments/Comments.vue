<template>
    <div class="comments-container">
        <div v-for="comment in filteredParentComments" :key="comment.id" class="comment-box">
            <CommentsItem :comment="comment"/>

            <div v-for="childComment in filteredChildComments(comment.id)" :key="childComment.id"
                 class="comment-box child">
                <CommentsItem :comment="childComment"/>
            </div>
        </div>
        <div>
            <textarea placeholder="Type here something..."></textarea>
            <button>Send</button>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from "vuex";
import CommentsItem from "./CommentsItem";
import Confirm from "../Confirm";

export default {
    name: "Comments",
    components: {
        CommentsItem, Confirm
    },
    data() {
        return {
            comments: [],
        }
    },
    computed: {
        ...mapGetters(["getUser"]),
        filteredParentComments() {
            return this.comments.filter(comment => comment.comment_id == null);
        },
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/comments").then(resp => resp.data).then(value => {
            this.comments = value;
        });
    },
    methods: {
        ...mapActions(["confirm"]),
        deleteComment(commentId) {
            axios.delete("http://127.0.0.1:8000/api/comments/" + commentId).then(() => {
                this.comments = this.comments.filter(commentId => commentId.id !== commentId);
                this.confirm();
            })
        },
        filteredChildComments(parentId) {
            return this.comments.filter(comment => comment.comment_id === parentId);
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent : 5px;

.comment-box {
    padding-top      : $indent * 2;
    padding-left     : $indent * 3;
    background-color : white;
    margin-top       : $indent;
    margin-bottom    : $indent;

    &.child {
        margin-right : $indent * 3;
        margin-left  : $indent * 10;
        border       : 1px solid black;
    }
}

.comments-container {

}
</style>
