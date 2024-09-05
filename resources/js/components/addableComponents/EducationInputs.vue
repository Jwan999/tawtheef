<template>
    <div class="space-y-5 text-sm py-6 my-2">
        <div class="relative">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
            <select v-model="modelValue.degree"
                    class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                <option value="" class="hidden" selected>Choose your degree...</option>
                <option v-for="option in degrees">{{ option }}</option>
            </select>
        </div>
        <div class="relative">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
            <input v-model="modelValue.institute" name="institute" placeholder="Educational Institute"
                   class="focus:border-orange capitalize focus:ring-0 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                   type="text">
        </div>
        <div class="relative">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
            <div class="flex">
                <select v-model="modelValue.duration[0]" name="startYear"
                        :class="degree !== 'Undergraduate' ?   'rounded-l-md' : 'rounded'"
                        class="focus:border-orange focus:ring-0 bg-zinc-50 w-full text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option selected>{{ modelValue.duration[0] }}</option>
                    <template v-for="year in years">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
                <select v-if="degree !== 'Undergraduate'"
                        v-model="modelValue.duration[1]" name="endYear"
                        class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option selected>{{ modelValue.duration[1] }}</option>
                    <template v-for="year in futureYears">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watchEffect, computed } from "vue";
import { getSelectables } from "../../utils/storeHelpers.js";

const { modelValue } = defineProps(["modelValue"]);

const institute = ref(modelValue.institute)
const degree = ref(modelValue.degree)
const duration = ref(modelValue.duration)

const currentYear = ref(new Date().getFullYear());
const years = ref([]);
const futureYears = ref([]);
const degrees = ref([])

const emit = defineEmits(["update:modelValue"]);

watchEffect(() => {
    emit("update:modelValue", {
        institute: institute.value,
        degree: degree.value,
        duration: duration.value,
    });
});

onMounted(async () => {
    try {
        degrees.value = await getSelectables('degrees');
    } catch (error) {
        console.error('Failed to fetch select options:', error);
    }

    // Generate years from current year to 1950 (past)
    for (let year = currentYear.value; year >= 1950; year--) {
        years.value.push(year);
    }

    const startYear = currentYear.value - 10;
    const endYear = currentYear.value + 10;

    for (let year = startYear; year <= endYear; year++) {
        futureYears.value.push(year);
    }
});
</script>

<style scoped>
</style>
