<template>
    <div>
        <HeroComponent></HeroComponent>
        <SearchComponent id="search-area"></SearchComponent>
        <SlidersComponent v-if="!searchMode"></SlidersComponent>
        <PreviewAllComponent v-else></PreviewAllComponent>
        <FooterComponent></FooterComponent>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch, nextTick } from "vue";
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import SearchComponent from "./mainViewComponents/SearchComponent.vue";
import SlidersComponent from "./mainViewComponents/SlidersComponent.vue";
import PreviewAllComponent from "./mainViewComponents/PreviewAllComponent.vue";
import FooterComponent from "./mainViewComponents/FooterComponent.vue";
import HeroComponent from "./mainViewComponents/HeroComponent.vue";

const router = useRouter();
const store = useStore();

const searchMode = computed(() => store.state.searchMode);

const lastState = ref({
    searchMode: false,
    filters: {},
});

watch(() => store.state.filters, (newFilters) => {
    const hasActiveFilters = Object.values(newFilters).some(value =>
        value !== null && value !== '' && value !== undefined &&
        !(Array.isArray(value) && value.length === 0)
    );
    store.commit('setSearchMode', hasActiveFilters);
}, { deep: true });

</script>
