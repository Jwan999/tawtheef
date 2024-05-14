<template>

    <div class="space-y-4 w-full pr-0">
        <div class="flex space-x-3 items-center">
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.title" placeholder="Job title"
                       class="w-full focus:border-orange focus:ring-0 bg-zinc-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>

            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.employer" placeholder="Employer"
                       class="w-full focus:border-orange focus:ring-0 bg-zinc-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>


            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>

                <div class="flex">
                    <select v-model="modelValue.duration[0]" name="startYear"
                            class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-l-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                        <option selected>Start year</option>
                        <template v-for="year in years">
                            <option :value="year">{{ year }}</option>
                        </template>
                    </select>
                    <select v-model="modelValue.duration[1]" name="endYear"
                            class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                        <option class="hidden" selected>End year</option>
                        <template v-for="year in years">
                            <option :value="year">{{ year }}</option>
                        </template>
                    </select>
                </div>

                <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>

        </div>
        <div class="w-full">
            <label for="message" class="block mb-2 text-xs font-medium text-dark mt-2">Lorem ipsum dolor sit
                amet, consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed
                voluptatibus?</label>


            <div class="relative w-full mt-3">

                <div class="relative w-full">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>
                    <input type="text" v-model="task" name="Task" placeholder="Responsibilities"
                           class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"/>

                </div>

                <button type="submit" @click="addTask"
                        class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-orange rounded-e-md  hover:bg-dark focus:outline-none">
                    <svg class="w-2 h-2 fill-white" viewBox="0 0 512 512">
                        <g>
                            <path
                                d="m467 211h-166v-166c0-24.853-20.147-45-45-45s-45 20.147-45 45v166h-166c-24.853 0-45 20.147-45 45s20.147 45 45 45h166v166c0 24.853 20.147 45 45 45s45-20.147 45-45v-166h166c24.853 0 45-20.147 45-45s-20.147-45-45-45z"/>
                        </g>
                    </svg>
                </button>
            </div>

            <div class="mt-3 space-y-2">
                <div v-for="(task,index) in allTasks" class="flex items-center justify-between">
                    <div class="flex items-start w-full space-x-3">
                        <span class="flex-none mt-2 w-2 h-2 bg-dark rounded-full"></span>
                        <p class="text-sm font-semibold">{{ task }}</p>
                    </div>

                    <button @click="removeTask(index)" class="appearance-none cursor-pointer">
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

const task = ref('')
const allTasks = ref([])
const addTask = () => {
    if (task.value.trim() !== '') {
        allTasks.value.push(task.value);
        task.value = '';
    }
}

const removeTask = (index) => {
    allTasks.value.splice(index,1)
}

const {modelValue} = defineProps(["modelValue"]);

const title = ref(modelValue.title)
const employer = ref(modelValue.employer)
const duration = ref(modelValue.duration)
// const tasks = ref(modelValue.tasks)

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
        tasks: allTasks.value
    });
});

</script>

<style scoped>

</style>
