<template>
    <div class="flex items-center space-x-3">
        <h1 class="flex-none text-xl font-semibold text-dark">Spoken Languages</h1>
        <hr class="h-px w-full bg-orange border-0 mt-1">
    </div>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">
        <div class="space-y-1">
            <div v-if="!languages[0]?.item">
                <p class="text-sm text-zinc-700">
                    Data not filled yet.
                </p>
            </div>
            <div v-else v-for="(language,index) in languages" :key="index" class="flex items-center justify-between space-x-2">
                <h1 class="font-semibold text-base">{{ language.item }}</h1>
                <span class="text-sm font-medium text-orange">{{ getCompetencyLevel(language.rating) }}</span>
            </div>
        </div>
    </div>

    <div v-else class="rounded-md py-4 bg-white text-sm md:text-sm">
        <div class="text-sm md:text-sm">
            <div class="relative w-full">
                <div class="space-y-4">
                    <div v-for="(item,index) in languages" :key="index">
                        <div class="flex justify-end">
                            <button @click="remove(index)"
                                    class="appearance-none rounded-full bg-white px-1 text-sm font-semibold group tracking-wide text-orange hover:text-white py-1 hover:bg-dark border-[1px] border-orange hover:border-zinc-700">
                                <svg class="fill-current group-hover:fill-white fill-orange w-4 h-4 " viewBox="0 0 1024 1024"
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
import RatingComponent from './RatingComponent.vue';
import { onMounted, onUpdated, ref, watch } from 'vue';
import { editMode } from "../../utils/storeHelpers.js";

const props = defineProps(["modelValue"]);
const languages = ref([]);

onUpdated(() => {
    languages.value = props.modelValue;
});

onMounted(() => {
    languages.value = props.modelValue;
});

const addNew = () => {
    languages.value.push({
        item: "", rating: ""
    });
};

const remove = (index) => {
    languages.value.splice(index, 1);
};

const emit = defineEmits(["update:modelValue"]);

watch(languages, (newValue) => {
    emit('update:modelValue', newValue);
}, { deep: true });

const getCompetencyLevel = (rating) => {
    const numericRating = Number(rating);
    switch(numericRating) {
        case 1: return 'Basic';
        case 2: return 'Intermediate';
        case 3: return 'Good';
        case 4: return 'Very Good';
        case 5: return 'Excellent';
        default: return 'N/A';
    }
};
</script>

<style scoped>
/* Add any scoped styles here if needed */
</style>
