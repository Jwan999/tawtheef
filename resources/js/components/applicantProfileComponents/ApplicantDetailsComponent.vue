<template>
    <!--preview-->
    <div class="relative grid grid-cols-12 gap-x-6 place-items-start">


        <div v-if="!editMode" class="col-span-8 w-full space-y-6">

            <span
                :class="workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                class="font-semibold text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-orange-900 dark:text-orange-300">
                                {{ workAvailability ? 'Available for Work' : 'Not Available for work' }}
            </span>
            <h1 class="text-2xl text-orange font-semibold mt-4 tracking-wider">{{ fullName }}</h1>

        </div>
        <div v-else class="col-span-8 w-full">

            <label class="inline-flex items-center mb-5 cursor-pointer">
                <input type="checkbox" value="" v-model="workAvailability" class="sr-only peer">
                <div
                    class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none peer-focus:ring-orange dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-zinc-600 peer-checked:bg-orange"></div>
                <span class="ms-3 text-sm font-semibold text-zinc-700">Available for work </span>
            </label>
            <div class="">
                <span
                    class="block mb-2 text-xs font-medium text-zinc-600">Please provide your First and Last name.</span>

                <div class="relative">
                    <input @input="emitDetails" v-model="fullName"
                           placeholder="Full name"
                           class="bg-zinc-50 w-full rounded-md text-xl text-dark py-1 tracking-wider font-semibold border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           type="text">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>

                </div>

                <!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->
            </div>
        </div>
    </div>

</template>


<script setup>
import {onMounted, ref, watch} from "vue";
import {editMode} from "../../utils/storeHelpers.js";

const workAvailability = ref(false)
const fullName = ref('')


const emit = defineEmits(["update:modelValue"])

const updateModelValue = () => {
    emit('update:modelValue', {
        workAvailability: workAvailability.value,
        fullName: fullName.value
    });
};
watch([workAvailability, fullName], updateModelValue, {deep: true});

onMounted(() => {
    updateModelValue();
});

</script>


<style scoped>

</style>
