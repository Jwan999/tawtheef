<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Employment</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div v-for="(job,index) in value">
                <!--period of employment-->
                <div class="flex text-dark space-x-1 text-sm font-semibold">
                    <h1 class="text-orange">{{ job.duration[0] }}</h1>
                    <h1>-</h1>
                    <h1 class="text-orange">{{ job.duration[1] }}</h1>
                </div>
                <!--position-->
                <div class="flex items-center space-x-2 mb-3">
                    <h1 class="font-semibold text-lg">{{ job.title }}</h1>
                    <h1>at</h1>
                    <h1 class="font-semibold italic text-orange text-sm">{{ job.employer }}</h1>
                </div>
                <div class="space-y-2">
                    <div v-for="task in job.tasks" class="flex items-center space-x-3">
                        <span class="w-2 h-2 bg-dark rounded-full"></span>
                        <p class="text-sm font-semibold">{{ task }}</p>
                    </div>
                </div>

            </div>

        </div>

        <div v-else class="rounded-md bg-white p-4 space-y-8">

            <div v-for="(item,index) in value" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement===index ? borderColor : 'border-zinc-100'">
                <div class="w-full">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-zinc-100')"
                            @click="remove(index)"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
                        Remove component

                    </button>
                    <EmploymentInputs v-model="value[index]"></EmploymentInputs>
                </div>
            </div>
            <button

                @click="addNew"
                class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
                Add component

            </button>

        </div>

    </div>

</template>


<script setup>

import {onMounted, ref, watch} from "vue";
import EmploymentInputs from "../addableComponents/EmploymentInputs.vue";

const hoveredElement = ref(null)
const borderColor = ref('border-zinc-100')

const {modelValue} = defineProps(["modelValue"]);

import {editMode} from "../../utils/storeHelpers.js";

const value = ref([])
const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

watch(value, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})
const addNew = () => {
    value.value.push({
        title: "", employer: "", duration: [], description: ""
    });
}
onMounted(() => {
    value.value = modelValue;
    if (modelValue.length == 0) {
        addNew();
    }
})

const remove = (index) => {
    value.value.splice(index, 1)
}

const emit = defineEmits(["update:modelValue"])


</script>


<style scoped>

</style>
