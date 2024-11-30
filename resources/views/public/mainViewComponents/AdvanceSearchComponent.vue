<template>
    <div
        class="advance-search-component fixed top-0 right-0 h-full w-full sm:w-3/12 md:w-4/12 lg:w-4/12 bg-zinc-800 text-white z-[100] overflow-y-auto transition-transform duration-300"
        :class="{ 'translate-x-0': showAdvanceSearch, 'translate-x-full': !showAdvanceSearch }">
        <div class="p-2 px-8">
            <!-- Sticky Header -->
            <div class="sticky z-10 w-full top-0 bg-zinc-800 py-2">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        <h2 class="text-xl text-zinc-100 font-semibold">Advance Search</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="handleReset" class="text-sm text-zinc-400 hover:text-white transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12a9 9 0 0 0-9-9 9 9 0 0 0-9 9 9 9 0 0 0 9 9 9 9 0 0 0 9-9"/>
                                <path d="M15.5 16.5 12 13l3.5-3.5"/>
                                <path d="M8.5 7.5 12 11l-3.5 3.5"/>
                            </svg>
                            Reset
                        </button>
                        <button @click="handleClose" class="text-white hover:text-orange">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <ActiveFilters
                    :filters="filters"
                    @update:filter="updateFilter"
                    @clear-all="handleReset"
                />
            </div>

            <!-- Filter Sections -->
            <div class="space-y-6 mt-6">
                <!-- Personal Details Section -->
                <div class="mb-6 border border-zinc-700 rounded-lg overflow-hidden">
                    <button
                        @click="toggleSection('personal')"
                        class="w-full px-4 py-3 bg-zinc-700 text-white flex justify-between items-center hover:bg-zinc-600 transition-colors"
                    >
                        <span class="text-lg font-medium">Personal Details</span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 transition-transform"
                             :class="{ 'rotate-180': expandedSections.personal }"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div class="p-4 bg-zinc-800 transition-all" :class="{ 'hidden': !expandedSections.personal }">
                        <!-- Gender -->
                        <div class="mb-6">
                            <h3 class="text-sm text-zinc-400 mb-2">Gender</h3>
                            <div class="flex space-x-2">
                                <button
                                    v-for="gender in ['Female', 'Male']"
                                    :key="gender"
                                    @click="updateFilter('gender', filters.gender === gender ? null : gender)"
                                    :class="{ 'bg-orange text-white': filters.gender === gender }"
                                    class="px-4 py-2 rounded w-6/12 bg-zinc-700 hover:bg-zinc-600 text-white transition-colors duration-300"
                                >
                                    {{ gender }}
                                </button>
                            </div>
                        </div>

                        <!-- Age Range -->
                        <div class="mb-6">
                            <h3 class="text-sm text-zinc-400 mb-2">Age Range</h3>
                            <RangeInput
                                label="Age Range"
                                :modelValue="filters.age || { min: 18, max: 50 }"
                                @update:modelValue="(value) => updateFilter('age', value)"
                            />
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="mb-6 border border-zinc-700 rounded-lg overflow-hidden">
                    <button
                        @click="toggleSection('location')"
                        class="w-full px-4 py-3 bg-zinc-700 text-white flex justify-between items-center hover:bg-zinc-600 transition-colors"
                    >
                        <span class="text-lg font-medium">Location</span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 transition-transform"
                             :class="{ 'rotate-180': expandedSections.location }"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div class="p-4 bg-zinc-800 transition-all" :class="{ 'hidden': !expandedSections.location }">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-zinc-400 mb-2 block">City</label>
                                <select
                                    v-model="filters.city"
                                    @change="updateFilter('city', $event.target.value)"
                                    class="w-full p-2 rounded bg-zinc-700 text-white border border-zinc-600 focus:border-orange focus:ring-1 focus:ring-orange"
                                >
                                    <option value="" disabled selected>Choose a city...</option>
                                    <option class="notranslate" v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                                </select>
                            </div>
                            <div v-if="filters.city === 'Baghdad'">
                                <label class="text-sm text-zinc-400 mb-2 block">Zone</label>
                                <select
                                    v-model="filters.zone"
                                    @change="updateFilter('zone', $event.target.value)"
                                    class="w-full p-2 rounded bg-zinc-700 text-white border border-zinc-600 focus:border-orange focus:ring-1 focus:ring-orange"
                                >
                                    <option value="" disabled selected>Choose a zone...</option>
                                    <option class="notranslate">Karkh</option>
                                    <option class="notranslate">Risafa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education Section -->
                <div class="mb-6 border border-zinc-700 rounded-lg overflow-hidden">
                    <button
                        @click="toggleSection('education')"
                        class="w-full px-4 py-3 bg-zinc-700 text-white flex justify-between items-center hover:bg-zinc-600 transition-colors"
                    >
                        <span class="text-lg font-medium">Education</span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 transition-transform"
                             :class="{ 'rotate-180': expandedSections.education }"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div class="p-4 bg-zinc-800 transition-all" :class="{ 'hidden': !expandedSections.education }">
                        <!-- Degree -->
                        <div class="mb-6">
                            <label class="text-sm text-zinc-400 mb-2 block">Qualification Level</label>
                            <select
                                v-model="filters.degree"
                                @change="updateFilter('degree', $event.target.value)"
                                class="w-full p-2 rounded bg-zinc-700 text-white border border-zinc-600 focus:border-orange focus:ring-1 focus:ring-orange"
                            >
                                <option value="" selected>Choose a degree...</option>
                                <option class="notranslate" v-for="degree in degrees" :key="degree">{{ degree }}</option>
                            </select>
                        </div>

                        <!-- Fresh Graduates Toggle -->
                        <div class="flex items-center justify-between p-3 bg-zinc-700 rounded">
                            <span class="text-sm">Include Fresh Graduates</span>
                            <label class="relative inline-block w-10 h-6">
                                <input
                                    type="checkbox"
                                    class="sr-only"
                                    v-model="filters.freshGraduate"
                                    @change="updateFilter('freshGraduate', $event.target.checked ? $event.target.checked : null)"
                                >
                                <div
                                    class="w-full h-full rounded-full shadow-inner transition-colors duration-300 ease-in-out"
                                    :class="filters.freshGraduate ? 'bg-orange' : 'bg-zinc-600'"
                                >
                                    <div
                                        class="absolute w-4 h-4 bg-white rounded-full shadow top-1 left-1 transition-transform duration-300"
                                        :class="{ 'translate-x-4': filters.freshGraduate }"
                                    ></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Experience Section -->
                <div class="mb-6 border border-zinc-700 rounded-lg overflow-hidden">
                    <button
                        @click="toggleSection('experience')"
                        class="w-full px-4 py-3 bg-zinc-700 text-white flex justify-between items-center hover:bg-zinc-600 transition-colors"
                    >
                        <span class="text-lg font-medium">Experience & Availability</span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 transition-transform"
                             :class="{ 'rotate-180': expandedSections.experience }"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div class="p-4 bg-zinc-800 transition-all" :class="{ 'hidden': !expandedSections.experience }">
                        <!-- Work Experience -->
                        <div class="mb-6">
                            <h3 class="text-sm text-zinc-400 mb-2">Years of Experience</h3>
                            <RangeInput
                                label="Work Experience (years)"
                                :modelValue="filters.experience || { min: 2, max: 10 }"
                                @update:modelValue="(value) => updateFilter('experience', value)"
                            />
                        </div>

                        <!-- Work Availability -->
                        <div>
                            <h3 class="text-sm text-zinc-400 mb-2">Availability Status</h3>
                            <div class="flex space-x-2">
                                <button
                                    v-for="option in [{ label: 'Available', value: true }, { label: 'Not Available', value: false }]"
                                    :key="option.label"
                                    @click="updateFilter('workAvailability', filters.workAvailability === option.value ? null : option.value)"
                                    :class="{ 'bg-orange text-white': filters.workAvailability === option.value }"
                                    class="px-4 py-2 rounded w-6/12 bg-zinc-700 hover:bg-zinc-600 text-white transition-colors duration-300"
                                >
                                    {{ option.label }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subspecialities Section -->
                <div v-if="activeSpecialization !== 'Latest'" class="mb-6 border border-zinc-700 rounded-lg overflow-hidden">
                    <button
                        @click="toggleSection('specialization')"
                        class="w-full px-4 py-3 bg-zinc-700 text-white flex justify-between items-center hover:bg-zinc-600 transition-colors"
                    >
                        <span class="text-lg font-medium">{{ activeSpecialization }} Subspecialities</span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 transition-transform"
                             :class="{ 'rotate-180': expandedSections.specialization }"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div class="p-4 bg-zinc-800 transition-all" :class="{ 'hidden': !expandedSections.specialization }">
                        <SpecializationSelectsAdvanceSearch
                            :subSpecialities="filters.subSpecialities"
                            @update:subSpecialities="(value) => updateFilter('subSpecialities', value)"
                        />
                    </div>
                </div>
            </div>

            <!-- Sticky Footer -->
            <div class="sticky z-40 w-full bottom-0 bg-zinc-800 py-2">
<!--                <button-->
<!--                    @click="handleAdvancedSearch"-->
<!--                    class="w-full bg-orange text-white py-3 rounded-lg font-medium hover:bg-orange-600 transition-colors duration-300">-->
<!--                    Apply Filters-->
<!--                </button>-->
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useStore } from 'vuex';
import { getSelectables } from "../../../js/utils/storeHelpers.js";
import RangeInput from "./RangeInput.vue";
import SpecializationSelectsAdvanceSearch from "./SpecializationSelectsAdvanceSearch.vue";
import ActiveFilters from "./ActiveFilters.vue";

const store = useStore();

const props = defineProps({
    showAdvanceSearch: Boolean,
});

const emit = defineEmits(['update:showAdvanceSearch', 'close']);

const filters = computed(() => store.state.filters);
const activeSpecialization = computed(() => store.state.activeSpecialization);

const cities = ref([]);
const degrees = ref([]);

// Track expanded sections
const expandedSections = ref({
    personal: true,
    location: true,
    education: true,
    experience: true,
    specialization: true
});

const toggleSection = (section) => {
    expandedSections.value[section] = !expandedSections.value[section];
};

const updateFilter = async (key, value) => {
    await store.dispatch('updateFilter', { key, value });
};

const handleAdvancedSearch = async () => {
    try {
        const filtersToApply = { ...filters.value };

        if (store.state.activeSpecialization !== 'Latest') {
            filtersToApply.mainSpecializations = [store.state.activeSpecialization];
        }

        await store.dispatch('setFilters', filtersToApply);
        await store.dispatch('getFilteredApplicants', { page: 1 });
    } catch (error) {
        console.error("Error in advanced search:", error);
    }
};

const handleReset = async () => {
    await store.dispatch('resetFilters');
    await store.dispatch('getFilteredApplicants', { page: 1 });
};

const handleClose = () => {
    store.dispatch('setAdvanceSearchInUse', false);
    emit('close');
};

// Handle clicks outside
const handleClickOutside = (event) => {
    const component = document.querySelector('.advance-search-component');
    const advanceSearchButton = document.querySelector('.advanced-search-button');

    if (props.showAdvanceSearch &&
        component &&
        !component.contains(event.target) &&
        advanceSearchButton &&
        !advanceSearchButton.contains(event.target)) {
        handleClose();
    }
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
        document.addEventListener('mousedown', handleClickOutside);
    } catch (error) {
        console.error('Error fetching selectables:', error);
    }
});

onUnmounted(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});

</script>

<style scoped>
.advance-search-component {
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
}

.translate-x-0 {
    transform: translateX(0);
}

.translate-x-full {
    transform: translateX(100%);
}

input:focus, select:focus {
    outline: none;
    border-color: #f97316;
    box-shadow: 0 0 0 1px #f97316;
}

.transition-all {
    transition-property: all;
    transition-duration: 300ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
