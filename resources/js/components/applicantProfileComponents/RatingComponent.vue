<template>
    <div class="py-2 space-y-4">
        <div class="relative">
            <select
                v-if="placeholderLabel === 'Language'"
                v-model="item"
                :value="item"
                @blur="v$.item.$touch"
                :class="{'border-red-500': v$.item.$error}"
                class="capitalize text-sm focus:border-orange rounded focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none py-2 px-3">
                <option value="" class="hidden" selected>Languages</option>
                <option class="notranslate" v-for="language in languages" :key="language">{{ language }}</option>
            </select>

            <div v-else class="relative">
                <input
                    type="text"
                    v-model="item"
                    :placeholder="placeholderLabel"
                    @blur="v$.item.$touch"
                    :class="{'border-red-500': v$.item.$error}"
                    class="capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none py-2 px-3"
                />
            </div>
        </div>
        <div v-if="v$.item.$error && showErrors" class="text-red-500 text-xs mt-1">
            Item is required
        </div>

        <div class="space-y-2">
            <h2 class="font-semibold text-zinc-500 text-sm ">Competency Level:</h2>
            <div class="flex flex-wrap gap-2 mt-2">
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
        <div v-if="v$.rating.$error && showErrors" class="text-red-500 text-xs mt-1">
            Competency level is required
        </div>
    </div>
</template>

<script setup>
import { ref, watchEffect, onMounted, computed, watch } from 'vue';
import { getSelectables } from "../../utils/storeHelpers.js";
import { required } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({item: '', rating: null})
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

const showErrors = ref(false);
const rules = computed(() => ({
    item: { required },
    rating: { required }
}));

const v$ = useVuelidate(rules, { item, rating });

watch([item, rating], () => {
    v$.value.$touch();
}, { deep: true });

const validateFields = () => {
    v$.value.$touch();
    showErrors.value = true;

    setTimeout(() => {
        showErrors.value = false;
    }, 30000); // Hide errors after 30 seconds

    return !v$.value.$invalid;
};

defineExpose({ validateFields });
</script>
