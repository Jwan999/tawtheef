<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-xl font-semibold text-dark">Courses</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <!--            !value[0].title-->

            <div v-if="!showInputs" v-for="(course,index) in value" :key="index">
                <h1 class="text-sm font-semibold mb-1">{{ course.duration }}</h1>
                <h1 class="text-orange text-xl font-semibold">{{ course.title }}</h1>
                <h1 v-if="course.entity" class="text-zinc-600 text-sm font-semibold mb-4">Provided by: {{
                        course.entity
                    }}</h1>
            </div>
            <div v-else>
                <p class="text-sm text-zinc-700">
                    Not all data filled yet.
                </p>
            </div>
        </div>

        <div v-else class="rounded-md bg-white p-4 pb-6">
            <div class="space-y-6">
                <div v-for="(course,index) in value" :key="index"
                     class="border-0 border-r-[1px] pb-4 border-b-[1px]"
                     :class="hoveredElement===index ? borderColor : 'border-zinc-100'">
                    <div class="w-full">
                        <div class="flex justify-end">
                            <button @mouseover="changeBorderColor(index,'border-dark')"
                                    @mouseleave="changeBorderColor(index,'border-zinc-100')"
                                    @click="remove(index)"
                                    class="flex-none w-auto appearance-none px-3 py-1 rounded-bl-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
                                <svg class="fill-current fill-orange w-4 h-4 " viewBox="0 0 1024 1024"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="pr-8 pt-3 ml-0">
                            <CourseInputs v-model="value[index]"></CourseInputs>
                        </div>
                    </div>

                </div>

            </div>
            <button

                @click="addNew"
                class="flex-none w-auto appearance-none px-3 py-1 rounded-bl-md font-semibold text-start text-orange bg-zinc-100 hover:bg-dark hover:text-white text-sm">
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
        title: "", duration: "", entity: ""
    });
}
const remove = (index) => {
    value.value.splice(index, 1)
}
const emit = defineEmits(["update:modelValue"])

const showInputs = ref(false)
const changeShowInputs = () => {
    if (editMode && value?.value[0]?.title === "") {
        showInputs.value = true;
    } else {
        showInputs.value = false;
    }
}
watch(value, (newValue) => {
    emit('update:modelValue', newValue);
    changeShowInputs()
}, {deep: true})

onMounted(() => {
    value.value = modelValue;
    if (modelValue.length == 0) {
        addNew();
    }
    changeShowInputs()
})


</script>

<style scoped>

</style>
