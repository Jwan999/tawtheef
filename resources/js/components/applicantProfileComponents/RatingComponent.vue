<template>
    <div class="py-2 space-y-4">
        <div class="relative">
            <select
                v-if="placeholderLabel === 'Language'"
                v-model="item"
                :value="item"
                class="capitalize text-sm focus:border-orange rounded focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none py-2 px-3"
            >
                <option value="" class="hidden" selected>Languages</option>
                <option class="notranslate" v-for="language in languages" :key="language">{{ language }}</option>
            </select>

            <div v-else class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>
                <input
                    type="text"
                    v-model="item"
                    :placeholder="placeholderLabel"
                    class="capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none py-2 px-3"
                />
            </div>
        </div>

        <div class="space-y-2">
            <h2 class="font-semibold text-zinc-500 text-sm">Competency Level:</h2>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="(level, value) in competencyLevels"
                    :key="value"
                    @click="setRatingValue(value)"
                    :class="[
                        'px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 ease-in-out',
                        isSelectedRating(value)
                            ? 'bg-orange text-white'
                            : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                    ]"
                >
                    {{ level }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { getSelectables } from "../../utils/storeHelpers.js";

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({ item: '', rating: null })
    },
    placeholderLabel: {
        type: String,
        default: "Language"
    }
});

const emit = defineEmits(["update:modelValue"]);

const item = ref(props.modelValue.item);
const rating = ref(props.modelValue.rating);
const languages = ref([]);

const competencyLevels = {
    1: 'Basic',
    2: 'Intermediate',
    3: 'Good',
    4: 'Very Good',
    5: 'Excellent',
};

watchEffect(() => {
    emit('update:modelValue', {
        item: item.value,
        rating: rating.value,
    });
});

onMounted(async () => {
    try {
        languages.value = await getSelectables('languages');
    } catch (error) {
        console.error('Failed to fetch language options:', error);
    }
});

const setRatingValue = (value) => {
    rating.value = String(value);
};

const isSelectedRating = (value) => {
    return rating.value === value || rating.value === String(value);
};
</script>

<style scoped>
/* Add any additional styles here if needed */
</style>
