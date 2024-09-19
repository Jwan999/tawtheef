<template>
    <div class="relative">
        <div class="flex justify-start -mt-16 md:-mt-32">
            <div
                class="w-full md:w-10/12 mr-6 md:mr-0 md:px-0 px-6 bg-zinc-800 text-white rounded-r-[4rem] md:rounded-r-full py-14 md:py-28">
                <div class="flex justify-center items-center w-full">
                    <div class="w-full md:w-10/12">
                        <div class="w-full md:w-8/12 flex flex-wrap">
                            <h1 class="text-3xl md:text-5xl font-bold">Discover Exceptional Talent and Inspiring Resumes
                                <span class="text-orange">Right Here</span>
                            </h1>
                        </div>
                        <div class="mt-14">
                            <div class="search-description text-base md:text-xl mb-3 text-zinc-100">
                                Search for applicants using <span class="text-orange tracking-wider font-semibold">keywords</span>.
                                Example keywords:
                                <ul class="mt-1 italic text-zinc-200 pl-4 md:pl-8 text-sm md:text-lg mt-2">
                                    <li>Skills or tools (e.g., "Python", "Photoshop")</li>
                                    <li>Job titles or roles (e.g., "Frontend Developer", "UX Designer")</li>
                                    <li>Specific responsibilities (e.g., "project management", "client relations")</li>
                                    <li>Specializations or areas of expertise</li>
                                </ul>
                            </div>
                        </div>
                        <div class="relative w-full md:w-10/12 flex justify-between items-center space-x-4 mt-10">
                            <div class="relative group lg:w-9/12 md:w-7/12 sm:w-6/12 w-8/12 flex items-center">
                                <input
                                    v-model="searchTerm"
                                    @keyup.enter="handleSearch"
                                    placeholder="Enter keywords to find the perfect candidate..."
                                    class="w-full text-dark text-md md:text-base focus:border-orange py-3 pl-4 pr-32 focus:ring-0 bg-white rounded-full border-[1px] border-zinc-200 group-hover:border-orange focus:outline-none focus:border-orange transition-colors duration-300"
                                    type="text">
                                <div class="absolute right-2 flex space-x-2">
                                    <button
                                        @click="handleSearch"
                                        class="w-10 h-10 flex items-center justify-center bg-orange text-white rounded-full hover:bg-orange-600 transition-colors duration-300">
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
                                 class="absolute right-0 top-1" ref="buttonContainer">
                                <button @click="toggleAdvanceSearch"
                                        :class="{'bg-orange text-white': showAdvanceSearch, 'fixed-position': isButtonFixed}"
                                        class="advanced-search-button z-40 transition-all duration-300 flex items-center bg-orange hover:bg-dark text-white rounded-bl-xl rounded-tr-xl
                                         py-2 px-2">
                                    <svg
                                        class="w-6 h-6 md:mr-2 fill-white md:hidden block"
                                        viewBox="0 0 189.524 189.524"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <g>
                                                <path clip-rule="evenodd"
                                                      d="m56.26 52.119c-2.104 8.174-9.524 14.214-18.355 14.214-10.467 0-18.952-8.485-18.952-18.952s8.485-18.952 18.952-18.952c8.831 0 16.251 6.04 18.355 14.214h128.526c2.616 0 4.738 2.121 4.738 4.738s-2.122 4.738-4.738 4.738zm-8.879-4.738c0 5.234-4.243 9.476-9.476 9.476s-9.476-4.243-9.476-9.476 4.243-9.476 9.476-9.476 9.476 4.242 9.476 9.476z"
                                                      fill-rule="evenodd"></path>
                                            </g>
                                            <g>
                                                <path
                                                    d="m4.738 52.119h14.811c-.39-1.514-.597-3.102-.597-4.738s.207-3.224.597-4.738h-14.811c-2.617 0-4.738 2.121-4.738 4.738s2.121 4.738 4.738 4.738z"></path>
                                            </g>
                                            <g>
                                                <path clip-rule="evenodd"
                                                      d="m113.117 137.405c-2.104-8.174-9.525-14.214-18.355-14.214s-16.252 6.04-18.355 14.214h-71.669c-2.617 0-4.738 2.122-4.738 4.738s2.121 4.738 4.738 4.738h71.668c2.104 8.174 9.525 14.214 18.355 14.214s16.252-6.04 18.355-14.214h71.668c2.616 0 4.738-2.122 4.738-4.738s-2.122-4.738-4.738-4.738zm-18.355 14.214c5.234 0 9.476-4.242 9.476-9.476s-4.242-9.476-9.476-9.476-9.476 4.242-9.476 9.476 4.242 9.476 9.476 9.476z"
                                                      fill-rule="evenodd"></path>
                                            </g>
                                            <g>
                                                <path clip-rule="evenodd"
                                                      d="m169.974 90.024c-2.104-8.174-9.525-14.214-18.355-14.214s-16.252 6.04-18.355 14.214h-128.526c-2.617 0-4.738 2.122-4.738 4.738s2.121 4.738 4.738 4.738h128.526c2.104 8.174 9.525 14.214 18.355 14.214s16.252-6.04 18.355-14.214h14.811c2.616 0 4.738-2.122 4.738-4.738s-2.122-4.738-4.738-4.738zm-18.355 14.214c5.234 0 9.476-4.242 9.476-9.476s-4.243-9.476-9.476-9.476c-5.234 0-9.476 4.242-9.476 9.476s4.242 9.476 9.476 9.476z"
                                                      fill-rule="evenodd"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="whitespace-nowrap md:block hidden px-6 font-semibold tracking-wide">{{
                                            showAdvanceSearch ? 'Close' : 'Advanced Search'
                                        }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-16">
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

const store = useStore();
const showAdvanceSearch = ref(false);
const searchApplied = ref(false);
const searchTerm = ref('');
const isButtonFixed = ref(false);
const buttonContainer = ref(null);

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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.fixed-button {
    position: fixed;
    z-index: 50;
    transition: all 0.3s ease;
}
</style>
