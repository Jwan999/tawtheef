<template>
    <div>
        <HeroComponent></HeroComponent>
        <SearchComponent></SearchComponent>
        <SlidersComponent v-if="!searchMode"></SlidersComponent>
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
import {computed, onMounted, ref, watch} from "vue";
import store from "../../js/store/index.js";


const searchMode = computed({
    get() {
        return store.getters.searchMode;
    },
    set(value) {
        store.dispatch('setSearchMode', value);
    }
});

watch(() => store.state.filters, (newFilters) => {
    const hasActiveFilters = Object.values(newFilters).some(value =>
        value !== null && value !== '' && value !== undefined &&
        !(Array.isArray(value) && value.length === 0)
    );
    store.dispatch('setSearchMode', hasActiveFilters);
}, { deep: true });



</script>
