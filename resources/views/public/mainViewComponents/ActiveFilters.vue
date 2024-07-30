<template>
    <div class="bg-zinc-50 rounded-tr-3xl rounded-bl-3xl border-[1px] border-orange p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-zinc-800 capitalize">
                Active Filters
            </h1>
            <button @click="clearAllFilters" v-if="Object.keys(activeFilters).length > 0"
                    class="text-orange hover:text-orange-600 font-semibold transition-colors duration-300">
                Clear All
            </button>
        </div>
        <div class="active-filters flex flex-wrap gap-2">
            <TransitionGroup name="filter-fade">
                <div v-for="(value, key) in groupedFilters" :key="key" class="mb-2">
                    <h2 class="text-sm font-semibold text-zinc-600 mb-1 capitalize">{{ formatGroupLabel(key) }}</h2>
                    <div class="flex flex-wrap gap-2">
                        <div v-for="(filterValue, filterKey) in value" :key="filterKey"
                             class="filter-capsule flex items-center bg-white border border-orange rounded-full py-1 px-3 shadow-sm transition-all duration-300 hover:shadow-md">
                            <span class="text-zinc-800 text-sm">{{ formatFilterLabel(filterKey, filterValue) }}</span>
                            <button @click="removeFilter(filterKey)" class="ml-2 focus:outline-none">
                                <svg class="fill-zinc-500 w-3 h-3 hover:fill-orange transition-colors duration-300" viewBox="0 0 1024 1024"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
        <div v-if="Object.keys(activeFilters).length === 0" class="text-zinc-500 text-sm italic">
            No active filters
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

const groupedFilters = computed(() => {
    const groups = {
        personal: ['gender', 'age'],
        location: ['city', 'zone'],
        education: ['degree', 'freshGraduate'],
        work: ['workAvailability', 'experience'],
        specialization: ['mainSpecializations', 'subSpecialities']
    };

    return Object.entries(groups).reduce((acc, [group, keys]) => {
        const groupFilters = keys.reduce((groupAcc, key) => {
            if (activeFilters.value.hasOwnProperty(key)) {
                groupAcc[key] = activeFilters.value[key];
            }
            return groupAcc;
        }, {});

        if (Object.keys(groupFilters).length > 0) {
            acc[group] = groupFilters;
        }
        return acc;
    }, {});
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

const formatGroupLabel = (group) => {
    return group.replace(/([A-Z])/g, ' $1').trim();
};

const formatFilterLabel = (key, value) => {
    switch (key) {
        case 'workAvailability':
            return value ? 'Available for Work' : 'Not Looking for Work';
        case 'age':
            if (Array.isArray(value)) {
                const [min, max] = value;
                if (min === max) {
                    return `Age: ${min} ${min >= 60 ? 'years old' : 'years'}`;
                } else {
                    return `Age: ${min} - ${max} ${max >= 60 ? 'years old' : 'years'}`;
                }
            }
            return `Age: ${value}`;
        case 'freshGraduate':
            return 'Fresh Graduate';
        case 'mainSpecializations':
        case 'subSpecialities':
            return value.join(', ');
        case 'experience':
            if (Array.isArray(value)) {
                return `Experience: ${value[0]} - ${value[1]} years`;
            }
            return `Experience: ${value} years`;
        default:
            return value.toString();
    }
};

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

const clearAllFilters = () => {
    store.dispatch('resetFilters');
    store.dispatch('fetchFilteredApplicants', store.state.filters);
};
</script>

<style scoped>
.filter-fade-enter-active,
.filter-fade-leave-active {
    transition: all 0.3s ease;
}
.filter-fade-enter-from,
.filter-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
