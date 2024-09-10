<!-- AdvancedSearch.vue -->
<template>
    <div
        class="advance-search-component fixed top-0 right-0 h-full w-full md:w-1/2 lg:w-1/3 bg-zinc-800 text-white z-50 overflow-y-auto transition-transform duration-300"
        :class="{ 'translate-x-0': showAdvanceSearch, 'translate-x-full': !showAdvanceSearch }">
        <div class="p-6">
            <div class="flex justify-between items-center mb-9">
                <h2 class="text-2xl font-bold">Advanced Search</h2>
                <button @click="$emit('close')" class="text-white hover:text-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Active Filters Component -->
            <ActiveFilters
                :filters="filters"
                @update:filter="updateFilter"
                @clear-all="clearAllFilters"
            />

            <!-- Gender -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Applicant Gender</h3>
                <div class="flex space-x-2">
                    <button @click="updateFilter('gender', 'Female')"
                            :class="{ 'bg-orange text-white': filters.gender === 'Female' }"
                            class="px-4 py-2 rounded w-6/12 bg-zinc-50 hover:bg-zinc-700 hover:text-white text-zinc-700 hover:bg-orange hover:text-white transition-colors duration-300">
                        Female
                    </button>
                    <button @click="updateFilter('gender', 'Male')"
                            :class="{ 'bg-orange text-white': filters.gender === 'Male' }"
                            class="px-4 py-2 rounded w-6/12 bg-zinc-50 hover:bg-zinc-700 hover:text-white text-zinc-700 hover:bg-orange hover:text-white transition-colors duration-300">
                        Male
                    </button>
                </div>
            </div>

            <!-- Location -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Base City</h3>
                <select v-model="filters.city"
                        class="w-full p-2 rounded text-zinc-700 border border-zinc-600 focus:border-orange focus:ring focus:ring-orange focus:ring-opacity-50">
                    <option value="" disabled selected>Choose a city...</option>
                    <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                </select>
                <select v-if="filters.city === 'Baghdad'" v-model="filters.zone"
                        class="w-full p-2 rounded bg-zinc-700 text-zinc-200 mt-2 border border-zinc-600 focus:border-orange focus:ring focus:ring-orange focus:ring-opacity-50">
                    <option value="" disabled selected>Choose a zone...</option>
                    <option>Karkh</option>
                    <option>Risafa</option>
                </select>
            </div>

            <!-- Age Range -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Applicant Age Range</h3>
                <RangeInput
                    label="Age Range"
                    :modelValue="filters.age || { min: 18, max: 50 }"
                    @update:modelValue="updateAgeFilter"
                />
            </div>


            <!-- Educational Degree -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Educational Degree</h3>
                <select v-model="filters.degree"
                        class="w-full p-2 rounded bg-zinc-50 text-zinc-700 border border-zinc-600 focus:border-orange focus:ring focus:ring-orange focus:ring-opacity-50">
                    <option value="" selected>Choose a degree...</option>
                    <option v-for="degree in degrees" :key="degree">{{ degree }}</option>
                </select>
            </div>

            <!-- Fresh Graduates -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Fresh Graduates</h3>
                <label class="flex items-center cursor-pointer">
                    <div class="relative mr-2">
                        <input type="checkbox" v-model="filters.freshGraduate" class="sr-only">
                        <div class="w-10 h-6 rounded-full shadow-inner transition-colors duration-300 ease-in-out"
                             :class="filters.freshGraduate ? 'bg-orange' : 'bg-zinc-50'"></div>
                        <div
                            class="absolute w-[17px] h-[17px] rounded-full shadow top-1 left-1 transition-transform duration-300 ease-in-out"
                            :class="[{'bg-zinc-50':filters.freshGraduate},{'bg-zinc-500': !filters.freshGraduate},{ 'translate-x-4': filters.freshGraduate }]"></div>
                    </div>
                    <span>Include Fresh Graduates</span>
                </label>
            </div>

            <!-- Work Availability -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Work Availability</h3>
                <div class="flex space-x-2">
                    <button @click="updateFilter('workAvailability', true)"
                            :class="{ 'bg-orange text-white': filters.workAvailability === true }"
                            class="px-4 py-2 rounded w-6/12 bg-zinc-50 hover:bg-zinc-700 hover:text-white text-zinc-700 hover:bg-orange hover:text-white transition-colors duration-300">
                        Available
                    </button>
                    <button @click="updateFilter('workAvailability', false)"
                            :class="{ 'bg-orange text-white': filters.workAvailability === false }"
                            class="px-4 py-2 rounded w-6/12 bg-zinc-50 hover:bg-zinc-700 hover:text-white text-zinc-700 hover:bg-orange hover:text-white transition-colors duration-300">
                        Not Available
                    </button>
                </div>
            </div>

            <!-- Work Experience -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Work Experience</h3>
                <RangeInput
                    label="Work Experience (years)"
                    :modelValue="filters.experience || { min: 2, max: 10 }"
                    @update:modelValue="updateExperienceFilter"
                />
            </div>

            <!-- Specialization -->
            <SpecializationSelectsAdvanceSearch
                v-model:mainSpecializations="filters.mainSpecializations"
                v-model:subSpecialities="filters.subSpecialities"
            />

            <!-- Apply Search Button -->
            <button @click="handleAdvancedSearch"
                    class="w-full bg-orange text-white py-2 rounded mt-6 hover:bg-orange-600 transition-colors duration-300">
                Apply Search
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { getSelectables } from "../../../js/utils/storeHelpers.js";
import RangeInput from "./RangeInput.vue";
import SpecializationSelectsAdvanceSearch from "./SpecializationSelectsAdvanceSearch.vue";
import ActiveFilters from "./ActiveFilters.vue";

const props = defineProps({
    showAdvanceSearch: Boolean,
});

const emit = defineEmits(['update:showAdvanceSearch', 'advancedSearch', 'close']);

const store = useStore();

const filters = ref({
    gender: '',
    city: '',
    zone: '',
    age: null,
    degree: '',
    freshGraduate: null,
    workAvailability: '',
    experience: null,
    mainSpecializations: [],
    subSpecialities: [],
});


const clearAllFilters = () => {
    filters.value = {
        gender: '',
        city: '',
        zone: '',
        age: null,
        degree: '',
        freshGraduate: null,
        workAvailability: '',
        experience: null,
        mainSpecializations: [],
        subSpecialities: [],
    };
    store.dispatch('resetFilters');
    store.dispatch('setSearchMode', false);
};

const activeFilters = computed(() => {
    return Object.entries(filters.value).reduce((acc, [key, value]) => {
        if (value !== null && value !== '' && !(Array.isArray(value) && value.length === 0)) {
            acc[key] = value;
        }
        return acc;
    }, {});
});

const cities = ref([]);
const degrees = ref([]);

const updateFilter = (key, value) => {
    filters.value[key] = value;
};

const updateAgeFilter = (value) => {
    filters.value.age = value;
};

const updateExperienceFilter = (value) => {
    filters.value.experience = value;
};

const handleAdvancedSearch = async () => {
    try {
        await store.dispatch('setFilters', activeFilters.value);
        await store.dispatch('getFilteredApplicants', { page: 1 });
        store.dispatch('setSearchMode', true);
        emit('advancedSearch', activeFilters.value);
        if (store.getters.error) {
            console.error(store.getters.error);
            // Handle error (e.g., show an error message to the user)
        }
    } catch (error) {
        console.error("Error in advanced search:", error);
        // Handle error (e.g., show an error message to the user)
    }
};

onMounted(async () => {
    try {
        cities.value = await getSelectables('cities');
        degrees.value = await getSelectables('degrees');
    } catch (error) {
        console.error('Error fetching selectables:', error);
        // Implement user-friendly error handling here
    }
});
</script>


<style scoped>
.advance-search-component {
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
}
</style>
