<template>
    <div>
        <h6><span><b>{{ comment.user_name }}</b></span>, <span class="text-sm">{{ comment.created_at }}</span></h6>
        <p>{{ comment.text }}</p>
        <div class="btn-container">
            <button class="btn" @click="reply" :id="'replyBtn-' + comment.id">
                <i class="fas fa-comment"></i> reply
            </button>
            <!--Don't change == to === while it is working-->
            <button v-if="getUser != null && getUser.id == comment.user_id" class="btn" :id="'editBtn-' + comment.id"
                    @click="editComment">
                <i class="fas fa-edit"></i> edit
            </button>
            <button v-if="getUser != null && (getUser.role === getAdminRole || getUser.id == comment.user_id)"
                    class="btn" @click="$emit('delete-comment', comment.id)">
                <i class="fas fa-trash"></i> delete
            </button>
        </div>

        <div style="display: none" class="send-msg" :id="'textarea-' + comment.id">
            <textarea v-model="msgText" placeholder="Type here something..." rows="1" maxlength="255"
                      @input="autoGrowTextArea($event.target)"></textarea>
            <button v-if="btnOption === 'answer'" class="send-btn" :disabled="msgText === ''" @click="answer">
                <i class="fas fa-paper-plane"></i> Answer
            </button>
            <button v-else-if="btnOption === 'edit'" class="send-btn" :disabled="msgText === ''" @click="edit">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button class="cancel-btn" @click="cancelAnswer">Cancel</button>
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
    data() {
        return {
            msgText: "",
            curCommentId: "",
            subjectId: this.$route.params.subject_id,
            btnOption: ""
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole"])
    },
    methods: {
        reply(event) {
            this.btnOption = "answer";
            this.curCommentId = event.target.id.split('-')[1];
            document.getElementById("textarea-" + this.curCommentId).style.display = "block";
            this.msgText = this.comment.user_name + ", ";
        },
        answer() {
            const comment = {
                text: this.msgText,
                comment_id: this.curCommentId,
                subject_id: this.subjectId
            };
            axios.post("http://127.0.0.1:8000/api/comments", comment).then(() => {
                this.cancelAnswer();
                this.$emit("refresh-comments");
            });
        },
        editComment(event) {
            this.btnOption = "edit";
            this.curCommentId = event.target.id.split('-')[1];
            document.getElementById("textarea-" + this.curCommentId).style.display = "block";
            this.msgText = this.comment.text;
        },
        edit() {
            const comment = {
                text: this.msgText
            };
            axios.put("http://127.0.0.1:8000/api/comments/" + this.curCommentId, comment).then(() => {
                this.cancelAnswer();
                this.$emit("refresh-comments");
            });
        },
        cancelAnswer() {
            this.msgText = "";
            document.getElementById("textarea-" + this.curCommentId).style.display = "none";
        },
        autoGrowTextArea(element) {
            element.style.height = "5px";
            element.style.height = (element.scrollHeight) + "px";
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$indent        : 0.25em;
$border-radius : 0.3em;

@import "resources/sass/send_comment_btn";

p {
    font-size : 16px;
}

.btn-container {
    font-size : 14px;

    .btn {
        padding : $indent / 5;
    }
}

textarea {
    border-bottom : 2px solid black;
    height        : auto;
}

.send-msg {
    .cancel-btn {
        color : black;
    }
}

</style>
