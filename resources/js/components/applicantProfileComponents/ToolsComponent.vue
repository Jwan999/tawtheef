<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Tools</h1>
            <div v-for="(tool,index) in value" :key="index" class="flex items-center justify-between">
                <h1 class="font-semibold">{{ tool.item }}</h1>


                <div class="flex items-center space-x-2">
                    <div
                        v-for="(value,index) in 5"
                        :key="index"
                        :class="tool.rating >= value ? 'bg-orange':'bg-zinc-100'"
                        class="rounded-full w-8 h-3">
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div v-else class="rounded-md py-4 bg-white text-sm md:text-xs">

        <div class="space-y-3 px-4 text-sm md:text-xs">
            <h1 class="text-lg font-semibold text-dark pb-3">Tools</h1>

            <div class="relative w-full">
                <div class="space-y-3">
                    <div v-for="(item,index) in value"
                         :key="index"
                         class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                         :class="hoveredElement===index ? borderColor : 'border-slate-100'">
                        <button @mouseover="changeBorderColor(index,'border-dark')"
                                @mouseleave="changeBorderColor(index,'border-slate-100')"
                                @click="remove"
                                class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                           Remove component
                        </button>

                        <ratingComponent placeholder-label="Tool" v-model="value[index]"></ratingComponent>
                    </div>
                    <button
                        @click="addNew"
                        class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        Add component

                    </button>
                </div>

            </div>


        </div>


    </div>

</template>
<script setup>
import RatingComponent from './RatingComponent.vue';
import {onMounted, ref, watch} from 'vue';
import {editMode} from "../../utils/storeHelpers.js";


const hoveredElement = ref(null)
const borderColor = ref('border-slate-100')
const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const {modelValue} = defineProps(["modelValue"]);
const value = ref([])

const addNew = () => {
    value.value.push({
        item: "", rating: ""
    });
}
const remove = (index) => {
    value.value.splice(index, 1)
}

onMounted(() => {
    value.value = modelValue;
    if (modelValue.length == 0) {
        addNew();
    }
})

watch(value, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})

const emit = defineEmits(["update:modelValue"])

</script>

<style scoped>

</style>
