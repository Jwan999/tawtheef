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
            <p class="capitalize">{{ summary }}</p>
        </div>
        <div v-else class="rounded-md bg-white">

            <div class="w-full">
                <label for="message" class="block mb-3 mt-2">Your professional summary is a
                    brief overview of your skills, experiences, and career goals. It's the first section employers will
                    read, so it's important to make it impactful and concise.</label>
                <div class="relative w-full mt-3">
                    <textarea @input="countWords(summary)" v-model="summary" id="message" rows="4"
                              :class="{'border-red-500': v$.summary.$error}"
                              @blur="v$.summary.$touch"
                              class="w-full capitalize focus:border-orange focus:ring-0 block p-2.5 w-full text-sm bg-zinc-50 rounded-md md:text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                              placeholder="Write your professional summary..."></textarea>

             <div class="flex justify-between">
                 <div>
                      <span v-show="showErrors && v$.summary.$error"
                            class="text-red-500 text-xs">Summary is required</span>
                 </div>

                 <p class="text-sm text-zinc-500 mt-2 text-end capitalize order-last">Word count: {{ countWords(summary) }}/300</p>

             </div>

                    <!--                    <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->
                </div>


            </div>

        </div>
    </div>

</template>

<script setup>
import {computed, onMounted, onUpdated, ref, watch} from 'vue'; // Import ref for reactive variables
import {editMode} from "../../utils/storeHelpers.js";
import {countWords} from "../../utils/storeHelpers.js";
import {email as emailValidator, helpers, maxLength, required} from "@vuelidate/validators";
import {useVuelidate} from "@vuelidate/core";

const props = defineProps(["modelValue"])
const summary = ref('');

onUpdated(() => {
    summary.value = props.modelValue
});
onMounted(() => {
    summary.value = props.modelValue
});

const emit = defineEmits(["update:modelValue"])

watch([summary], () => {
    emit('update:modelValue',
        summary.value
    )
}, {deep: true})


const showErrors = ref(false);
const rules = computed(() => ({
    summary: {
        required,
        maxLength: maxLength(300),
        minWords: (value) => countWords(value) >= 50 || 'Summary should be at least 50 words',
        maxWords: (value) => countWords(value) <= 300 || 'Summary should not exceed 300 words'
    }
}));
// Updated validation rules

const v$ = useVuelidate(rules, {summary});

// Updated watch function
watch([summary], () => {
    v$.value.$touch();
    let isValid = !v$.value.$invalid;
}, {deep: true});

// Updated validateFields method
const validateFields = () => {
    v$.value.$touch();
    showErrors.value = true;

    setTimeout(() => {
        showErrors.value = false;
    }, 30000); // Hide errors after 30 seconds

    return !v$.value.$invalid;
};

// Expose the validateFields method to the parent component
defineExpose({validateFields});


</script>

<style scoped>

</style>
