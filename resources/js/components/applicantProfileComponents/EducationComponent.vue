<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Education</h1>

            <h1 class="font-semibold">{{ university }}</h1>
            <h1 class="text-dark font-semibold">{{ degree }}
                <span class="">{{ college }}</span>
            </h1>
            <h1 class="text-orange font-semibold">{{ duration[0] + '-' + duration[1] }}</h1>
        </div>

    </div>

    <div v-else class="rounded-md p-4 bg-white text-sm md:text-xs">
        <h1 class="text-lg font-semibold text-dark pb-4">Education</h1>

        <div class="space-y-5 text-sm md:text-xs ">
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input @input="emitInputData" v-model="university" name="university" placeholder="University"
                       class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <select @change="emitInputData" v-model="degree"
                        class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-l-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none">
                    <option selected>Choose a degree</option>
                    <option>Bachelor's Degree</option>
                    <option>Master's Degree</option>
                    <option>Doctorate (Ph.D.)</option>
                </select>
                <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input @input="emitInputData" v-model="college" name="college" placeholder="College"
                       class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <div class="flex">
                    <select @change="emitInputData" v-model="duration[0]" name="startYear"
                            class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-l-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none">
                        <option selected>Start year</option>
                        <!-- Generate options for years from 2000 to current year -->
                        <template v-for="year in years">
                            <option :value="year">{{ year }}</option>
                        </template>
                    </select>
                    <select @change="emitInputData" v-model="duration[1]" name="endYear"
                            class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-r-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none">
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


    </div>

</template>

<script setup>
import {onMounted, ref, watch} from "vue";
import {editMode} from "../../utils/storeHelpers.js";

const university = ref('')
const degree = ref('Choose a degree')
const college = ref('')
const duration = ref(['Start year', 'End year'])

const currentYear = ref(new Date().getFullYear());
const years = ref([]);

onMounted(() => {
    for (let year = 2000; year <= currentYear.value; year++) {
        years.value.push(year);
    }
});

const emit = defineEmits(["update:modelValue"])

watch([university,degree,college,duration], () => {
    emit('update:modelValue',{
        university: university.value,
        degree: degree.value,
        college: college.value,
        duration: duration.value,
    })
},{deep: true})


</script>

<style scoped>

</style>
