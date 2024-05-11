<template>
    <!--preview-->
    <div class="relative grid grid-cols-12 gap-x-6 place-items-start">

        <div v-if="!editMode" class="col-span-8 w-full">

            <span
                :class="workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                class="font-semibold text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    {{ workAvailability ? 'Available for Work' : 'Not Available for work' }}
            </span>

            <h1 class="text-2xl text-orange font-semibold mt-4 tracking-wider">{{ fullName }}</h1>

        </div>
        <div v-else class="col-span-8 w-full space-y-6">
            <div class="flex">
                <span @click="workAvailability = !workAvailability"
                      :class="workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                      class="cursor-pointer font-semibold hover:bg-dark hover:text-white text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    Available for Work
            </span>
                <span @click="workAvailability = !workAvailability"
                      :class="!workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                      class="cursor-pointer font-semibold hover:bg-dark hover:text-white text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    Not Available for Work
            </span>
            </div>

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
import {ref, watch} from "vue";
import {editMode} from "../../utils/storeHelpers.js";

const workAvailability = ref(true)
const fullName = ref(null)


const emit = defineEmits(["update:modelValue"])

watch([workAvailability, fullName], () => {
    emit('update:modelValue',
        {
            workAvailability: workAvailability.value,
            fullName: fullName.value
        }
    )
}, {deep: true})
</script>


<style scoped>

</style>
