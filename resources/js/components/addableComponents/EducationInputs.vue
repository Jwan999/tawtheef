<template>
    <div class="space-y-5 text-sm py-6 my-2">
        <div class="relative">
            <select v-model="modelValue.degree"
                    :class="{'border-red-500': v$.degree.$error}"
                    @blur="v$.degree.$touch"
                    class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                <option value="" class="hidden" selected>Choose your degree...</option>
                <option class="notranslate" v-for="option in degrees" :key="option">{{ option }}</option>
            </select>
            <div v-if="showErrors && v$.degree.$error" class="text-red-500 text-xs mt-1">
                Degree is required
            </div>
        </div>
        <div class="relative">
            <input v-model="modelValue.institute"
                   :class="{'border-red-500': v$.institute.$error}"
                   @blur="v$.institute.$touch"
                   name="institute"
                   placeholder="Educational Institute"
                   class="focus:border-orange capitalize focus:ring-0 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                   type="text">
            <div v-if="showErrors && v$.institute.$error" class="text-red-500 text-xs mt-1">
                Institute is required
            </div>
        </div>
        <div class="relative">
            <div class="flex">
                <select v-model="modelValue.duration[0]"
                        :class="[{'border-red-500': v$.duration.$error}, localDegree === 'Undergraduate' ? 'rounded' : 'rounded-l-md']"
                        @blur="v$.duration.$touch"
                        name="startYear"
                        class="focus:border-orange focus:ring-0 bg-zinc-50 w-full text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option selected>{{ modelValue.duration[0] }}</option>
                    <template v-for="year in years" :key="year">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
                <select v-if="localDegree !== 'Undergraduate'"
                        v-model="modelValue.duration[1]"
                        :class="{'border-red-500': v$.duration.$error}"
                        @blur="v$.duration.$touch"
                        name="endYear"
                        class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option selected>{{ modelValue.duration[1] }}</option>
                    <template v-for="year in futureYears" :key="year">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
            </div>
            <div v-if="showErrors && v$.duration.$error" class="text-red-500 text-xs mt-1">
                Valid duration is required
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watchEffect, computed } from "vue";
import { getSelectables } from "../../utils/storeHelpers.js";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    }
});

const showErrors = ref(false);
const localDegree = computed(() => props.modelValue.degree);

const rules = computed(() => ({
    degree: { required },
    institute: { required },
    duration: {
        required,
        validDuration: (value) => value[0] !== 'Start Year' && value[1] !== 'End Year'
    }
}));

const v$ = useVuelidate(rules, props.modelValue);

const currentYear = ref(new Date().getFullYear());
const years = ref([]);
const futureYears = ref([]);
const degrees = ref([]);

const emit = defineEmits(["update:modelValue"]);

watchEffect(() => {
    emit("update:modelValue", props.modelValue);
});

onMounted(async () => {
    try {
        degrees.value = await getSelectables('degrees');
    } catch (error) {
        console.error('Failed to fetch select options:', error);
    }

    years.value = Array.from({ length: currentYear.value - 1949 }, (_, i) => currentYear.value - i);
    futureYears.value = Array.from({ length: 61 }, (_, i) => currentYear.value - 40 + i);
});

const validateFields = () => {
    v$.value.$touch();
    showErrors.value = true;

    setTimeout(() => {
        showErrors.value = false;
    }, 30000);

    return !v$.value.$invalid;
};

defineExpose({ validateFields });
</script>
