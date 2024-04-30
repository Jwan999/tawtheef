<template>

    <div class="space-y-4 w-full p-6 pr-0">
        <div class="flex space-x-3 items-center">
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.title" placeholder="Job title"
                       class="w-full focus:border-orange focus:ring-0 bg-slate-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>

            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.employer" placeholder="Employer"
                       class="w-full focus:border-orange focus:ring-0 bg-slate-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>


            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.duration" placeholder="Duration of employment"
                       class="w-full focus:border-orange focus:ring-0 bg-slate-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>

        </div>
        <div class="w-full">
            <label for="message" class="block mb-2 text-xs font-medium text-dark mt-2">Lorem ipsum dolor sit
                amet, consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed
                voluptatibus?</label>
            <div class="relative w-full mt-3">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <textarea id="message" rows="4" v-model="modelValue.description"
                          class="w-full focus:border-orange focus:ring-0 block p-2.5 w-full text-sm bg-slate-50 rounded-md md:text-xs border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                          placeholder="Write your thoughts here..."></textarea>
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>

        </div>
    </div>


</template>

<script setup>
import {ref, watchEffect} from 'vue';

const {modelValue} = defineProps(["modelValue"]);

const title = ref(modelValue.title)
const employer = ref(modelValue.employer)
const duration = ref(modelValue.duration)
const description = ref(modelValue.description)

const emit = defineEmits(["update:modelValue"]);

watchEffect(() => {
    emit("update:modelValue", {
        title: title.value,
        employer: employer.value,
        duration: duration.value,
        description: description.value
    });
});
</script>

<style scoped>

</style>
