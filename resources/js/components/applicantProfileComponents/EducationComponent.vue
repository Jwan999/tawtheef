<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Educational Background</h1>
            <div v-if="modelValue[0]?.degree"
                 v-for="item in modelValue">
                <h1 class="text-dark font-semibold">{{ item.degree }}</h1>
                <h1 class="font-semibold">{{ item.institute }}</h1>
                <h1 class="font-semibold text-orange">{{ item.duration[0] + ' - ' + item.duration[1] }}</h1>
            </div>

            <div v-if="!modelValue[0]?.degree">
                <p class="text-sm text-zinc-700">
                    Not all data filled yet.
                </p>
            </div>

        </div>

    </div>

    <div v-else class="rounded-md p-4 bg-white text-sm md:text-xs">
        <h1 class="text-lg font-semibold text-dark pb-4">Educational Background</h1>
        <button

            @click="addNew"
            class="mt-4 flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
            Add component

        </button>
        <div class="relative w-full">
            <div class="">
                <div v-for="(item,index) in value"
                     :key="index"
                     class=" rounded-bl-md border-0 border-r-[1px] border-b-[1px]"
                     :class="hoveredElement===index ? borderColor : 'border-zinc-100'">
                    <div class="flex justify-end">
                        <button @mouseover="changeBorderColor(index,'border-dark')"
                                @mouseleave="changeBorderColor(index,'border-zinc-100')"
                                @click="remove(index)"
                                class="flex-none w-auto hover:text-white appearance-none px-3 py-1 rounded-bl-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
                            <svg class="fill-current fill-orange w-4 h-4 " viewBox="0 0 1024 1024"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                            </svg>
                        </button>
                    </div>

                    <EducationInputs v-model="value[index]"></EducationInputs>
                </div>

            </div>

        </div>


    </div>

</template>

<script setup>
import EducationInputs from "../addableComponents/EducationInputs.vue";
import {onMounted, ref, watch} from 'vue';
import {editMode} from "../../utils/storeHelpers.js";


const hoveredElement = ref(null)
const borderColor = ref('border-zinc-100')
const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const {modelValue} = defineProps(["modelValue"]);
const value = ref([])

const addNew = () => {
    // duration: ['Start year', 'End year']
    value.value.push({
        degree: "Bachelor's Degree", institute: "Test", duration: ['2020', '2024']
    });
}
const remove = (index) => {
    value.value.splice(index, 1)
}

const emit = defineEmits(["update:modelValue"])

watch(value, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})

onMounted(() => {
    value.value = modelValue;
    if (modelValue.length == 0) {
        addNew();
    }
})

</script>

<style scoped>

</style>
