<template>
    <div>
        <div class="send-msg">
            <textarea v-model="msgText" placeholder="Type here something..." rows="2" maxlength="255"></textarea>
            <button class="send-btn" @click="sendComment" :disabled="msgText === ''">
                <i class="fas fa-paper-plane"></i> Leave comment
            </button>
            <button v-if="msgText !== ''" class="cancel-btn" @click="msgText = ''">Cancel</button>
        </div>

        <hr>

        <Loader v-if="comments === []"/>

        <div v-else-if="comments.length !== 0" class="comments-container">
            <div v-for="comment in comments" :key="comment.id"
                 :class="{'comment-box' : comment.comment_id === null, 'comment-box child' : comment.comment_id !== null}">
                <CommentsItem :comment="comment" @delete-comment="deleteComment" @refresh-comments="refresh"/>
            </div>
        </div>

        <div v-else><p class="text-lg text-white font-semibold">No comments. Be first!;)</p></div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from "vuex";
import CommentsItem from "./CommentsItem";
import Loader from "../Loader";

export default {
    name: "Comments",
    components: {
        CommentsItem, Loader
    },
    data() {
        return {
            comments: [],
            msgText: "",
            subjectId: this.$route.params.subject_id
        }
    },
    computed: {
        ...mapGetters(["getUser"]),
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/subject/" + this.subjectId + "/comments")
            .then(resp => resp.data).then(value => {
                this.comments = value;
            });
    },
    methods: {
        ...mapActions(["confirm"]),
        deleteComment(commentId) {
            axios.delete("http://127.0.0.1:8000/api/comments/" + commentId).then(() => {
                this.comments = this.comments.filter(comment => comment.id !== commentId);
            });
        },
        refresh() {
            axios.get("http://127.0.0.1:8000/api/subject/" + this.subjectId + "/comments")
                .then(resp => resp.data).then(value => {
                this.comments = value;
            });
        },
        sendComment() {
            const comment = {
                text: this.msgText,
                comment_id: null,
                subject_id: this.subjectId
            };
            axios.post("http://127.0.0.1:8000/api/comments", comment).then(resp => {
                axios.get("http://127.0.0.1:8000/api/comments/" + resp.data)
                    .then(resp => resp.data).then(value => {
                    this.comments.push(value);
                    this.msgText = "";
                });
            });
        },
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent        : 0.25em;
$border-radius : 0.3em;

@import "resources/sass/send_comment_btn";

.comments-container {
    padding          : $indent * 5;
    margin           : $indent * 2 0;
    border-radius    : $border-radius;
    overflow         : hidden;
    background-color : white;
}

.comment-box {
    padding : $indent;

    &.child {
        padding      : $indent * 2 $indent;
        margin-right : $indent * 3;
        margin-left  : $indent * 10;
    }
}

textarea {
    border-radius : $border-radius;
    padding       : $indent;
    text-indent   : $indent;
}

hr {
    background-color : white;
}

</style>
