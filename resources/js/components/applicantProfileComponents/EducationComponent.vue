<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Educational Background</h1>

            <h1 class="font-semibold">{{ modelValue.institute }}</h1>
            <h1 class="text-dark font-semibold">{{ modelValue.degree }}
            </h1>
<!--            <h1 class="text-orange font-semibold">{{ modelValue.duration[0] + '-' + modelValue.duration[1] }}</h1>-->
        </div>

    </div>

    <div v-else class="rounded-md p-4 bg-white text-sm md:text-xs">
        <h1 class="text-lg font-semibold text-dark pb-4">Educational Background</h1>

        <div class="relative w-full">
            <div class="space-y-3">
                <div v-for="(item,index) in value"
                     :key="index"
                     class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                     :class="hoveredElement===index ? borderColor : 'border-zinc-100'">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-zinc-100')"
                            @click="remove(index)"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
                        Remove component
                    </button>
                    <EducationInputs v-model="value[index]"></EducationInputs>
                </div>
                <button

                    @click="addNew"
                    class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
                    Add component

                </button>
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
    value.value.push({
        degree: "", institute: "", duration: ['Start year','End year']
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
