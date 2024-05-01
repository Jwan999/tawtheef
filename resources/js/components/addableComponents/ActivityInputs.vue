<template>


    <div class="w-full p-6 pr-0">
        <label for="message" class="block mb-2 text-xs font-medium text-dark">Lorem ipsum dolor
            sit amet,
            consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed voluptatibus?</label>

        <div class="flex space-x-3 items-center w-full mt-4">

            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.title"
                       placeholder="Activity or event title"
                       class="w-full focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input
                       v-model="modelValue.participatedAs"
                       placeholder="Participated as"
                       class="w-full focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <select v-model="modelValue.year" class="w-full focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none">
                    <option value="" selected disabled>Year</option>
                    <!-- Use a loop to generate options for years from 2000 to the current year -->
                    <template v-for="year in years">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>


        </div>
    </div>


</template>

<script setup>
import {ref, watchEffect} from 'vue';

const {modelValue} = defineProps(["modelValue"]);

const title = ref(modelValue.title)
const participatedAs = ref(modelValue.participatedAs)
const year = ref(modelValue.year)

const years = ref([]);

// Compute the current year
const currentYear = new Date().getFullYear();

// Populate the years array with the years from 2000 to the current year
for (let year = 2000; year <= currentYear; year++) {
    years.value.push(year);
}

const emit = defineEmits(["update:modelValue"]);

watchEffect(() => {
    emit("update:modelValue", {
        title: title.value,
        participatedAs: participatedAs.value,
        year: year.value
    });
});
</script>

<style scoped>

</style>
