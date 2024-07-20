<template>
    <div class="grid grid-cols-1">
        <h1 class="mt-10 mb-3 font-semibold text-4xl capitalize tracking-wider text-orange">
            Active Filters</h1>
        <div class="active-filters mt-6 flex flex-wrap gap-2">
            <div v-for="(value, key) in activeFilters" :key="key">
                <div v-if="shouldDisplayFilter(key, value)"
                     class="filter-capsule flex items-center bg-white justify-between border-[1px] border-orange rounded-tr-lg rounded-bl-lg py-2 px-3">
                    <span class="text-zinc-800 font-semibold">{{ formatFilterLabel(key, value) }}</span>
                    <button @click="removeFilter(key)" class="ml-2">
                        <svg class="fill-zinc-700 w-4 h-4 hover:fill-orange" viewBox="0 0 1024 1024"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();


const activeFilters = computed(() => {
    const filters = store.state.filters;
    return Object.fromEntries(
        Object.entries(filters).filter(([key, value]) =>
            value !== null && value !== undefined && value !== '' &&
            !(Array.isArray(value) && value.length === 0) &&
            !isDefaultValue(key, value)
        )
    );
});

const isDefaultValue = (key, value) => {
    if (key === 'age' && Array.isArray(value) && value[0] === 19 && value[1] === 26) {
        return true;
    }
    if (key === 'experience' && Array.isArray(value) && value[0] === 2 && value[1] === 6) {
        return true;
    }
    return false;
};

const shouldDisplayFilter = (key, value) => {
    return value !== null && value !== undefined && value !== '' &&
        !(Array.isArray(value) && value.length === 0) &&
        !isDefaultValue(key, value);
};

const formatFilterLabel = (key, value) => {
    if (Array.isArray(value)) {
        return `${value.join(' - ')}`;
    } else if (typeof value === 'boolean') {
        return key;
    } else {
        return value;
    }
};
const hasResults = computed(() => {
    return store.state.filteredApplicants.length > 0 || store.state.searchedApplicants.length > 0;
});
const removeFilter = (key) => {
    let resetValue;
    switch (key) {
        case 'experience':
            resetValue = [2, 6];
            break;
        case 'age':
            resetValue = [19, 26];
            break;
        case 'mainSpecializations':
        case 'subSpecialities':
            resetValue = [];
            break;
        default:
            resetValue = null;
    }
    store.commit('updateFilter', {key, value: resetValue});
    store.dispatch('fetchFilteredApplicants', store.state.filters);
};
</script>
