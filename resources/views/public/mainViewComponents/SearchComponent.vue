<template>
    <div class="flex justify-start -mt-32">
        <div :class="showAdvanceSearch ? 'pt-14 pb-10 md:pt-28 md:pb-10' : 'py-14 md:py-28'"
             class="w-full md:w-10/12 mr-6 md:mr-0 md:px-0 px-4 bg-zinc-800 text-white rounded-r-[4rem] md:rounded-r-full">
            <div class="flex justify-center items-center w-full">
                <div class="w-full md:w-10/12">
                    <div class="w-full md:w-8/12 flex flex-wrap">
                        <h1 class="text-3xl md:text-5xl font-bold">Discover Exceptional Talent or Inspiring Resumes <span
                            class="text-orange">Right Here</span></h1>
                    </div>
                    <div class="w-full md:w-7/12 flex items-center space-x-4 mt-10 md:mt-14">
                        <div class="relative group w-full">
                            <div class="search-container">
                                <div class="search-description text-base md:text-xl mb-3 text-zinc-100">
                                    Search for applicants using <span class="text-orange tracking-wider font-semibold">keywords</span>.
                                    You can search through:
                                    <ul class="mt-1 italic text-zinc-200 pl-4 md:pl-8 text-sm md:text-lg mt-2">
                                        <li>Skills or tools (e.g., "Python", "Photoshop")</li>
                                        <li>Job titles or roles (e.g., "Frontend Developer", "UX Designer")</li>
                                        <li>Specific responsibilities (e.g., "project management", "client relations")
                                        </li>
                                        <li>Specializations or areas of expertise</li>
                                    </ul>
                                </div>
                                <div class="flex justify-start space-x-3 items-center mt-6">
                                    <div class="relative group w-full">
                                        <input
                                            v-model="searchTerm"
                                            @keyup.enter="handleSearch"
                                            placeholder="Enter keywords to find the perfect candidate..."
                                            class="w-full text-dark text-md md:text-base focus:border-orange py-3 pl-4 pr-16 focus:ring-0 bg-white rounded-full border-[1px] border-zinc-200 group-hover:border-orange focus:outline-none focus:border-orange transition-colors duration-300"
                                            type="text">
                                        <button
                                            @click="handleSearch"
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-orange text-white rounded-full hover:bg-orange-600 transition-colors duration-300">
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
                                    <div>
                                        <button @click="showAdvanceSearch = !showAdvanceSearch" class="mt-1">
                                            <svg
                                                class="bg-white w-[40px] h-[40px] hover:fill-orange border-0 border-zinc-200 border-[1px] border-transparent hover:border-orange rounded-full px-2 py-1 fill-zinc-600 md:hidden"
                                                id="fi_3024539" viewBox="0 0 189.524 189.524"
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
                                            <span class="hidden md:inline bg-white text-zinc-600 hover:text-orange hover:border-orange border border-zinc-200 rounded-full px-4 py-2 font-semibold transition-colors duration-300 whitespace-nowrap">Advance Search</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1 v-if="showAdvanceSearch"
                        class="mt-10 font-semibold text-4xl capitalize tracking-wider text-white">Advance search</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-16">
        <transition name="slide-down" appear>
            <div v-if="showAdvanceSearch" id="advanceSearch" class="relative w-9/12 md:w-7/12">
                <div class="advance-search flex justify-center bg-zinc-800 rounded-br-[4rem] md:rounded-br-[7rem] pb-10 border-[2px] md:border-[4px] md:border-t-0 border-t-0 border-orange rounded-rt-[5rem] z-40">
                    <div class="grid grid-cols-1 gap-12 w-10/12">
                        <!--gender-->
                        <div class="group cursor-pointer">
                            <h1 class="text-xl text-white group-  mb-2 tracking-wider font-bold capitalize">
                                Gender</h1>
                            <span @click="filters.gender = 'Female'"
                                  :class="filters.gender == 'Female' ?'bg-orange text-white':'bg-zinc-200 text-zinc-600'"
                                  class="font-semibold hover:bg-dark hover:text-white text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                         Female
                    </span>
                            <span @click="filters.gender = 'Male'"
                                  :class="filters.gender == 'Male' ?'bg-orange text-white':'bg-zinc-200 text-zinc-600'"
                                  class="font-semibold hover:bg-dark hover:text-white text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                      Male
                    </span>
                        </div>
                        <!--location-->
                        <div class="group cursor-pointer">
                            <h1 class="text-xl text-white group-  mb-2 tracking-wider font-bold capitalize">
                                Location</h1>
                            <div class="flex space">
                                <select v-model="filters.city"
                                        :class="filters.city == 'Baghdad' ?'rounded-l-md' :'rounded-md'"
                                        class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-l-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                    <option value="" disabled selected>Choose a city...</option>
                                    <option v-for="city in cities">{{ city }}</option>
                                </select>
                                <select v-if="filters.city == 'Baghdad'" v-model="filters.zone"
                                        class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                    <option value="" disabled selected>Choose a zone...</option>
                                    <option>Karkh</option>
                                    <option>Risafa</option>
                                </select>
                            </div>
                        </div>
                        <!--age-->
                        <div class="group cursor-pointer">
                            <h1 class="text-xl text-white group-  mb-2 tracking-wider font-bold capitalize">
                                Based on age</h1>
                            <div class="mt-12">
                                <VueSimpleRangeSlider
                                    active-bar-color="#f97316"
                                    class="w-full text-orange font-bold"
                                    v-model="filters.age"
                                    :min="18"
                                    :max="40"
                                    exponential>
                                    <template #suffix="{value}"> Y</template>
                                </VueSimpleRangeSlider>
                            </div>
                        </div>
                        <!--degree-->
                        <div class="group cursor-pointer">
                            <h1 class="text-xl text-white group-  mb-2 tracking-wider font-bold">
                                Educational degree</h1>
                            <select v-model="filters.degree"
                                    class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                <option value="" disabled selected>Choose a degree...</option>
                                <template v-for="option in degrees">
                                    <option>{{ option }}</option>
                                </template>
                            </select>
                        </div>
                        <!--fresh graduates-->
                        <div class="group cursor-pointer flex items-center space-x-2">
                            <input
                                id="fresh-graduate-checkbox"
                                type="checkbox"
                                v-model="filters.freshGraduate"
                                class="text-sm w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600"
                            />
                            <label for="fresh-graduate-checkbox"
                                   class="text-xl text-white group-  mb-2 tracking-wider font-bold capitalize">
                                Fresh Graduates
                            </label>
                        </div>
                        <!--work availability -->
                        <div class="group cursor-pointer">
                            <h1 class="text-xl text-white  mb-2 tracking-wider font-bold capitalize">
                                Work availability</h1>
                            <span @click="filters.workAvailability = true"
                                  :class="filters.workAvailability ? 'bg-orange text-white' : 'bg-zinc-200 text-zinc-600'"
                                  class="font-semibold hover:bg-dark hover:text-white text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                          Available for Work
                    </span>
                            <span @click="filters.workAvailability = false" :class="!filters.workAvailability ? 'bg-orange text-white' : 'bg-zinc-200 text-zinc-600'"
                                  class="font-semibold hover:bg-dark hover:text-white  text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                     Not Available for work
                    </span>
                        </div>
                        <!--work experience-->
                        <div class="group cursor-pointer">
                            <h1 class="text-xl text-white group-  mb-2 tracking-wider font-bold capitalize">Work
                                experience</h1>
                            <div class="mt-12">
                                <VueSimpleRangeSlider
                                    active-bar-color="#f97316"
                                    class="w-full text-orange font-bold"
                                    v-model="filters.experience"
                                    :min="1"
                                    :max="15"
                                    exponential>
                                    <template #suffix="{value}"> Y</template>
                                </VueSimpleRangeSlider>
                            </div>
                        </div>
                        <SpecializationSelectsAdvanceSearch />
                        <div class="flex justify-center w-full">
                            <div class="md:w-full w-11/12">
                                <button @click="handleAdvancedSearch"
                                        class="hover:text-orange capitalize mt-3 w-full appearance-none px-8 py-1.5 rounded-bl-lg font-bold text-white bg-orange hover:bg-zinc-100">
                                    APPLY SEARCH
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="flex justify-center" v-if="searchApplied">
            <ActiveFilters class="md:w-8/12 w-11/12" />
        </div>
    </div>
</template>


<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { getSelectables } from "../../../js/utils/storeHelpers.js";
import store from "../../../js/store/index.js";
import VueSimpleRangeSlider from "vue-simple-range-slider";
import "vue-simple-range-slider/css";
import ActiveFilters from "./ActiveFilters.vue";
import SpecializationSelectsAdvanceSearch from "./SpecializationSelectsAdvanceSearch.vue";

const showAdvanceSearch = ref(false);
const searchApplied = ref(false);
const specializations = ref([]);
const cities = ref([]);
const degrees = ref([]);
const searchTerm = ref('');
const filters = computed(() => store.state.filters);

const selectedMainSpecializations = computed(() => store.state.filters.mainSpecializations);
const selectedSubSpecialities = computed(() => store.state.filters.subSpecialities);

const updateFilters = (newFilters) => {
    store.commit('updateFilters', newFilters);
};

const handleAdvancedSearch = async () => {
    const advancedFilters = {
        ...filters.value,
        searchTerm: searchTerm.value
    };

    try {
        await store.dispatch('fetchFilteredApplicants', advancedFilters);
        searchApplied.value = true;
        searchMode.value = true;
    } catch (error) {
        console.error("Error in advanced search:", error);
        // Handle error (e.g., show an error message to the user)
    }
};

const availableSubSpecialties = computed(() => {
    return selectedMainSpecializations.value.flatMap(mainSpec =>
        specializations.value.find(s => s.title === mainSpec)?.children || []
    );
});

watch(() => filters.value.mainSpecializations, (newValue) => {
    updateFilter('subSpecialities', []);
});

const resetFilters = () => {
    store.dispatch('resetFilters');
    searchApplied.value = false;
    searchMode.value = false;
};

const updateFilter = (key, value) => {
    if (key === 'mainSpecializations' && !Array.isArray(value)) {
        value = Array.isArray(value) ? value : [value].filter(Boolean);
    }
    store.commit('updateFilter', {key, value});
};

const handleSearch = async () => {
    await store.dispatch('searchApplicants', searchTerm.value);
    searchMode.value = true;
};

const searchMode = computed({
    get() {
        return store.getters.searchMode;
    },
    set(value) {
        store.dispatch('setSearchMode', value);
    }
});

onMounted(async () => {
    degrees.value = await getSelectables('degrees');
    cities.value = await getSelectables('cities');
    specializations.value = await getSelectables('specialities');
});

</script>


<style>
.simple-range-slider .simple-range-slider-bg-bar[data-v-f6e84873] {
    margin: 0;
}

input[type=range]::-moz-range-thumb {
    background: #f97316;
}

input[type="range"]::-moz-range-progress {
    background-color: #f97316;
}

.slide-down-enter-active, .slide-down-leave-active {
    transition: all 200ms cubic-bezier(0.17, 0.04, 0.03, 0.94);
}

.slide-down-enter-from, .slide-down-leave-to {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
}

.slide-down-enter-to, .slide-down-leave-from {
    max-height: 1000px; /* Adjust this value as needed */
    opacity: 1;
    overflow: hidden;
}
</style>
