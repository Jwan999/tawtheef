<template>
    <div>
        <HeroComponent></HeroComponent>
        <SearchComponent></SearchComponent>
        <SlidersComponent v-if="searchMode"></SlidersComponent>
        <PreviewAllComponent v-else></PreviewAllComponent>
        <FooterComponent></FooterComponent>
    </div>
</template>

<script setup>
import SearchComponent from "./mainViewComponents/SearchComponent.vue";
import SlidersComponent from "./mainViewComponents/SlidersComponent.vue";
import PreviewAllComponent from "./mainViewComponents/PreviewAllComponent.vue";
import FooterComponent from "./mainViewComponents/FooterComponent.vue";
import HeroComponent from "./mainViewComponents/HeroComponent.vue";
import {computed, onMounted, ref} from "vue";
import store from "../../js/store/index.js";


const searchMode = computed({
    get() {
        return store.getters.searchMode;
    },
    set() {
        store.dispatch('setSearchMode', !searchMode)
    }
})




</script>
