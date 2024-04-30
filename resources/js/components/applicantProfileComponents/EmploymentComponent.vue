<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Employment</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div>
                <!--period of employment-->
                <div class="flex text-dark space-x-1 text-sm font-semibold">
                    <h1 class="text-orange">2019</h1>
                    <h1>-</h1>
                    <h1 class="text-orange">2022</h1>
                </div>
                <!--position-->
                <div class="flex items-center space-x-2 mb-3">
                    <h1 class="font-semibold text-lg">Graphic Designer</h1>
                    <h1>at</h1>
                    <h1 class="font-semibold italic text-orange text-sm">Employer</h1>
                </div>
                <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                    Aliquam
                    asperiores dicta dolore fuga harum id
                    incidunt, inventore iusto nemo pariatur provident, tempore temporibus voluptatibus?
                    Excepturi
                    nulla
                    odit
                    repellat? Aspernatur, facere!</p>
            </div>
            <div>
                <!--period of employment-->
                <div class="flex text-dark space-x-1 text-sm font-semibold">
                    <h1 class="text-orange">2019</h1>
                    <h1>-</h1>
                    <h1 class="text-orange">2022</h1>
                </div>
                <!--position-->
                <div class="flex items-center space-x-2 mb-3">
                    <h1 class="font-semibold text-lg">Graphic Designer</h1>
                    <h1>at</h1>
                    <h1 class="font-semibold italic text-orange text-sm">Employer</h1>
                </div>
                <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                    Aliquam
                    asperiores dicta dolore fuga harum id
                    incidunt, inventore iusto nemo pariatur provident, tempore temporibus voluptatibus?
                    Excepturi
                    nulla
                    odit
                    repellat? Aspernatur, facere!</p>
            </div>
        </div>

        <div v-else class="rounded-md bg-white p-4 space-y-8">

            <div v-for="(item,index) in employment" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement===index ? borderColor : 'border-slate-100'">
                <div class="w-full">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-slate-100')"
                            @click="componentAction(index)"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        {{ index === 0 ? 'Add component' : 'Remove component' }}

                    </button>
                    <EmploymentInputs v-model="employment[index]"></EmploymentInputs>

                </div>
            </div>

        </div>

    </div>

</template>


<script setup>

import {computed, ref} from "vue";
import store from "../../store/index.js";
import EmploymentInputs from "../addableComponents/EmploymentInputs.vue";

const hoveredElement = ref(null)
const borderColor = ref('border-slate-100')

const {modelValue} = defineProps(["modelValue"]);

const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

    const employment = computed({
        get() {
            return modelValue?.length ? modelValue : [{title: "", employer: "", duration: "", description: ""}];
        },
        set(val) {
            emit('update:modelValue', val)
        }
    });

    const componentAction = (index) => {
        if (index === 0) {
            employment.value.push({
                title: "", employer: "", duration: "", description: ""
            });
        } else if (index > 0) {
            employment.value.splice(index, 1)

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
