<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Courses</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div v-for="(course,index) in courses" :key="index">
                <h1 class="text-sm font-semibold mb-1">{{ course.duration }}</h1>
                <h1 class="text-orange text-lg font-semibold mb-4">{{ course.title }}</h1>
                <p class="text-sm">{{
                        course.description
                    }}</p>
            </div>

        </div>

        <div v-else class="rounded-md bg-white p-4 space-y-4">

            <div v-for="(course,index) in courses" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement===index ? borderColor : 'border-slate-100'">
                <div class="w-full">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-slate-100')"
                            @click="coursesActions(index === 0 ? 'add':'remove',index)"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        {{ index === 0 ? 'Add component' : 'Remove component' }}

                    </button>
                    <CourseInputs v-model="courses[index]"></CourseInputs>

                </div>
            </div>


        </div>
    </div>

</template>


<script setup>
import {computed, ref} from "vue";
import CourseInputs from "../addableComponents/CourseInputs.vue";
import store from "../../store/index.js";

const hoveredElement = ref(null)
const borderColor = ref('border-slate-100')
const courses = ref([{
    title: '',
    duration: '',
    description: '',
}])
const {modelValue} = defineProps(["modelValue"]);
const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const coursesActions = (action, index) => {
    borderColor.value = 'border-dark'
    if (action == 'add') {
        courses.value.push({
            title: "",
            duration: "",
            description: ""
        });
    } else if (action === 'remove') {
        courses.value.splice(index, 1)
    }

}
const emit = defineEmits(["courseUpdated"])

const course = computed({
    get() {
        return modelValue;
    },
    set(val) {
        emit('update:modelValue', val)
    }
});
console.log(modelValue)

const editMode = computed({
    get() {
        return store.getters.editMode;
    },

})

</script>

<style scoped>

</style>
