<template>
    <tr>
        <td>
            <div v-if="getUser" class="hover-shadow-effect" style="padding: 1rem 1.25rem">
                <router-link
                    class="whitespace-no-wrap text-right text-base leading-5 font-medium underline"
                    title="Click to open subject's detail"
                    :to="{name: 'SubjectContent', params: {subject_id: subject.id} }">{{ subject.name }}
                </router-link>
            </div>
            <span v-if="!getUser">{{ subject.name }}</span>
        </td>
        <td>{{ subject.semester }}</td>
        <td>{{ subject.year }}</td>
        <td>{{ subject.short_name }}</td>
        <!--        <td v-if="getUser">-->
        <!--            <button class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium"-->
        <!--                    @click="showStudyMats">-->
        <!--                Watch materials-->
        <!--            </button>-->
        <!--        </td>-->
        <td v-if="getUser && option !== ''">
            <div class="hover-shadow-effect">
                <button @click="subjectUtility($event.target.value)" :value="option"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ option | capitalizer }}
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "SubjectItem",
    props: {
        subject: {
            type: Object,
            required: true
        },
        option: {
            type: String,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getUser"
            , "getWriteOperation", "getEditOperation", "getDeleteOperation"
            , "getStudentRole", "getAdminRole", "getTeacherRole"])
    },
    methods: {
        subjectUtility(value) {
            // console.log(value);
            switch (value) {
                case this.getWriteOperation :
                    this.$emit('assign-course', this.subject.id);
                    break;
                case this.getEditOperation :
                    this.$emit('edit-course', this.subject.id);
                    break;
                case this.getDeleteOperation :
                    this.$emit('delete-course-in-user', this.subject.id);
                    break;
            }
        },
        // showStudyMats() {
        //     if (this.subject != null && this.subject.id) {
        //         this.$router.push({name: "SubjectContent", params: {subject_id: this.subject.id}});
        //     }
        // }
    },
    filters: {
        capitalizer(value) {
            return value ? value.replace(/\b\w/g, function (character) {
                return character.toUpperCase();
            }) : "";
        }
    }
}
</script>

<style scoped="scoped" lang="scss">

@import "./resources/sass/hover_effects";

td {
    padding : 5px 10px;
}

button {
    &:focus {
        outline : none;
    }
}
</style>
