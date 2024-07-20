<template>
    <div v-if="!editMode && !specializations?.includes('Undecided Yet')" class="">
        <div class="flex items-center space-x-3 mb-4">
            <h1 class="flex-none text-xl font-semibold text-dark">Specializations</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>
        <div :class="specializations ?'rounded':'rounded-t-md'"
             class="bg-orange text-lg text-start px-2 text-white py-3 font-bold tracking-wide">
            <div v-if="!specializations.length">
                <p class="text-sm">
                    Not all data filled yet.
                </p>
            </div>
            <h1 v-else v-for="(item,index) in specializations">
                {{ item === 'Other' ? item.toString() : item }}.
            </h1>

        </div>
        <div>
            <div v-if="children.length" class="bg-white rounded-b-md px-4 py-2 space-y-1">
                <h1 class="text-orange text-sm font-semibold mb-4">Experienced with the following:</h1>
                <h1 class="font-semibold text-base" v-for="subSpeciality in children">{{
                        subSpeciality
                    }}.</h1>
            </div>
        </div>

    </div>
    <div v-else :class="!editMode ? 'hidden':''">
        <div class="flex items-center space-x-3 mb-5">
            <h1 class="flex-none text-xl font-semibold text-dark">Specializations</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>
        <div class="mt-2 mb-1">
            <h1 class="text-sm font-semibold text-zinc-600">* You can pick two main specializations only.</h1>
        </div>
        <div class="flex">

            <div class="flex justify-between space-x-6 w-full">
                <div class="w-full">
                    <div v-for="(item,index) in specialities" :key="index"
                         class="">
                        <div @click="toggleSpecialization(item.title)"
                             :class="{'rounded-t-md': index === 0,
                 'rounded-b-md': index === specialities.length - 1,
                 'bg-orange text-white': specializations?.includes(item.title),
                 'bg-white text-dark': !specializations?.includes(item.title)}"
                             class="border-b-[1px] border-orange-400 py-3 cursor-pointer focus:bg-orange focus:text-white hover:bg-dark hover:text-white">
                            <h1 class="text-center text-lg font-semibold">{{ item.title }}</h1>

                        </div>


                        <div class="w-full bg-white py-4 px-2" v-if="specializations?.includes(item.title)">
                            <div class="flex items-start space-y-2 me-4" v-for="(subSpeciality, index) in item.children"
                                 :key="index">
                                <div>
                                    <input
                                        :id="`subSpeciality-${index}`"
                                        type="checkbox"
                                        :value="subSpeciality"
                                        @change="toggleSubSpeciality(subSpeciality)"
                                        class="w-4 h-4 text-orange bg-white border-zinc-400 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-2 dark:bg-zinc-700 dark:border-zinc-600"
                                    />
                                    <label :for="`subSpeciality-${index}`"
                                           class="ms-2 text-base font-medium text-zinc-900 dark:text-zinc-300">
                                        {{ subSpeciality }}.
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script setup>
import {computed, onMounted, onUpdated, ref, watch, watchEffect} from "vue";

import {editMode, getSelectables} from "../../utils/storeHelpers.js";

const props = defineProps(["modelValue"])

const specializations = ref([])
const children = ref([])

onUpdated(() => {
    specializations.value = props?.modelValue?.specializations
    children.value = props?.modelValue?.children
});

const specialities = ref([])
onMounted(async () => {
    specializations.value = props?.modelValue?.specializations
    children.value = props?.modelValue?.children

    specialities.value = await getSelectables('specialities');

});

const toggleSubSpeciality = (subSpeciality) => {
    const index = children.value.indexOf(subSpeciality);
    if (index === -1) {
        children.value.push(subSpeciality);
    } else {
        children.value.splice(index, 1);
    }
};

const toggleSpecialization = (name) => {
    if (!specializations?.value?.includes(name)) {
        if (specializations?.value?.length >= 2) {
            specializations?.value?.splice(0, 1);
        }
        specializations?.value?.push(name);
    } else {
        const index = specializations?.value?.indexOf(name);
        if (index !== -1) {
            specializations?.value?.splice(index, 1);
        }
    }
};

const emit = defineEmits(["update:modelValue"])

watch([specializations.value, children.value], () => {
    emit('update:modelValue', {
        specializations: specializations.value,
        children: children.value
    });
}, {deep: true});


</script>

