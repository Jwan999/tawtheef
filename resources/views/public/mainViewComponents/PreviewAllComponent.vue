<template>
    <div>
        <div class="md:w-3/12 w-full mt-16">
            <h1 class="text-start py-1 my-2 px-3 font-bold tracking-wider text-2xl md:text-3xl uppercase">
                Search Results
            </h1>
            <div class="w-10/12 mt-6">
                <div class="-mt-4 h-2.5 mb-2 w-11/12 rounded-br-2xl bg-orange"></div>
                <div class="-mt-3 ml-8 h-2.5 w-full rounded-br-2xl bg-dark"></div>
            </div>
        </div>

        <Loader v-if="loading" />

        <div v-else class="px-10 mt-16">
            <div v-if="applicantsToShow.length" class="grid grid-cols-1 md:grid-cols-4 justify-items-center gap-x-4 gap-y-14">
                <ApplicantCard
                    v-for="applicant in applicantsToShow"
                    :key="applicant.id"
                    :applicant="applicant"
                    :rounded="applicant === applicantsToShow[0] ? 'rounded-r-lg' : 'rounded-lg'"
                />
            </div>
            <div v-else class="flex justify-center items-center h-64">
                <p class="text-2xl font-semibold text-zinc-600">No search results available with your search query.</p>
            </div>
        </div>

        <Pagination
            v-if="applicantsToShow.length"
            class="mt-16"
            :current-page="currentPage"
            :last-page="combinedApplicants.last_page"
            :per-page="Number(combinedApplicants.per_page)"
            :total-items="combinedApplicants.total"
            @changePage="changePage"
        />

        <Alert
            :message="alertMessage"
            :type="alertType"
            :duration="5000"
        />
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useStore } from 'vuex';
import ApplicantCard from './ApplicantCard.vue';
import Pagination from './Pagination.vue';
import Loader from '../../../js/components/LottieLoader.vue';
import Alert from '../../../js/components/AlertComponent.vue';

const store = useStore();
const currentPage = ref(1);
const loading = ref(false);
const alertMessage = ref('');
const alertType = ref('info');

const isSearchMode = computed(() => store.state.searchMode);
// const combinedApplicants = computed(() =>
//     isSearchMode.value && store.state.searchedApplicants.data.length > 0
//         ? store.state.searchedApplicants
//         : store.state.filteredApplicants
// );
const combinedApplicants = ref(store.state.filteredApplicants);

const applicantsToShow = computed(() => combinedApplicants.value.data || []);

const fetchApplicants = async (page) => {
    loading.value = true;
    alertMessage.value = '';
    try {
        const action = isSearchMode.value ? 'searchApplicants' : 'getFilteredApplicants';
        await store.dispatch(action, {
            page,
            perPage: combinedApplicants.value.per_page,
            ...(isSearchMode.value ? { search: store.state.searchQuery } : {})
        });
    } catch (error) {
        console.error('Error fetching applicants:', error);
        alertMessage.value = 'Failed to fetch applicants. Please try again.';
        alertType.value = 'error';
    } finally {
        loading.value = false;
    }
};

const changePage = (page) => {
    currentPage.value = page;
    fetchApplicants(page);
};

const refreshApplicants = () => {
    currentPage.value = 1;
    store.commit('clearSearchedApplicants');
    fetchApplicants(1);
};

watch(() => store.state.searchQuery, refreshApplicants);

watch(() => store.state.filters, () => {
    store.dispatch('setSearchMode', false);
    refreshApplicants();
}, { deep: true });

onMounted(() => {
    fetchApplicants(1);
});
</script>
