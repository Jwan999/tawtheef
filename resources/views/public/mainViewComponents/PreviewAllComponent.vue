<template>
    <div :class="isAdvanceSearchInUse ? 'w-8/12' : 'w-full'" class="px-6 py-12 bg-zinc-50">
        <!-- Specializations Filter -->
        <div>
            <SpecializationsFilter/>
        </div>

        <!-- Content Header -->
        <div class="w-full mt-16 mb-8">
            <h1 class="text-3xl md:text-4xl font-semibold text-zinc-900">
                {{ getHeaderText }}
            </h1>
            <div class="w-32 h-1.5 mt-4 bg-orange rounded-full"></div>
        </div>

        <!-- Loading State -->
        <div v-if="loading && !applicantsToShow.length" class="flex justify-center items-center min-h-[300px]">
            <Loader/>
        </div>

        <!-- Main Content -->
        <div v-else class="mt-8">
            <!-- Grid of Applicants -->
            <div v-if="accumulatedApplicants.length"
                 class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template v-for="applicant in accumulatedApplicants" :key="applicant.id">
                    <ApplicantCard
                        :applicant="applicant"
                        class="h-full"
                    />
                </template>
            </div>

            <!-- No Results Message -->
            <div v-else-if="!accumulatedApplicants.length"
                 class="flex justify-center items-center min-h-[200px] bg-white rounded-xl shadow-sm">
                <p class="text-xl text-zinc-600">
                    {{ getNoResultsMessage }}
                </p>
            </div>

            <!-- End of Results Section -->
            <div v-if="accumulatedApplicants.length"
                 class="flex flex-col items-center mt-12 space-y-4">
                <!-- Loading State -->
                <div v-if="isLoadingMore">
                    <Loader class="w-8 h-8"/>
                </div>

                <!-- No More Results Message -->
                <p v-else-if="!hasMorePages" class="text-zinc-600">
                    That's all our available talents for now
                </p>

                <!-- Load More Button -->
                <button
                    v-if="isLoadMoreVisible"
                    @click="loadMore"
                    class="px-8 py-3 bg-orange text-white rounded-full font-medium hover:bg-orange-600 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                    Load More
                </button>
            </div>
        </div>

        <Alert
            :message="alertMessage"
            :type="alertType"
            :duration="5000"
        />
    </div>
</template>

<script setup>
import {ref, computed, watch, onMounted} from 'vue';
import {useStore} from 'vuex';
import ApplicantCard from './ApplicantCard.vue';
import SpecializationsFilter from './SpecializationsFilterComponent.vue';
import Loader from '../../../js/components/LottieLoader.vue';
import Alert from '../../../js/components/AlertComponent.vue';

const store = useStore();
const currentPage = ref(1);
const loading = ref(false);
const alertMessage = ref('');
const alertType = ref('info');
const isLoadingMore = ref(false);
const accumulatedData = ref([]);

const isAdvanceSearchInUse = computed(() => store.state.advanceSearchInUse);
const isSearchMode = computed(() => store.state.searchMode);
const activeSpecialization = computed(() => store.state.activeSpecialization);

const getHeaderText = computed(() => {
    if (activeSpecialization.value === 'Latest') return 'Latest Candidates';
    if (activeSpecialization.value) return activeSpecialization.value;
    if (isSearchMode.value) return 'Search Results';
    return 'Latest Candidates';
});

const getNoResultsMessage = computed(() => {
    if (activeSpecialization.value && activeSpecialization.value !== 'Latest') {
        return `No results found for ${activeSpecialization.value}`;
    }
    if (isSearchMode.value) {
        return 'No search results available';
    }
    return 'No applicants available';
});

const applicantsToShow = computed(() => {
    if (isSearchMode.value) {
        return store.state.searchedApplicants.data || [];
    }
    return store.state.filteredApplicants.data || [];
});

const accumulatedApplicants = computed(() => {
    return accumulatedData.value || [];
});

const currentPageData = computed(() => {
    if (isSearchMode.value) {
        return store.state.searchedApplicants;
    }
    return store.state.filteredApplicants;
});

const hasMorePages = computed(() => {
    const currentPage = currentPageData.value.current_page;
    const lastPage = currentPageData.value.last_page;
    const hasData = accumulatedData.value.length > 0;

    return hasData && currentPage < lastPage;
});

const isLoadMoreVisible = computed(() => {
    return !loading.value &&
        !isLoadingMore.value &&
        accumulatedApplicants.value.length > 0 &&
        hasMorePages.value;
});

const fetchApplicants = async (page, isLoadMore = false) => {
    if (!isLoadMore) {
        loading.value = true;
        accumulatedData.value = [];
    } else {
        isLoadingMore.value = true;
    }

    alertMessage.value = '';

    try {
        const action = isSearchMode.value ? 'searchApplicantsInfinite' : 'getFilteredApplicants';

        const requestParams = {
            page,
            perPage: 12,
        };

        if (activeSpecialization.value && activeSpecialization.value !== 'Latest') {
            if (!store.state.filters.mainSpecializations?.includes(activeSpecialization.value)) {
                await store.dispatch('updateFilter', {
                    key: 'mainSpecializations',
                    value: [activeSpecialization.value]
                });
            }
        }

        await store.dispatch(action, requestParams);

        const currentData = isSearchMode.value
            ? store.state.searchedApplicants.data
            : store.state.filteredApplicants.data;

        if (isLoadMore) {
            const existingIds = new Set(accumulatedData.value.map(item => item.id));
            const uniqueNewData = currentData.filter(item => !existingIds.has(item.id));
            accumulatedData.value = [...accumulatedData.value, ...uniqueNewData];
        } else {
            accumulatedData.value = [...currentData];
        }
    } catch (error) {
        console.error('Error fetching applicants:', error);
        alertMessage.value = 'Failed to fetch applicants. Please try again.';
        alertType.value = 'error';
    } finally {
        loading.value = false;
        isLoadingMore.value = false;
    }
};

const loadMore = async () => {
    if (!hasMorePages.value || isLoadingMore.value || loading.value) {
        return;
    }

    try {
        isLoadingMore.value = true;
        currentPage.value++;
        await fetchApplicants(currentPage.value, true);
    } catch (error) {
        console.error('Error loading more applicants:', error);
        alertMessage.value = 'Failed to load more applicants. Please try again.';
        alertType.value = 'error';
    } finally {
        isLoadingMore.value = false;
    }
};

watch(
    () => store.state.advanceSearchInUse,
    () => {
        currentPage.value = 1;
        accumulatedData.value = [];
        fetchApplicants(1);
    }
);

watch([isSearchMode, activeSpecialization], () => {
    currentPage.value = 1;
    accumulatedData.value = [];
    fetchApplicants(1);
});

watch(
    () => store.state.filters,
    (newFilters, oldFilters) => {
        const hasSpecializationChanged =
            JSON.stringify(newFilters.mainSpecializations) !==
            JSON.stringify(oldFilters.mainSpecializations);

        if (store.state.advanceSearchInUse || hasSpecializationChanged) {
            currentPage.value = 1;
            accumulatedData.value = [];
            fetchApplicants(1);
        }
    },
    {deep: true}
);

onMounted(async () => {
    await fetchApplicants(1);
});

defineExpose({
    handleAdvancedSearch: () => {
        currentPage.value = 1;
        accumulatedData.value = [];
        fetchApplicants(1);
    }
});
</script>
