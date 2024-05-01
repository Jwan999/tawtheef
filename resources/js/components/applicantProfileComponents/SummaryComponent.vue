<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Summary</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white">
            <p>{{ summary }}</p>

        </div>
        <div v-else class="rounded-md p-4 bg-white">

            <div class="w-full">
                <label for="message" class="block mb-2 text-xs font-medium text-dark">Lorem ipsum dolor sit
                    amet, consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed
                    voluptatibus?</label>
                <div class="relative w-full mt-3">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                    <textarea v-model="summary" id="message" rows="4"
                              class="w-full focus:border-orange focus:ring-0 block p-2.5 w-full text-sm bg-slate-50 rounded-md md:text-xs border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                              placeholder="Write your thoughts here..."></textarea>
<!--                    <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

                </div>

            </div>

        </div>
    </div>

</template>

<script setup>
import {ref, watch} from 'vue'; // Import ref for reactive variables
import {editMode} from "../../utils/storeHelpers.js";
const summary = ref('');

const emit = defineEmits(["update:modelValue"])

watch([summary], () => {
    emit('update:modelValue',
        summary.value,
    )
},{deep: true})

</script>

<style scoped>

</style>
