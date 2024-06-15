<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-xl font-semibold text-dark pb-4">Languages</h1>

            <div v-if="!languages[0]?.item">
                <p class="text-sm text-zinc-700">
                    Not all data filled yet.
                </p>
            </div>

            <div v-else v-for="(language,index) in languages" :key="index" class="flex items-center justify-between space-x-2">


                <h1 class="font-semibold text-base">{{ language.item }}</h1>

                <div class="flex items-center space-x-2">
                    <div v-if="language.rating"
                         v-for="(value,index) in 5"
                         :key="index"
                         :class="language.rating >= value ? 'bg-orange':'bg-zinc-100'"
                         class="rounded-full w-8 h-3">
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div v-else class="rounded-md py-4 bg-white text-sm md:text-sm">

        <div class="px-4 text-sm md:text-sm">
            <h1 class="text-xl font-semibold text-dark pb-3">Languages</h1>

            <div class="relative w-full">
                <div class="space-y-4">
                    <div v-for="(item,index) in languages"
                         :key="index"
                         class="border-0 border-r-[1px] border-b-[1px]"
                         :class="hoveredElement===index ? borderColor : 'border-zinc-100'">
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

                        <ratingComponent v-model="languages[index]"></ratingComponent>
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
import RatingComponent from './RatingComponent.vue';
import {onMounted, onUpdated, ref, watch} from 'vue';
import {editMode} from "../../utils/storeHelpers.js";

const hoveredElement = ref(null)
const borderColor = ref('border-zinc-100')
const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const props = defineProps(["modelValue"]);
const languages = ref([])

onUpdated(() => {
        languages.value = props.modelValue
});
onMounted(() => {
    languages.value = props.modelValue
})

const addNew = () => {
    languages.value.push({
        item: "", rating: ""
    });
}
const remove = (index) => {
    languages.value.splice(index, 1)
}

const emit = defineEmits(["update:modelValue"])

watch(languages, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})


</script>


<style scoped>

</style>
