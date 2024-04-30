<template>
    <div class="w-full space-y-4">

        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Events and Activities</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div>
                <h1 class="text-orange font-semibold text-lg mb-4">2019</h1>
                <ul class="text-sm">
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Speaker</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Attendee</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Volunteer</h1>
                    </li>
                </ul>
            </div>
            <div>
                <h1 class="text-orange font-semibold text-lg mb-4">2019</h1>
                <ul class="text-sm">
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Speaker</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Attendee</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Volunteer</h1>
                    </li>
                </ul>
            </div>

        </div>


        <div v-else class="rounded-md bg-white space-y-4 p-4">
            <div v-for="(item,index) in list" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement===index ? borderColor : 'border-slate-100'">
                <div class="w-full">

                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-slate-100')"
                            @click="componentAction(index)"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        {{ index === 0 ? 'Add component' : 'Remove component' }}

                    </button>
                    <ActivityInputs v-model="list[index]">
                    </ActivityInputs>
                </div>
            </div>
        </div>


    </div>

</template>

<script setup>
import ActivityInputs from "../addableComponents/ActivityInputs.vue";

import {computed, ref} from "vue";
import store from "../../store/index.js";

const hoveredElement = ref(null)
const borderColor = ref('border-slate-100')

const {modelValue} = defineProps(["modelValue"]);

const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const list = computed({
    get() {
        return modelValue?.length ? modelValue : [{title: "", participatedAs: "", year: ""}];
    },
    set(val) {
        emit('update:modelValue', val)
    }
});

const componentAction = (index) => {
    if (index === 0) {
        list.value.push({
            title: "",
            participatedAs: "",
            year: ""
        });
    } else if (index > 0) {
        list.value.splice(index, 1)

    }
}

const emit = defineEmits(["update:modelValue"])

const editMode = computed({
    get() {
        return store.getters.editMode;
    },
    set() {
        store.dispatch('setEditMode', true)
    }
})
</script>

<style scoped>

</style>
