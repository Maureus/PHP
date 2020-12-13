<template>
    <div>
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>

    </div>
</template>

<script>
import {mapGetters} from "vuex";
import Preloader from "../Preloader";


export default {
    name: "StudyMats",
    components: {
        Preloader
    },
    data() {
        return {
            loading: true,
            study_mats: []
        }
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/study_mats").then(resp => resp.data).then(value => {
            this.study_mats = value;
            this.loading = false;
        });
    }
}
</script>

<style scoped>

</style>
