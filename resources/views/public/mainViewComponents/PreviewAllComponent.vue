<template>
    <div class="w-full px-4 py-8 bg-white">
        <!-- Specializations Filter -->
        <div :class="isAdvanceSearchInUse ? 'sm:w-8/12 md:w-8/12 lg:w-8/12' : 'w-full'">
            <SpecializationsFilter/>

        </div>

        <!-- Content Header -->
        <div class="md:w-3/12 w-full mt-16">
            <h1 class="text-start py-1 my-2 px-3 font-bold tracking-wider text-2xl md:text-3xl">
                {{ getHeaderText }}
            </h1>
            <div class="w-10/12 mt-6">
                <div class="-mt-4 h-2.5 mb-2 w-full rounded-full shadow-custom-3d bg-orange"></div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading && !applicantsToShow.length" class="flex justify-center items-center min-h-[200px]">
            <Loader/>
        </div>

        <!-- Main Content -->
        <div v-else class="mt-16">
            <!-- Grid of Applicants -->
            <div v-if="accumulatedApplicants.length"
                 :class="isAdvanceSearchInUse ? 'px-4 sm:w-8/12 md:w-8/12 lg:w-8/12 grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3' : 'px-10 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 justify-items-center'"
                 class="grid gap-x-4 gap-y-14 justify-items-center">
                <template v-for="(applicant, index) in accumulatedApplicants" :key="applicant.id">
                    <ApplicantCard
                        :applicant="applicant"
                        :rounded="'rounded-lg'"
                        class="w-full"
                    />
                </template>
            </div>

            <!-- Message when no initial results -->
            <div v-else-if="!accumulatedApplicants.length"
                 :class="isAdvanceSearchInUse ? 'sm:w-8/12 md:w-8/12 lg:w-8/12' : 'w-full'"
                 class="flex justify-center items-center h-64">
                <p class="text-2xl font-semibold text-zinc-600">
                    {{ getNoResultsMessage }}
                </p>
            </div>

            <!-- End of Results Section -->
            <div v-if="accumulatedApplicants.length"
                 :class="isAdvanceSearchInUse ? 'sm:w-8/12 md:w-8/12 lg:w-8/12' : 'w-full'"
                 class="flex flex-col items-center mt-12">

                <!-- Loading State -->
                <div v-if="isLoadingMore" class="mb-4">
                    <Loader class="w-8 h-8"/>
                </div>

                <!-- No More Results Message -->
                <p v-else-if="!hasMorePages" class="text-lg text-zinc-600 mb-4">
                    That's all our available talents for now
                </p>

                <!-- Load More Button -->
                <button
                    v-if="isLoadMoreVisible"
                    @click="loadMore"
                    class="px-8 py-3 bg-orange text-white rounded-full font-semibold hover:bg-orange-600 transition-colors duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
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
    return !loading.value && // Not in initial loading state
        !isLoadingMore.value && // Not currently loading more
        accumulatedApplicants.value.length > 0 && // Has some data
        hasMorePages.value; // Has more pages to load
});

const fetchApplicants = async (page, isLoadMore = false) => {
    if (!isLoadMore) {
        loading.value = true;
        accumulatedData.value = []; // Reset accumulated data on new search/filter
    } else {
        isLoadingMore.value = true;
    }

    alertMessage.value = '';

    try {
        const action = isSearchMode.value ? 'searchApplicantsInfinite' : 'getFilteredApplicants';

        // Include active specialization in the request if it exists
        const requestParams = {
            page,
            perPage: 12,
        };

        // If we have an active specialization that isn't Latest, ensure it's included
        if (activeSpecialization.value && activeSpecialization.value !== 'Latest') {
            if (!store.state.filters.mainSpecializations?.includes(activeSpecialization.value)) {
                await store.dispatch('updateFilter', {
                    key: 'mainSpecializations',
                    value: [activeSpecialization.value]
                });
            }
        }

        await store.dispatch(action, requestParams);

        // Get the latest data directly from the store after the action
        const currentData = isSearchMode.value
            ? store.state.searchedApplicants.data
            : store.state.filteredApplicants.data;

        // Update accumulated data
        if (isLoadMore) {
            // Ensure we don't have duplicates and maintain order
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
    (newValue) => {
        // Reset and refetch when advanced search is toggled
        currentPage.value = 1;
        accumulatedData.value = [];
        fetchApplicants(1);
    }
);

// Watch for filter changes to reset pagination
watch([isSearchMode, activeSpecialization], () => {
    currentPage.value = 1;
    accumulatedData.value = []; // Reset accumulated data
    fetchApplicants(1);
});

// Watch for changes in filters
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

// Initial fetch
onMounted(async () => {
    await fetchApplicants(1);
});

// Expose for parent component
defineExpose({
    handleAdvancedSearch: () => {
        currentPage.value = 1;
        accumulatedData.value = []; // Reset accumulated data
        fetchApplicants(1);
    }
});
</script>
