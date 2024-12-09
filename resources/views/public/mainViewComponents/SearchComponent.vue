<template>
    <div id="search-area" class="relative">
        <div class="w-full text-center px-6 mt-24">
            <div class="mx-auto max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-semibold text-zinc-900">
                    Talent Pool
                </h1>
                <div class="w-24 h-1 mt-4 bg-orange-500 rounded-full mx-auto"></div>
            </div>
        </div>
        <div class="mt-10 text-center">
            <h1 class="text-2xl px-6 max-w-3xl mx-auto">Connect with top talent and find your perfect candidate. Browse through professionally crafted resumes from skilled individuals across all industries.</h1>
        </div>
        <div class="flex justify-center py-20" ref="searchContainer">
            <div class="md:w-10/12 w-full max-w-4xl mx-auto">
                <div class="w-full border-none md:border-2 md:rounded-full border-orange">
                    <div class="flex justify-center items-center w-full">
                        <div class="w-full px-4 md:px-0">
                            <div class="relative w-full flex justify-center items-center">
                                <div class="relative group w-full max-w-2xl flex items-center">
                                    <input
                                        v-model="searchTerm"
                                        @keyup.enter="handleSearch"
                                        placeholder="Enter keywords to find the perfect candidate..."
                                        class="w-full text-dark text-md md:text-base group py-3 pl-4 pr-32 focus:ring-0 bg-white rounded-full border-[1px] border-zinc-200 hover:border-dark focus:outline-none transition-colors duration-300"
                                        type="text">
                                    <div class="absolute right-2 flex space-x-2">
                                        <button
                                            @click="handleSearch"
                                            class="w-10 h-10 flex items-center justify-center bg-orange text-white rounded-full group-hover:bg-orange-600 transition-colors duration-300">
                                            <svg
                                                class="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                ></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 mt-4 text-center">
                        <h1>Example keywords:</h1>
                        <div class="space-x-2 mt-2">
                            <span v-for="(keyword,index) in keywords" :key="index" @click="keywordSelected(keyword)"
                                  class="text-zinc-500 underline underline-offset-2 font-semibold tracking-wide cursor-pointer hover:text-orange text-sm">
                                {{ keyword }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Advanced Search Button with animation -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="transform translate-y-10 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-10 opacity-0"
        >
            <button v-show="showFixedButton"
                    @click="toggleAdvanceSearch"
                    ref="advanceSearchButton"
                    :class="{'bg-orange text-white': showAdvanceSearch}"
                    class="fixed bottom-10 right-6 z-50 flex items-center bg-orange hover:bg-dark text-white rounded-3xl py-2 px-3 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <svg id="icons" viewBox="0 0 64 64" class="w-6 h-6 md:mr-2 fill-white md:hidden block"
                     xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m53.39 8h-42.78a5.61 5.61 0 0 0 -4.15 9.38l18.54 20.39v19.23a2 2 0 0 0 1.13 1.8 1.94 1.94 0 0 0 .87.2 2 2 0 0 0 1.25-.44l3.75-3 6.25-5a2 2 0 0 0 .75-1.56v-11.23l18.54-20.39a5.61 5.61 0 0 0 -4.15-9.38z"/>
                </svg>
                <span class="whitespace-nowrap md:block hidden px-6 font-semibold tracking-wide">
                    {{ showAdvanceSearch ? 'Close' : 'Advanced Search' }}
                </span>
            </button>
        </Transition>

        <div class="space-y-16 z-40">
            <AdvanceSearchComponent
                v-model:showAdvanceSearch="showAdvanceSearch"
                @advancedSearch="handleAdvancedSearch"
                @close="closeAdvanceSearch"
            />
        </div>
    </div>
</template>

<script setup>
import {ref, watch, onMounted, onUnmounted} from "vue";
import {useStore} from 'vuex';
import ActiveFilters from "./ActiveFilters.vue";
import AdvanceSearchComponent from "./AdvanceSearchComponent.vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";

const store = useStore();
const showAdvanceSearch = ref(false);
const searchApplied = ref(false);
const searchTerm = ref('');
const showFixedButton = ref(false);
const keywords = ref(null);
const searchContainer = ref(null);
const advanceSearchButton = ref(null);

const keywordSelected = (term) => {
    if (term) searchTerm.value = term;
    handleSearch();
};

onMounted(async () => {
    try {
        keywords.value = await getSelectables('keywords');
    } catch (error) {
        console.error('Failed to fetch select options:', error);
    }
});

const handleSearch = async () => {
    if (searchTerm.value.trim() !== '') {
        await store.dispatch('setSearchMode', true);
        await store.dispatch('setSearchQuery', searchTerm.value);
        await store.dispatch('searchApplicants', {page: 1});
    } else {
        await store.dispatch('setSearchMode', false);
        await store.dispatch('resetFilters');
        await store.dispatch('getFilteredApplicants', {page: 1});
    }
};

const clearSearch = () => {
    searchTerm.value = '';
    store.dispatch('setSearchMode', false);
    store.dispatch('resetFilters');
    searchApplied.value = false;
};

const handleAdvancedSearch = async (advancedFilters) => {
    try {
        await store.dispatch('setFilters', advancedFilters);
        await store.dispatch('getFilteredApplicants', {page: 1});
        searchApplied.value = true;
        await store.dispatch('setSearchMode', true);
    } catch (error) {
        console.error("Error in advanced search:", error);
    }
};

const toggleAdvanceSearch = () => {
    showAdvanceSearch.value = !showAdvanceSearch.value;
    store.dispatch('setAdvanceSearchInUse', showAdvanceSearch.value);
};

const closeAdvanceSearch = () => {
    showAdvanceSearch.value = false;
    store.dispatch('setAdvanceSearchInUse', false);
};

const handleScroll = () => {
    if (searchContainer.value) {
        const rect = searchContainer.value.getBoundingClientRect();
        const triggerPoint = window.innerHeight / 2;
        showFixedButton.value = rect.top <= triggerPoint;
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('click', (event) => {
        // Check if click is outside both the advance search component and the toggle button
        const isClickOutsideAdvanceSearch = !event.target.closest('.advance-search-component');
        const isClickOnToggleButton = advanceSearchButton.value && advanceSearchButton.value.contains(event.target);

        if (showAdvanceSearch.value && isClickOutsideAdvanceSearch && !isClickOnToggleButton) {
            closeAdvanceSearch();
        }
    });
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

watch(showAdvanceSearch, (newValue) => {
    store.dispatch('setAdvanceSearchInUse', newValue);
});
</script>

<style scoped>
.advanced-search-button {
    transition: all 0.3s ease;
}
</style>
