<template>
    <div class="flex">
        <button @click="toggleEditMode"
                class=" appearance-none text-xs rounded-bl-md px-3 py-2 font-semibold text-white bg-orange hover:bg-dark">

            <svg v-if="!editMode" class="fill-white h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="m21.783 3.766-1.548-1.548c-1.088-1.088-2.858-1.088-3.946 0l-13.939 13.937c-.092.092-.151.211-.169.339l-.774 5.42c-.027.187.036.375.169.509.113.113.266.176.424.176.028 0 .057-.002.085-.006l5.42-.774c.128-.018.248-.078.339-.169l13.938-13.938c1.088-1.088 1.088-2.858 0-3.946zm-14.645 16.894-4.431.633.633-4.431 11.364-11.363 3.797 3.797-11.364 11.364zm13.796-13.796-1.584 1.584-3.797-3.797 1.584-1.584c.62-.62 1.629-.62 2.249 0l1.548 1.548c.62.62.62 1.629 0 2.249z"/>
            </svg>
            <span v-else>Save</span>
        </button>
        <button v-if="editMode" @click="$emit('publishResume')"
                class="appearance-none text-xs px-3 py-2 font-semibold text-dark hover:text-white bg-white hover:bg-dark">
            <span>Publish</span>
        </button>
        <!--        todo only show when logged in and without the edit more-->
        <button
            class="appearance-none text-xs rounded-tr-md px-3 py-2 font-semibold text-dark hover:text-white bg-white hover:bg-dark">
            <span>Download PDF</span>
        </button>
    </div>

</template>

<script setup>

import {computed} from "vue";
import store from '../../store/index.js'; // Adjust path as needed

const editMode = computed(() => store.getters.editMode)

const emit = defineEmits(["publishResume"])

const handleButtonClick = () => {
    console.log('Publish button clicked in ApplicantDetailsComponent');
    emit('publishResume');
}
const toggleEditMode = () => {
    console.log(store.getters.editMode)
    store.dispatch('setEditMode', !editMode.value); // Use setEditMode
};
</script>

<style scoped>

</style>
