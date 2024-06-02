<template>

    <div class="space-y-4 w-full pr-0">
        <div class="flex space-x-3 items-center">

            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.title" placeholder="Job title"
                       class="w-full capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-4/12 rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>

            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.employer" placeholder="Employer"
                       class="w-full capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-4/12 rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>


            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>

                <div class="flex">
                    <select :value="modelValue.duration[0]" v-model="modelValue.duration[0]" name="startYear"
                            class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-l-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">

                        <option selected>{{ modelValue.duration[0] }}
                        </option>
                        <template v-for="year in years">
                            <option :value="year">{{ year }}</option>
                        </template>
                    </select>
                    <select :value="modelValue.duration[1]" v-model="modelValue.duration[1]" name="endYear"
                            class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">

                        <option selected>{{ modelValue.duration[1] }}
                        </option>
                        <template v-for="year in years">
                            <option :value="year">{{ year }}</option>
                        </template>
                        <option>To the present</option>

                    </select>
                </div>

                <!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>

        </div>
        <div class="w-full">
            <label for="message" class="block mb-2 text-sm font-medium text-zinc-700 mt-2">List your responsibilities
                within this period of employment.</label>


            <div class="relative w-full mt-3">

                <div class="relative w-full">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>
                    <input type="text" v-model="responsibility" name="Responsibility" placeholder="Responsibilities"
                           class="focus:border-orange capitalize focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"/>

                </div>

                <button type="submit" @click="addResponsibility"
                        class="absolute top-0 end-0 px-3 text-sm h-full font-semibold text-white bg-orange rounded-e-md hover:bg-dark focus:outline-none">
                    ADD
                </button>
            </div>

            <div class="mt-3 space-y-2">
                <div v-for="(responsibility,index) in responsibilities" class="flex items-center justify-between">
                    <div class="flex items-start w-full space-x-3">
                        <span class="flex-none mt-2 w-2 h-2 bg-dark rounded-full"></span>
                        <p class="text-base">{{ responsibility }}</p>
                    </div>

                    <button @click="removeResponsibility(index)" class="appearance-none cursor-pointer">
                        <svg class="fill-dark w-4 h-4" viewBox="0 0 1024 1024"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                        </svg>
                    </button>
                </div>

            </div>

        </div>
    </div>


</template>

<script setup>
import {ref, watchEffect} from 'vue';

const responsibility = ref('')
const addResponsibility = () => {
    if (responsibility.value.trim() !== '') {
        responsibilities.value.push(responsibility.value);
        responsibility.value = '';
    }
}

const removeResponsibility = (index) => {
    responsibilities.value.splice(index, 1)
}

const {modelValue} = defineProps(["modelValue"]);

const title = ref(modelValue.title)
const employer = ref(modelValue.employer)
const duration = ref([modelValue.duration[0], modelValue.duration[1]])
const responsibilities = ref(modelValue.responsibilities)

modelValue.duration[0] = 'Start year'
modelValue.duration[1] = 'End year'

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
        employer: employer.value,
        duration: duration.value,
        responsibilities: responsibilities.value
    });
});


</script>

<style scoped>

</style>
