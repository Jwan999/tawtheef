<template>
    <div class="flex space-x-2">
        <div class="w-full">
            <label :for="startId" class="block text-sm font-medium text-gray-700">{{ startLabel }}</label>
            <select
                :id="startId"
                v-model="localStartYear"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange focus:ring-orange sm:text-sm"
            >
                <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
            </select>
        </div>
        <div class="w-full">
            <label :for="endId" class="block text-sm font-medium text-gray-700">{{ endLabel }}</label>
            <select
                :id="endId"
                v-model="localEndYear"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange focus:ring-orange sm:text-sm"
            >
                <option class="notranslate" v-if="allowPresent" value="present">To present</option>
                <option v-for="year in availableEndYears" :key="year" :value="year">{{ year }}</option>
            </select>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => ['', ''],
    },
    startYear: {
        type: Number,
        default: 1950,
    },
    endYear: {
        type: Number,
        default: () => new Date().getFullYear() + 10,
    },
    allowPresent: {
        type: Boolean,
        default: false,
    },
    startLabel: {
        type: String,
        default: 'Start Year',
    },
    endLabel: {
        type: String,
        default: 'End Year',
    },
});

const emit = defineEmits(['update:modelValue']);

const startId = computed(() => `start-${Math.random().toString(36).substr(2, 9)}`);
const endId = computed(() => `end-${Math.random().toString(36).substr(2, 9)}`);

const localStartYear = ref(props.modelValue[0] || '');
const localEndYear = ref(props.modelValue[1] || '');

const availableYears = computed(() => {
    const years = [];
    for (let year = props.startYear; year <= props.endYear; year++) {
        years.push(year);
    }
    return years;
});

const availableEndYears = computed(() => {
    return availableYears.value.filter(year => year >= localStartYear.value);
});

watch([localStartYear, localEndYear], ([newStart, newEnd]) => {
    emit('update:modelValue', [newStart, newEnd]);
});

onMounted(() => {
    if (!props.modelValue || props.modelValue.length !== 2) {
        emit('update:modelValue', [localStartYear.value, localEndYear.value]);
    }
});
</script>
