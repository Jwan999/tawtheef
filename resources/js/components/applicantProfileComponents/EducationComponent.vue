<template>
    <div class="flex items-center space-x-3">
        <h1 class="flex-none text-xl font-semibold text-dark">Educational Background</h1>
        <hr class="h-px w-full bg-orange border-0 mt-1">
    </div>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-sm mt-4">


        <div v-if="!modelValue[0]?.degree">
            <p class="text-sm text-zinc-700">
                Data not filled yet.
            </p>
        </div>
        <div v-else>
            <div class="mt-6" v-for="item in modelValue">
                <h1 v-if="item.duration[0] !== 'Start Year' && item.duration[1] !== 'End Year'"
                    class="font-semibold text-orange">{{ item.duration[0] + ' - ' + item.duration[1] }}</h1>
                <h1 class="font-semibold text-lg capitalize">{{ item.institute }}</h1>
                <h1 class="text-dark font-semibold italic capitalize">{{ item.degree }}</h1>

            </div>

        </div>


    </div>

    <div v-else class="rounded-md pb-6 bg-white text-sm md:text-sm mt-4">
        <div class="relative w-full space-y-3">
            <div class="w-full" v-for="(item,index) in value"
                 :key="index">
                <div class="flex justify-end">
                    <button @click="remove(index)"
                            class="appearance-none px-2 text-sm font-semibold group tracking-wide text-zinc-700 hover:text-white py-2 hover:bg-dark border-[1px] border-zinc-700">
                        <svg class="fill-current group-hover:fill-white fill-zinc-700 w-4 h-4 " viewBox="0 0 1024 1024"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                        </svg>
                    </button>
                </div>

                <EducationInputs v-model="value[index]"></EducationInputs>
            </div>


        </div>
        <div class="flex justify-end mt-6">
            <button @click="addNew"
                    class="appearance-none capitalize w-full text-sm font-semibold tracking-wide text-zinc-700 hover:text-white py-3 hover:bg-dark border-[1px] border-zinc-700">
                Add component

            </button>

        </div>


    </div>

</template>

<script setup>
import EducationInputs from "../addableComponents/EducationInputs.vue";
import {computed, onMounted, onUpdated, ref, watch} from 'vue';
import {editMode} from "../../utils/storeHelpers.js";


const hoveredElement = ref(null)
const borderColor = ref('border-zinc-100')
const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const props = defineProps(["modelValue"])
const value = ref([])
onUpdated(() => {
    value.value = props?.modelValue;
});
onMounted(() => {
    value.value = props?.modelValue;
})
const addNew = () => {
    value.value.push({
        degree: "", institute: "", duration: ['Start Year', 'End Year']
    });
};
const remove = (index) => {
    value.value.splice(index, 1)
}

const emit = defineEmits(["update:modelValue"])

watch(value, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})


</script>

<style scoped>

</style>
