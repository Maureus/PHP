<template>
    <div>
        <div>
            <table class="table-container">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Accessible to</th>
                    <th scope="col">Last update</th>
                    <th scope="col">Creator</th>
                    <th scope="col">Editor</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <StudyMatItem v-for="study_mat in study_mats" :key="study_mat.id" :study_mat="study_mat"/>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import StudyMatItem from "./StudyMatItem";

export default {
    name: "StudyMats",
    components: {StudyMatItem},
    data() {
        return {
            loading: true,
            study_mats: [],
            subject_id: this.$route.params.subject_id,
        }
    },
    async mounted() {
        console.log(this.subject_id);
        await axios.get("http://127.0.0.1:8000/api/subject/" + this.subject_id + "/study_mats")
            .then(resp => resp.data).then(value => {
                this.study_mats = value;
                this.loading = false;
            });
    },
    computed: {}
}
</script>

<style scoped="scoped" lang="scss">
$fontSize        : 18px;
$hoverColor      : #dde9f5;
$backgroundColor : white;
$margin          : 10px;

.table-container {
    text-align       : center;
    display          : table;
    background-color : $backgroundColor;
    color            : black;
    font-size        : $fontSize;
    border-radius    : 7px;
    overflow         : hidden;
    border-collapse  : collapse;
    //white-space      : nowrap;
    margin           : auto;
    //table-layout     : fixed;
    width            : 100%;

    tr {
        //margin      : $margin/2 0;
        line-height : 2.1875em;

        &:nth-child(odd) {
            background-color : $backgroundColor;
        }

        &:nth-child(even) {
            background-color : darken($color : $backgroundColor, $amount : 5%);
        }

        &:hover {
            background-color : darken($color : $hoverColor, $amount : 2%);
        }

        th {
            color            : white;
            background-color : darken($color : #187fe2, $amount : 3%);
            overflow-wrap    : break-word;
            max-width        : 250px;
            min-width        : 100px;
        }
    }
}

</style>
