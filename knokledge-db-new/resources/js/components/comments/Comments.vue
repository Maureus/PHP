<template>
    <div>
        <div class="comments-container">
            <Confirm/>
            <div v-for="comment in filteredParentComments" :key="comment.id" class="comment-box">
                <CommentsItem :comment="comment" @delete-comment="deleteComment"/>
                <div v-for="childComment in filteredChildComments(comment.id)" :key="childComment.id"
                     class="comment-box child">
                    <CommentsItem :comment="childComment" @delete-comment="deleteComment"/>
                </div>
            </div>
        </div>
        <hr style="background-color: white">
        <div class="send-msg">
            <textarea v-model="msgText" placeholder="Type here something..." rows="2" maxlength="255"></textarea>
            <button class="send-btn" @click="sendComment" :disabled="msgText === ''">
                <i class="fas fa-paper-plane"></i> Send
            </button>
            <button v-if="msgText !== ''" class="cancel-btn" @click="msgText = ''">Cancel</button>
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
            msgText: ""
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
                this.comments = this.comments.filter(comment => comment.id !== commentId);
            });
        },
        filteredChildComments(parentId) {
            return this.comments.filter(comment => comment.comment_id === parentId);
        },
        sendComment() {
            const comment = {
                text: this.msgText,
                comment_id: null,
                subject_id: this.$route.params.subject_id
            };
            axios.post("http://127.0.0.1:8000/api/comments", comment).then(resp => {
                axios.get("http://127.0.0.1:8000/api/comments/" + resp.data)
                    .then(resp => resp.data).then(value => {
                    this.comments.push(value);
                    this.msgText = "";
                });
            });
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent        : 0.25em;
$border-radius : 0.3em;

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

.send-msg {
    margin : $indent * 2 0;

    textarea {
        width         : 100%;
        border-radius : $border-radius;
        padding       : $indent;
        text-indent   : $indent;

        &:focus {
            outline : none;
        }
    }

    .send-btn {
        padding          : $indent;
        margin-bottom    : $indent;
        border-radius    : $border-radius;
        color            : white;
        background-color : #a9d5ec;
        min-width        : 10em;

        &:disabled {
            filter : grayscale(0.5);
            cursor : default;
        }
    }

    .cancel-btn {
        color        : white;
        margin-right : $indent;
        margin-left  : $indent;
        padding      : $indent $indent * 3;
    }
}

button {
    &:focus {
        outline : none;
    }
}

</style>
