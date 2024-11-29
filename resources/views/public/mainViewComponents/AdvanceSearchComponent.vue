<template>
    <div
        class="advance-search-component fixed top-0 right-0 h-full w-full sm:w-3/12 md:w-4/12 lg:w-4/12 bg-zinc-800 text-white z-50 overflow-y-auto transition-transform duration-300"
        :class="{ 'translate-x-0': showAdvanceSearch, 'translate-x-full': !showAdvanceSearch }">
        <div class="p-2 px-8">
            <div class="sticky z-40 w-full top-0 bg-zinc-800 py-2">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-xl text-zinc-100 font-semibold">Advanced Search</h2>
                    <button @click="handleClose" class="text-white hover:text-orange">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <ActiveFilters
                    :filters="filters"
                    @update:filter="updateFilter"
                    @clear-all="clearAllFilters"
                />
            </div>

            <!-- Gender -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Applicant Gender</h3>
                <div class="flex space-x-2">
                    <button
                        v-for="gender in ['Female', 'Male']"
                        :key="gender"
                        @click="updateFilter('gender', filters.gender === gender ? null : gender)"
                        :class="{ 'bg-orange text-white': filters.gender === gender }"
                        class="px-4 py-2 rounded w-6/12 bg-zinc-50 hover:bg-zinc-700 hover:text-white text-zinc-700 hover:bg-orange hover:text-white transition-colors duration-300"
                    >
                        {{ gender }}
                    </button>
                </div>
            </div>

            <!-- Location -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Base City</h3>
                <select
                    v-model="filters.city"
                    @change="updateFilter('city', $event.target.value)"
                    class="w-full p-2 rounded text-zinc-700 border border-zinc-600 focus:border-orange focus:ring focus:ring-orange focus:ring-opacity-50"
                >
                    <option value="" disabled selected>Choose a city...</option>
                    <option class="notranslate" v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                </select>
                <select
                    v-if="filters.city === 'Baghdad'"
                    v-model="filters.zone"
                    @change="updateFilter('zone', $event.target.value)"
                    class="w-full p-2 mt-2 rounded text-zinc-700 border border-zinc-600 focus:border-orange focus:ring focus:ring-orange focus:ring-opacity-50"
                >
                    <option value="" disabled selected>Choose a zone...</option>
                    <option class="notranslate">Karkh</option>
                    <option class="notranslate">Risafa</option>
                </select>
            </div>

            <!-- Age Range -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Applicant Age Range</h3>
                <RangeInput
                    label="Age Range"
                    :modelValue="filters.age || { min: 18, max: 50 }"
                    @update:modelValue="(value) => updateFilter('age', value)"
                />
            </div>

            <!-- Educational Degree -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Educational Degree</h3>
                <select
                    v-model="filters.degree"
                    @change="updateFilter('degree', $event.target.value)"
                    class="w-full p-2 rounded bg-zinc-50 text-zinc-700 border border-zinc-600 focus:border-orange focus:ring focus:ring-orange focus:ring-opacity-50"
                >
                    <option value="" selected>Choose a degree...</option>
                    <option class="notranslate" v-for="degree in degrees" :key="degree">{{ degree }}</option>
                </select>
            </div>

            <!-- Fresh Graduates -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Fresh Graduates</h3>
                <label class="flex items-center cursor-pointer">
                    <div class="relative mr-2">
                        <input type="checkbox" v-model="filters.freshGraduate"
                               @change="updateFilter('freshGraduate', $event.target.checked ? $event.target.checked : null)"
                               class="sr-only">
                        <div
                            class="w-10 h-6 rounded-full shadow-inner transition-colors duration-300 ease-in-out"
                            :class="filters.freshGraduate ? 'bg-orange' : 'bg-zinc-50'"
                        ></div>
                        <div
                            class="absolute w-[17px] h-[17px] rounded-full shadow top-1 left-1 transition-transform duration-300 ease-in-out"
                            :class="[
                                {'bg-zinc-50': filters.freshGraduate},
                                {'bg-zinc-500': !filters.freshGraduate},
                                { 'translate-x-4': filters.freshGraduate }
                            ]"
                        ></div>
                    </div>
                    <span>Include Fresh Graduates</span>
                </label>
            </div>

            <!-- Work Availability -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Work Availability</h3>
                <div class="flex space-x-2">
                    <button
                        v-for="option in [{ label: 'Available', value: true }, { label: 'Not Available', value: false }]"
                        :key="option.label"
                        @click="updateFilter('workAvailability', filters.workAvailability === option.value ? null : option.value)"
                        :class="{ 'bg-orange text-white': filters.workAvailability === option.value }"
                        class="px-4 py-2 rounded w-6/12 bg-zinc-50 hover:bg-zinc-700 hover:text-white text-zinc-700 hover:bg-orange hover:text-white transition-colors duration-300"
                    >
                        {{ option.label }}
                    </button>
                </div>
            </div>

            <!-- Work Experience -->
            <div class="mb-9">
                <h3 class="text-lg mb-3">Work Experience</h3>
                <RangeInput
                    label="Work Experience (years)"
                    :modelValue="filters.experience || { min: 2, max: 10 }"
                    @update:modelValue="(value) => updateFilter('experience', value)"
                />
            </div>

            <!-- Specialization -->
            <SpecializationSelectsAdvanceSearch
                :mainSpecializations="filters.mainSpecializations"
                :subSpecialities="filters.subSpecialities"
                @update:mainSpecializations="(value) => updateFilter('mainSpecializations', value)"
                @update:subSpecialities="(value) => updateFilter('subSpecialities', value)"
            />

            <div class="sticky z-40 w-full bottom-0 bg-zinc-800 py-2">
                <!-- Apply Search Button -->
                <button
                    @click="handleAdvancedSearch"
                    class="w-full bg-orange text-white py-2 rounded mt-6 hover:bg-orange-600 transition-colors duration-300">
                    Apply Search
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, computed, onMounted, watch} from 'vue';
import {useStore} from 'vuex';
import {getSelectables} from "../../../js/utils/storeHelpers.js";
import RangeInput from "./RangeInput.vue";
import SpecializationSelectsAdvanceSearch from "./SpecializationSelectsAdvanceSearch.vue";
import ActiveFilters from "./ActiveFilters.vue";

const store = useStore();

const props = defineProps({
    showAdvanceSearch: Boolean,
});

const emit = defineEmits(['update:showAdvanceSearch', 'advancedSearch', 'close']);

const filters = computed(() => store.state.filters);
const activeSpecialization = computed(() => store.state.activeSpecialization);

const cities = ref([]);
const degrees = ref([]);

const updateFilter = async (key, value) => {
    await store.dispatch('updateFilter', {key, value});
};

const clearAllFilters = async () => {
    await store.dispatch('resetFilters');
};

const handleAdvancedSearch = async () => {
    try {
        // Ensure we're sending specializations correctly
        const filtersToApply = { ...filters.value };

        // If we have an active specialization, make sure it's included
        if (store.state.activeSpecialization !== 'Latest') {
            filtersToApply.mainSpecializations = filtersToApply.mainSpecializations || [];
            if (!filtersToApply.mainSpecializations.includes(store.state.activeSpecialization)) {
                filtersToApply.mainSpecializations = [
                    ...filtersToApply.mainSpecializations,
                    store.state.activeSpecialization
                ];
            }
        }

        await store.dispatch('setFilters', filtersToApply);
        await store.dispatch('getFilteredApplicants', { page: 1 });
        emit('advancedSearch');
    } catch (error) {
        console.error("Error in advanced search:", error);
    }
};
const handleClose = () => {
    store.dispatch('setAdvanceSearchInUse', false);
    emit('close');
};

watch(() => props.showAdvanceSearch, (newValue) => {
    if (!newValue) {
        store.dispatch('setAdvanceSearchInUse', false);
    }
});

onMounted(async () => {
    try {
        [cities.value, degrees.value] = await Promise.all([
            getSelectables('cities'),
            getSelectables('degrees')
        ]);
    } catch (error) {
        console.error('Error fetching selectables:', error);
    }
});

watch(
    () => store.state.filters.mainSpecializations,
    (newValue) => {
        if (!newValue || newValue.length === 0) {
            // Only reset to Latest if it's not already Latest
            if (store.state.activeSpecialization !== 'Latest') {
                store.commit('setActiveSpecialization', 'Latest');
                store.dispatch('getFilteredApplicants', {
                    page: 1,
                    sortBy: 'created_at',
                    sortOrder: 'desc'
                });
            }
        }
    }
);

</script>

<style scoped>
.advance-search-component {
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
}
</style>
