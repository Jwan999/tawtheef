<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-xl font-semibold text-dark">Summary</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white">
            <div v-if="!summary">
                <p class="text-sm text-zinc-700">
                    Data not filled yet.
                </p>
            </div>
            <p class="whitespace-pre-wrap">{{ summary }}</p>
        </div>
        <div v-else class="rounded-md bg-white">
            <div class="w-full">
                <label for="message" class="block mb-3 mt-2">Your professional summary is a
                    brief overview of your skills, experiences, and career goals. It's the first section employers will
                    read, so it's important to make it impactful and concise.</label>
                <div class="relative w-full mt-3">
                    <textarea @input="v$.summary.$touch()" v-model="summary" id="message" rows="4"
                              :class="{'border-red-500': v$.summary.$error}"
                              class="w-full focus:border-orange focus:ring-0 block p-2.5 w-full text-sm bg-zinc-50 rounded-md md:text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                              placeholder="Write your professional summary..."></textarea>

                    <div class="flex justify-between">
                        <div>
                            <span v-if="v$.summary.$error" class="text-red-500 text-xs">
                                {{ v$.summary.$errors[0]?.$message }}
                            </span>
                        </div>
                        <p class="text-sm text-zinc-500 mt-2 text-end order-last">
                            Word count: {{ wordCount }}/300
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, onUpdated, ref, watch} from 'vue';
import {countWords, editMode} from "../../utils/storeHelpers.js";
import {helpers, maxLength, minLength} from "@vuelidate/validators";
import {useVuelidate} from "@vuelidate/core";

const props = defineProps(["modelValue"])
const summary = ref('');

onUpdated(() => {
    if (props.modelValue !== summary.value) {
        summary.value = props.modelValue;
    }
})

onMounted(() => {
    summary.value = props.modelValue;
})

const emit = defineEmits(["update:modelValue"])

watch(summary, (newValue) => {
    emit('update:modelValue', newValue);
}, { deep: true })

const wordCount = computed(() => countWords(summary.value));

const customRequired = (value) => {
    return !!value && value.trim().length > 0;
};

const rules = computed(() => ({
    summary: {
        required: helpers.withMessage('Summary is required', customRequired),
        minLength: helpers.withMessage(
            () => `Summary should be at least 50 words (currently ${wordCount.value} words)`,
            minLength(50)
        ),
        maxLength: helpers.withMessage(
            () => `Summary should not exceed 300 words (currently ${wordCount.value} words)`,
            maxLength(1500)
        ),
        wordLimit: helpers.withMessage(
            () => `Summary should be between 50 and 300 words (currently ${wordCount.value} words)`,
            (value) => {
                const count = countWords(value);
                return count >= 50 && count <= 300;
            }
        )
    }
}));

const v$ = useVuelidate(rules, { summary });

watch(summary, () => {
    v$.value.$touch();
}, { deep: true });

const validateFields = () => {
    v$.value.$touch();
    return !v$.value.$invalid;
};

defineExpose({ validateFields });
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
