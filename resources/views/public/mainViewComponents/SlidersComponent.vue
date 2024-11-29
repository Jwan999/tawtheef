<template>
    <div class="w-full px-4 py-8 bg-white">
        <!-- Specializations Filter Section -->
        <div class="max-w-7xl mx-auto mb-12">
            <div class="flex flex-wrap justify-center gap-3 px-4">
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

        <!-- Mixed View (Default State) -->
        <div v-if="!activeSpecialization" class="mt-14 md:mt-16 mb-16">
            <div class="w-full md:w-3/12 mb-10">
                <div class="mt-3 md:mt-6">
                    <h1 class="text-start py-1 my-2 px-3 font-bold tracking-wider text-2xl md:text-3xl">
                        Latest Applicants
                    </h1>
                    <div class="mt-6">
                        <div class="-mt-4 h-2 mb-2 w-full rounded-full shadow-custom-3d bg-orange"></div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading && !displayedApplicants.length" class="flex justify-center items-center min-h-[200px]">
                <Loader />
            </div>

            <!-- Grid layout for mixed view -->
            <div v-else class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <ApplicantCard
                    v-for="applicant in displayedApplicants"
                    :key="applicant.id"
                    :applicant="applicant"
                    :rounded="'rounded-lg'"
                    class="w-full"
                />
            </div>

            <!-- Load More Button -->
            <div v-if="hasMoreApplicants" class="flex justify-center mt-12">
                <button
                    @click="loadMore"
                    :disabled="loading"
                    class="px-8 py-3 bg-orange text-white rounded-full font-semibold hover:bg-orange-600 transition-colors duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="loading">Loading...</span>
                    <span v-else>Load More</span>
                </button>
            </div>
        </div>

        <!-- Filtered/Search View -->
        <PreviewAllComponent v-else />

        <!-- Intersection Observer Target -->
        <div ref="intersectionTarget" class="h-10 mt-8" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useStore } from 'vuex';
import { getSelectables } from '../../../js/utils/storeHelpers.js';
import ApplicantCard from './ApplicantCard.vue';
import PreviewAllComponent from './PreviewAllComponent.vue';
import Loader from '../../../js/components/LottieLoader.vue';
import axios from 'axios';

const ITEMS_PER_LOAD = 8; // 2 rows × 4 cards
const store = useStore();
const loading = ref(false);
const applicants = ref([]);
const specialities = ref([]);
const displayedCount = ref(ITEMS_PER_LOAD);
const intersectionTarget = ref(null);
const error = ref(null);

const activeSpecialization = computed({
    get: () => store.state.activeSpecialization,
    set: (value) => store.commit('setActiveSpecialization', value)
});

const getApplicants = async () => {
    try {
        loading.value = true;
        error.value = null;

        const response = await axios.get('/api/applicants', {
            params: {
                page: 1,
                per_page: displayedCount.value
            }
        });

        // Handle different response structures
        const applicantsData = response.data.data || response.data;

        if (!Array.isArray(applicantsData)) {
            throw new Error('Invalid response format');
        }

        applicants.value = applicantsData.map(applicant => ({
            ...applicant,
            topTools: (applicant.tools || [])
                .filter(tool => tool.rating === 5)
                .slice(0, 2)
        }));
    } catch (error) {
        console.error('Error fetching applicants:', error);
        error.value = 'Failed to load applicants. Please try again.';
    } finally {
        loading.value = false;
    }
};

const handleSpecializationClick = (specialization) => {
    if (activeSpecialization.value === specialization) {
        activeSpecialization.value = null;
        // Reset search if active
        if (store.state.searchMode) {
            store.dispatch('setSearchMode', false);
        }
    } else {
        activeSpecialization.value = specialization;
        // Update filters and trigger search if search is active
        if (store.state.searchMode) {
            store.dispatch('searchApplicantsInfinite', { page: 1 });
        }
    }
};

const displayedApplicants = computed(() => {
    return applicants.value.slice(0, displayedCount.value);
});

const hasMoreApplicants = computed(() => {
    return displayedCount.value < applicants.value.length;
});

const loadMore = async () => {
    if (loading.value) return;

    displayedCount.value += ITEMS_PER_LOAD;
    await getApplicants();
};

// Intersection Observer setup
let observer;
onMounted(async () => {
    await getApplicants();

    try {
        specialities.value = await getSelectables('specialities');
    } catch (error) {
        console.error('Error fetching specialities:', error);
    }

    observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting && hasMoreApplicants.value && !loading.value) {
                loadMore();
            }
        },
        { threshold: 0.5 }
    );

    if (intersectionTarget.value) {
        observer.observe(intersectionTarget.value);
    }
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
});
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

.grid > * {
    transition: all 0.3s ease;
}
</style>
