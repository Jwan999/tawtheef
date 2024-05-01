<template>
    <div class="w-full space-y-4">

        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Events and Activities</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div v-for="(year, index) in uniqueYears" :key="index">
                <h1 class="text-orange font-semibold text-lg mb-4">{{ year }}</h1>
                <ul class="text-sm">
                    <li v-for="event in value.filter(item => item.year === year)"
                        class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">{{ event.title }}</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">{{ event.participatedAs }}</h1>
                    </li>
                </ul>
            </div>

        </div>


        <div v-else class="rounded-md bg-white space-y-4 p-4">
            <div v-for="(item,index) in value" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement===index ? borderColor : 'border-slate-100'">
                <div class="w-full">

                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-slate-100')"
                            @click="remove"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        {{ index === 0 ? 'Add component' : 'Remove component' }}

                    </button>
                    <ActivityInputs v-model="value[index]">
                    </ActivityInputs>
                </div>
            </div>
            <button

                @click="addNew"
                class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                Add component

            </button>
        </div>


    </div>

</template>

<script setup>
import ActivityInputs from "../addableComponents/ActivityInputs.vue";

import {computed, onMounted, ref, watch} from "vue";
import {editMode} from "../../utils/storeHelpers.js";

const hoveredElement = ref(null)
const borderColor = ref('border-slate-100')

const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const {modelValue} = defineProps(["modelValue"]);
const value = ref([])


const uniqueYears = computed({
    get() {
        const yearSet = new Set(value.value.map(item => item.year));
        return [...yearSet];
    }
})
const addNew = () => {
    value.value.push({
        title: "", participatedAs: "", year: ""
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
