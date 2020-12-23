<template>
    <tr>
        <td>
            <div v-if="getUser" class="hover-shadow-effect" style="padding: 0.75rem">
                <router-link class="whitespace-no-wrap text-right text-base leading-5 font-medium"
                             title="Click to open subject's detail"
                             :to="{name: 'SubjectContent', params: {subject_id: subject.id} }">{{ subject.name }}
                </router-link>
            </div>
            <div v-else>{{ subject.name }}</div>
        </td>
        <td>{{ subject.semester }}</td>
        <td>{{ subject.year }}</td>
        <td>{{ subject.short_name }}</td>
        <td v-if="getUser && option !== ''">
            <div v-if="option == getWriteOperation" class="hover-shadow-effect">
                <button @click="buttonUtility($event.target.value)" :value="option"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ option | capitalizer }}
                </button>
            </div>
            <div v-else-if="option == getEditOperation" class="hover-shadow-effect">
                <button @click="buttonUtility($event.target.value)" :value="option" data-toggle="modal"
                        data-target="#editSubjectModal"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
                    {{ option | capitalizer }}
                </button>
            </div>
            <div v-else-if="option == getDeleteOperation" class="hover-shadow-effect">
                <button @click="buttonUtility($event.target.value)" :value="option"
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
        buttonUtility(value) {
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
        }
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

</style>
