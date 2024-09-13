<template>
    <div class="mb-6 bg-zinc-700 rounded-lg p-4">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold">Active Filters</h3>
            <button @click="clearAllFilters" v-if="Object.keys(groupedFilters).length > 0"
                    class="text-orange hover:text-orange-300 text-sm font-semibold transition-colors duration-300">
                Clear All
            </button>
        </div>
        <div class="active-filters flex flex-wrap gap-2">
            <TransitionGroup name="filter-fade">
                <div v-for="(value, key) in groupedFilters" :key="key" class="mb-2">
                    <div class="flex flex-wrap gap-2">
                        <template v-for="(filterValue, filterKey) in value" :key="filterKey">
                            <div v-for="(item, index) in formatFilterLabel(filterKey, filterValue)" :key="`${filterKey}-${index}`"
                                 class="filter-capsule flex items-center text-zinc-700 border-[1px] border-orange bg-white rounded-full py-1 px-3 text-sm transition-all duration-300 hover:text-white hover:bg-zinc-700">
                                <span class="mr-1 font-medium">{{ formatGroupLabel(key) }}:</span>
                                <span>{{ item }}</span>
                                <button @click="removeFilter(filterKey, index)" class="ml-2 focus:outline-none">
                                    <svg class="fill-zinc-400 w-3 h-3 hover:fill-orange transition-colors duration-300" viewBox="0 0 1024 1024"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </TransitionGroup>
        </div>
        <div v-if="Object.keys(groupedFilters).length === 0" class="text-zinc-400 text-sm italic">
            No active filters
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const props = defineProps({
    filters: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['update:filter', 'clear-all']);

const groupedFilters = computed(() => {
    if (!props.filters) return {};

    const groups = {
        personal: ['gender', 'age'],
        location: ['city', 'zone'],
        education: ['degree', 'freshGraduate'],
        work: ['workAvailability', 'experience'],
        specialization: ['mainSpecializations', 'subSpecialities']
    };

    return Object.entries(groups).reduce((acc, [group, keys]) => {
        const groupFilters = keys.reduce((groupAcc, key) => {
            if (props.filters.hasOwnProperty(key) && !isEmptyValue(props.filters[key])) {
                groupAcc[key] = props.filters[key];
            }
            return groupAcc;
        }, {});

        if (Object.keys(groupFilters).length > 0) {
            acc[group] = groupFilters;
        }
        return acc;
    }, {});
});

const formatGroupLabel = (group) => {
    return group.charAt(0).toUpperCase() + group.slice(1);
};

const formatFilterLabel = (key, value) => {
    switch (key) {
        case 'workAvailability':
            return [value ? 'Available' : 'Not Available'];
        case 'age':
        case 'experience':
            return [`${value.min} - ${value.max} years`];
        case 'freshGraduate':
            return ['Fresh Graduate'];
        case 'mainSpecializations':
        case 'subSpecialities':
            if (Array.isArray(value)) {
                return value.map(item => item.toString());
            }
            return [value.toString()];
        default:
            return [value.toString()];
    }
};

const isEmptyValue = (value) => {
    return value === '' || value === null || value === undefined || (Array.isArray(value) && value.length === 0);
};

const clearAllFilters = () => {
    emit('clear-all');
};

const removeFilter = (key, index) => {
    let updatedValue = props.filters[key];

    if (Array.isArray(updatedValue)) {
        updatedValue = [...updatedValue];
        updatedValue.splice(index, 1);
        if (updatedValue.length === 0) {
            updatedValue = null;
        }
    } else if (typeof updatedValue === 'object' && updatedValue !== null) {
        // Handle object types (like age and experience ranges)
        updatedValue = null;
    } else {
        // For primitive types, just set to null
        updatedValue = null;
    }

    emit('update:filter', key, updatedValue);
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
