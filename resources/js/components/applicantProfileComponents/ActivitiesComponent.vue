<template>
    <div class="w-full space-y-4">

        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-xl font-semibold text-dark">Events and Activities</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-3">
            <div v-for="(year, index) in uniqueYears" :key="index">

                <h1 v-if="year !== 'Year'" class="text-orange font-semibold text-base mb-2">{{ year }}</h1>
                <ul class="text-lg ">
                    <li v-for="event in value.filter(item => item.year === year)"
                        class="flex space-x-2 items-center ml-5">

                        <h1 class="font-semibold capitalize">{{ event.title }}</h1>
                        <h1 v-if="event.participatedAs !== 'Participated as'">as</h1>
                        <h1 v-if="event.participatedAs !== 'Participated as'" class="text-orange capitalize font-semibold">
                            {{ event.participatedAs }}</h1>
                    </li>

                </ul>
            </div>
            <div v-if="!props?.modelValue[0]?.title">
                <p class="text-sm text-zinc-700">
                    Data not filled yet.
                </p>
            </div>
        </div>


        <div v-else class="rounded-md bg-white pb-6">
            <div class="space-y-6">
                <label for="message" class="block mb-3 mt-2">These fields are optional, and their
                    purpose is to show if the individual has other related activities.</label>
                <div v-for="(item,index) in value" :key="index">
                    <div class="w-full">
                        <div class="flex justify-end">
                            <button @click="remove(index)"
                                    class="appearance-none bg-orange rounded-full px-1 text-sm font-semibold group tracking-wide py-1 hover:bg-dark hover:bg-dark">
                                <svg class="fill-current group-hover:fill-white fill-white w-4 h-4 " viewBox="0 0 1024 1024"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="ml-0 -mt-8">
                            <ActivityInputs v-model="value[index]">
                            </ActivityInputs>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button @click="addNew"
                        class="bg-orange text-white font-semibold text-md px-12 py-2 shadow-custom-3d rounded-full hover:bg-zinc-800 hover:shadow-none transition-all duration-300 ease-in-out transform hover:scale-105">
                    Add component

                </button>

            </div>


        </div>


    </div>

</template>

<script setup>
import ActivityInputs from "../addableComponents/ActivityInputs.vue";

import {computed, onMounted, onUpdated, ref, watch} from "vue";
import {editMode} from "../../utils/storeHelpers.js";

const hoveredElement = ref(null)
const borderColor = ref('border-zinc-100')

const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const props = defineProps(["modelValue"]);
const value = ref([])

const uniqueYears = computed({
    get() {
        const yearSet = new Set(value.value.map(item => item.year));
        return [...yearSet];
    }
})
const addNew = () => {
    value.value.push({
        title: "", participatedAs: "Participated as", year: "Year"
    });
}
const remove = (index) => {
    value.value.splice(index, 1)
}

const emit = defineEmits(["update:modelValue"])

watch(value, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})

onUpdated(() => {
    value.value = props?.modelValue;
});
onMounted(() => {
    value.value = props?.modelValue;
})

</script>

<style scoped>

</style>
