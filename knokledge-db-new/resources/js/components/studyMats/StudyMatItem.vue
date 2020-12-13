<template>
    <tr>
        <td>{{ study_mat.name }}</td>
        <td>{{ study_mat.created_at }}</td>
        <td>{{ study_mat.date_till }}</td>
        <td>{{ study_mat.updated_at == null ? "No updates" : study_mat.updated_at }}</td>
        <td>{{ study_mat.created_by }}</td>
        <td>{{ study_mat.edited_by == null ? "Nobody changed it" : study_mat.edited_by }}</td>
        <td>
            <div class="hover-shadow-effect">
                <a :href="'http://127.0.0.1:8000/api/file/' + this.study_mat.file_name"
                   title="Click here to open" target="_blank">Open in new tab</a>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    name: "StudyMatItem",
    data() {
        return {
            linkForDownload: ""
        }
    },
    props: {
        study_mat: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$fontSize   : 18px;
$hoverColor : #dde9f5;
$padding    : 5px;

* {
    font-size : $fontSize;
}

.hover-shadow-effect {
    &:hover {
        font-weight                : bold;
        background-color           : darken($color : $hoverColor, $amount : 10%);
        box-shadow                 : darken($color: $hoverColor, $amount: 5%) -1px 1px,
        darken($color: $hoverColor, $amount: 5%) -2px 2px,
        darken($color: $hoverColor, $amount: 5%) -3px 3px,
        darken($color: $hoverColor, $amount: 5%) -4px 4px,
        darken($color: $hoverColor, $amount: 5%) -5px 5px;
        transform                  : translate3d(5px, -5px, 0);

        transition-delay           : 0s;
        transition-duration        : 0.5s;
        transition-property        : all;
        transition-timing-function : linear;
    }
}

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
