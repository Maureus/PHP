<template>
    <tr>
        <td>
            <div class="hover-shadow-effect" style="padding: 1rem">
                <a :href="'http://127.0.0.1:8000/api/file/' + study_mat.file_name" target="_blank" title="Click to open"
                   class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">{{ study_mat.name }}
                </a>
            </div>
        </td>
        <td>{{ study_mat.date_from }}</td>
        <td>{{ study_mat.date_till }}</td>
        <td>{{ study_mat.updated_at == null ? "No updates" : study_mat.updated_at }}</td>
        <td>{{ study_mat.created_by }}</td>
        <td>{{ study_mat.edited_by == null ? "Nobody changed it" : study_mat.edited_by }}</td>
        <td v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)">
            <div class="hover-shadow-effect">
                <button @click="$emit('edit-study-mat', study_mat.id)" data-toggle="modal"
                        data-target="#editStudyMaterial"
                        class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">Edit
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "StudyMatItem",
    props: {
        study_mat: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole"])
    }
}
</script>

<style scoped="scoped" lang="scss">
$padding : 10px;

@import "./resources/sass/hover_effects";

td {
    padding       : $padding;
    overflow-wrap : break-word;
}

button {
    &:focus {
        outline : none;
    }
}
</style>
