<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Courses</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div v-for="(course,index) in value" :key="index">
                <h1 class="text-sm font-semibold mb-1">{{ course.duration }}</h1>
                <h1 class="text-orange text-lg font-semibold mb-4">{{ course.title }}</h1>
                <p class="text-sm">{{
                        course.description
                    }}</p>
            </div>

        </div>

        <div v-else class="rounded-md bg-white p-4 space-y-4">

            <div v-for="(course,index) in value" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement===index ? borderColor : 'border-zinc-100'">
                <div class="w-full">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-zinc-100')"
                            @click="remove(index)"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
                        Remove component

                    </button>
                    <CourseInputs v-model="value[index]"></CourseInputs>

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
import CourseInputs from "../addableComponents/CourseInputs.vue";
import {editMode} from "../../utils/storeHelpers.js";

const hoveredElement = ref(null)
const borderColor = ref('border-zinc-100')

const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const value = ref([])
const {modelValue} = defineProps(["modelValue"]);

const addNew = () => {
    value.value.push({
        title: "", duration: "", description: ""
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
