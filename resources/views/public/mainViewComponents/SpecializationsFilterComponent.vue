<template>
    <div class="max-w-7xl mx-auto mb-12">
        <div class="flex flex-wrap justify-center gap-3 px-4">
            <!-- Latest Filter -->
            <button
                @click="handleSpecializationClick('Latest')"
                :class="[
                    'px-6 py-2 rounded-full text-sm font-semibold transition-all duration-300',
                    activeSpecialization === 'Latest'
                        ? 'bg-orange text-white shadow-lg transform scale-105'
                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                ]"
            >
                Latest
            </button>

            <!-- Other Specializations -->
            <button
                v-for="speci in specialities"
                :key="speci.title"
                @click="handleSpecializationClick(speci.title)"
                :class="[
                    'px-6 py-2 rounded-full text-sm font-semibold transition-all duration-300',
                    activeSpecialization === speci.title
                        ? 'bg-orange text-white shadow-lg transform scale-105'
                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                ]"
            >
                {{ speci.title }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { getSelectables } from '../../../js/utils/storeHelpers.js';

const store = useStore();
const specialities = ref([]);

// Use computed property to access the active specialization from store
const activeSpecialization = computed(() => store.state.activeSpecialization);

onMounted(async () => {
    try {
        // Set default specialization on mount if not set
        if (!activeSpecialization.value) {
            store.commit('setActiveSpecialization', 'Latest');
        }

        // Fetch specialities
        specialities.value = await getSelectables('specialities');
    } catch (error) {
        console.error('Error fetching specialities:', error);
        specialities.value = [];
    }
});

const handleSpecializationClick = async (specialization) => {
    store.commit('setActiveSpecialization', specialization);

    if (specialization === 'Latest') {
        // Clear specialization filters but keep other filters
        const currentFilters = { ...store.state.filters };
        currentFilters.mainSpecializations = [];
        await store.dispatch('setFilters', currentFilters);
    } else {
        // Update filters while preserving other active filters
        const currentFilters = { ...store.state.filters };
        currentFilters.mainSpecializations = [specialization];
        await store.dispatch('setFilters', currentFilters);
    }

    // Get filtered applicants
    await store.dispatch('getFilteredApplicants', {
        page: 1,
        sortBy: specialization === 'Latest' ? 'created_at' : undefined,
        sortOrder: specialization === 'Latest' ? 'desc' : undefined
    });
};
// Watch for changes in filters to handle reset to Latest
watch(
    () => store.state.filters.mainSpecializations,
    (newValue) => {
        if (!newValue || newValue.length === 0) {
            store.commit('setActiveSpecialization', 'Latest');
        }
    }
);
</script>

<style scoped>
.transform {
    transform: scale(1.05);
}

button {
    transition: all 0.3s ease;
}

button:hover {
    transform: translateY(-1px);
}
</style>
