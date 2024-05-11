<template>
    <div class="space-y-5 text-sm md:text-xs p-2 my-2">
        <div class="relative">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
            <select v-model="modelValue.degree"
                    class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                <option :value="degree" class="hidden" selected>Choose your degree...</option>
                <option v-for="option in degrees">{{ option }}</option>
            </select>
            <!--<h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

        </div>
        <div class="relative">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
            <input v-model="modelValue.institute" name="institute" placeholder="Educational Institute"
                   class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                   type="text">
            <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

        </div>
        <div class="relative">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
            <div class="flex">
                <select v-model="modelValue.duration[0]" name="startYear"
                        :class="degree !== 'Undergraduate' ?   'rounded-l-md' : 'rounded'"
                        class="focus:border-orange focus:ring-0 bg-zinc-50 w-full md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option selected>Start year</option>
                    <!-- Generate options for years from 2000 to current year -->
                    <template v-for="year in years">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
                <select v-if="degree !== 'Undergraduate'"

                        v-model="modelValue.duration[1]" name="endYear"
                        class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option class="hidden" selected>End year</option>
                    <!-- Generate options for years from 2000 to current year -->
                    <template v-for="year in years">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
            </div>
            <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

        </div>
    </div>
</template>

<script setup>
import {onMounted, ref, watchEffect} from "vue";
import {getSelectables} from "../../utils/storeHelpers.js";

const {modelValue} = defineProps(["modelValue"]);

const institute = ref(modelValue.institute)
const degree = ref(modelValue.degree)
const duration = ref(modelValue.duration)

const currentYear = ref(new Date().getFullYear());
const years = ref([]);
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
    axios.get('').then(async res => {
        degrees.value = await getSelectables('degrees');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });

    for (let year = 2000; year <= currentYear.value; year++) {
        years.value.push(year);
    }
});


</script>

<style scoped>

</style>
