<template>
    <div class="search-container">
        <div class="search-label">
            <label for="searchArea" @click="isShownSearchArea = !isShownSearchArea"
                   :title="isShownSearchArea ? 'click to hide search field' : 'click to start search'">
                <i class="fas fa-search"></i> Search
            </label>
        </div>
        <transition name="fade">
            <div class="search-field" v-if="isShownSearchArea">
                <textarea id="searchArea" v-model="searchAreaText" @keyup="$emit('search-area-text', searchAreaText)" placeholder="Start typing..."></textarea>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    name: "SearchField",
    data() {
        return {
            searchAreaText: "",
            isShownSearchArea: false
        }
    }
}
</script>

<style scoped>

</style>
