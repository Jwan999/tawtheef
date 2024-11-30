<template>
    <div class="relative">
        <div class="w-3/12 md:w-5/12 md:px-20 px-6 mb-24">
            <div class="w-full mt-16 mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-6xl text-zinc-900">
                    Talent Pool
                </h1>
                <div class="w-44 h-1.5 mt-4 bg-orange-500 rounded-full"></div>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="md:w-10/12 w-full">
                <div
                    class="w-full bg-zinc-50 border-none md:border-2 md:rounded-full border-orange md:px-24 md:pt-20 md:pb-16 px-10 py-10">
                    <div class="flex justify-center items-center w-full">
                        <div class="w-full">
                            <div class="relative w-full flex justify-between items-center space-x-4">
                                <div class="relative group lg:w-9/12 md:w-7/12 sm:w-6/12 w-10/12 flex items-center">
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
                                <div :class="isButtonFixed ? 'bottom-10 right-4': 'right-0 top-1'"
                                     class="absolute right-0 top-2 md:top-1" ref="buttonContainer">
                                    <button @click="toggleAdvanceSearch"
                                            :class="{'bg-orange text-white': showAdvanceSearch, 'fixed-position shadow-lg': isButtonFixed, 'hover:shadow-none':!isButtonFixed}"
                                            class="advanced-search-button md:mt-0 -mt-1 z-40 transition-all duration-300 flex items-center bg-orange hover:bg-dark text-white rounded-3xl
                                         md:py-2 py-2 px-2 md:px-3">
                                        <svg id="icons" viewBox="0 0 64 64" class="w-6 h-6 md:mr-2 fill-white md:hidden block"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m53.39 8h-42.78a5.61 5.61 0 0 0 -4.15 9.38l18.54 20.39v19.23a2 2 0 0 0 1.13 1.8 1.94 1.94 0 0 0 .87.2 2 2 0 0 0 1.25-.44l3.75-3 6.25-5a2 2 0 0 0 .75-1.56v-11.23l18.54-20.39a5.61 5.61 0 0 0 -4.15-9.38z"/>
                                        </svg>
                                        <span
                                            class="whitespace-nowrap md:block hidden px-6 font-semibold tracking-wide">{{
                                                showAdvanceSearch ? 'Close' : 'Advanced Search'
                                            }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 mt-4">

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
const isButtonFixed = ref(false);
const buttonContainer = ref(null);
const keywords = ref(null)

const keywordSelected = (term) => {
    if (term) searchTerm.value = term
    handleSearch()
}
onMounted(async () => {
    try {
        keywords.value = await getSelectables('keywords');
    } catch (error) {
        console.error('Failed to fetch select options:', error);
    }
})

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
        if (store.getters.error) {
            console.error(store.getters.error);
            // Handle error (e.g., show an error message to the user)
        }
    } catch (error) {
        console.error("Error in advanced search:", error);
        // Handle error (e.g., show an error message to the user)
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
    if (buttonContainer.value) {
        const rect = buttonContainer.value.getBoundingClientRect();
        if (rect.top <= 0 && !isButtonFixed.value) {
            isButtonFixed.value = true;
        } else if (rect.top > 0 && isButtonFixed.value) {
            isButtonFixed.value = false;
        }
    }
};


onMounted(() => {

    window.addEventListener('scroll', handleScroll);
    window.addEventListener('click', (event) => {
        if (showAdvanceSearch.value && !event.target.closest('.advance-search-component') && !event.target.closest('.advanced-search-button')) {
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
    position: absolute;
    right: 0.5rem;
}

.fixed-position {
    position: fixed;
    bottom: 2.5rem;
    right: 2rem;
//box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.fixed-button {
    position: fixed;
    z-index: 50;
    transition: all 0.3s ease;
}

 .fixed-position {
     position: fixed;
     bottom: 2.5rem;
     right: 2rem;
     z-index: 90;  /* High enough to be above cards but below the advance search panel */
 }

</style>
