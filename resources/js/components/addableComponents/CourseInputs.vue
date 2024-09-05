<template>
    <div class="w-full space-y-4 py-6 pt-4">
        <label for="message" class="block mb-3 text-zinc-500 mt-2">* List the courses you've completed, that can show the skills you have.</label>

        <div class="md:flex flex-none md:space-x-3 space-x-0 md:space-y-0 space-y-5 items-center">

            <div class="relative w-full">

                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.title" placeholder="Course title"
                       class="w-full capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-6/12 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.entity" placeholder="Offering Entity"
                       class="w-full capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-6/12 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <select v-model="modelValue.duration" class="w-full focus:border-orange focus:ring-0 bg-zinc-50 w-6/12 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option value="" selected disabled>Course duration</option>
                    <option value="1 week">1 week</option>
                    <option value="2 weeks">2 weeks</option>
                    <option value="3 weeks">3 weeks</option>
                    <option value="1 month">1 month</option>
                    <option value="2 months">2 months</option>
                    <option value="3 months">3 months</option>
                    <option value="6 months">6 months</option>
                    <option value="9 months">9 months</option>
                    <option value="1 year">1 year</option>
                </select>
<!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>


        </div>

    </div>

</template>

<script setup>
import {ref, watchEffect} from "vue";
import {countWords} from "../../utils/storeHelpers.js";

const {modelValue} = defineProps(["modelValue"]);
const title = ref(modelValue.title);
const entity = ref(modelValue.entity);
const duration = ref(modelValue.duration);

const emit = defineEmits(["update:modelValue"]);

watchEffect(() => {
    emit("update:modelValue", {
        title: title.value,
        entity: entity.value,
        duration: duration.value,
    });
});

</script>

<style scoped>

</style>
