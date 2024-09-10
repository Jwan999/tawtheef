<template>
    <div class="md:w-3/12 w-full mt-16">
        <div class="w-full">
            <div class="mb-10 w-full">
                <div class="mt-3 md:mt-6 w-full">
                    <h1 class="w-full text-start py-1 my-2 px-3 font-bold tracking-wider text-2xl md:text-3xl uppercase">
                        Search Results
                    </h1>
                    <div class="w-10/12 mt-6">
                        <div class="-mt-4 h-2.5 mb-2 w-11/12 rounded-br-2xl bg-orange"></div>
                        <div class="-mt-3 ml-8 h-2.5 w-full rounded-br-2xl bg-dark"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-10 mt-16">
        <div v-if="combinedApplicants.data && combinedApplicants.data.length > 0" class="grid grid-cols-1 md:grid-cols-4 justify-items-center gap-x-4 gap-y-14">
            <ApplicantCard
                v-for="applicant in combinedApplicants.data"
                :key="applicant.id"
                :applicant="applicant"
                :rounded="applicant === 1 ? 'rounded-r-lg' : 'rounded-lg'"
            />
        </div>
        <div v-else class="flex justify-center items-center h-64">
            <p class="text-2xl font-semibold text-zinc-600">No search results available with your search query.</p>
        </div>
    </div>
    <Pagination class="mt-16"
                v-if="combinedApplicants.data && combinedApplicants.data.length > 0"
                :current-page="currentPage"
                :last-page="combinedApplicants.last_page"
                :per-page="combinedApplicants.per_page"
                :total-items="combinedApplicants.total"
                @changePage="changePage"
    />
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useStore } from 'vuex';
import ApplicantCard from './ApplicantCard.vue';
import Pagination from './Pagination.vue';

const store = useStore();
const currentPage = ref(1);

const filteredApplicants = computed(() => store.state.filteredApplicants);
const searchedApplicants = computed(() => store.state.searchedApplicants);
const isSearchMode = computed(() => store.state.searchMode);

const combinedApplicants = computed(() => {
    if (isSearchMode.value && searchedApplicants.value.data && searchedApplicants.value.data.length > 0) {
        return searchedApplicants.value;
    } else {
        return filteredApplicants.value;
    }
});

const changePage = (page) => {
    currentPage.value = page;
    if (isSearchMode.value) {
        store.dispatch('searchApplicants', { page: page });
    } else {
        store.dispatch('getFilteredApplicants', { page: page });
    }
};

watch(() => store.state.searchQuery, (newQuery, oldQuery) => {
    if (newQuery !== oldQuery) {
        currentPage.value = 1;
        store.commit('clearSearchedApplicants');
        if (newQuery) {
            store.dispatch('setSearchMode', true);
            store.dispatch('searchApplicants', { page: 1 });
        } else {
            store.dispatch('setSearchMode', false);
            store.dispatch('getFilteredApplicants', { page: 1 });
        }
    }
});

const hasActiveFilters = computed(() => {
    return Object.values(store.state.filters).some(value =>
        value !== null && value !== '' && value !== undefined &&
        !(Array.isArray(value) && value.length === 0)
    );
});

onMounted(() => {
    if (!hasActiveFilters.value) {
        store.dispatch('getFilteredApplicants', { page: 1 });
    }
});

watch(() => store.state.filters, () => {
    currentPage.value = 1;
    store.commit('clearSearchedApplicants');
    store.dispatch('getFilteredApplicants', { page: 1 });
    store.dispatch('setSearchMode', false);
}, { deep: true });

onMounted(() => {
    store.dispatch('getFilteredApplicants', { page: 1 });
});
</script>
