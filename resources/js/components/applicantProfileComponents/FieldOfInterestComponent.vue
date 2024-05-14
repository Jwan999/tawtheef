<template>
    <div v-if="!editMode && !specializations.includes('Undecided Yet')" class="">
        <div class="flex items-center space-x-3 mb-4">
            <h1 class="flex-none text-lg font-semibold text-dark">Specializations</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>
        <div :class="subSpecialities.length == 0 ?'rounded':'rounded-t-md'"
             class="bg-orange text-start px-2 text-white py-3 font-semibold">
            <h1 v-for="(speciality,index) in specializations">
                {{ specializations.includes('Other') ? subSpecialities.toString() : speciality }}.
            </h1>
        </div>
        <div>
            <div v-if="subSpecialities.length" class="bg-white rounded-b-md px-4 py-2 space-y-1">
                <h1 class="text-orange text-xs font-semibold mb-2">Experienced with the following:</h1>
                <h1 class="font-semibold text-sm" v-for="subSpeciality in subSpecialities">{{ subSpeciality }}.</h1>
            </div>
        </div>

    </div>
    <div :class="!editMode ? 'hidden':''">
        <div class="flex items-center space-x-3 mb-5">
            <h1 class="flex-none text-lg font-semibold text-dark">Specializations</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>
        <div class="flex">

            <div class="flex justify-between space-x-6 w-full">
                <div class="w-full">
                    <div v-for="(speciality,index) in specialities" :key="index"
                         class="">
                        <div @click="addSpecializations(speciality.title,index)"
                             :class="{'rounded-t-md': index === 0,
                 'rounded-b-md': index === specialities.length - 1,
                 'bg-orange text-white': specializations.includes(speciality.title),
                 'bg-white text-dark': !specializations.includes(speciality.title)}"
                             class="border-b-[1px] border-orange-400 py-3 cursor-pointer focus:bg-orange focus:text-white hover:bg-dark hover:text-white">
                            <h1 class="text-center text-sm font-semibold">{{ speciality.title }}</h1>

                        </div>

                        <div class="w-full bg-white py-4 px-2" v-if="specializations.includes(speciality.title)">
                            <div class="flex items-start space-y-2 me-4"
                                 v-for="(subSpeciality, index) in speciality.children"

                                 :key="index">
                                <div>
                                    <input v-model="subSpecialities"
                                           type="checkbox"
                                           :value="subSpeciality"
                                           class="w-4 h-4 text-orange bg-white border-zinc-400 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-2 dark:bg-zinc-700 dark:border-zinc-600">
                                    <label :for="`subSpeciality-${index}`"
                                           class="ms-2 text-sm font-medium text-zinc-900 dark:text-zinc-300">
                                        {{ subSpeciality }}.
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="mt-2">
            <h1 class="text-xs font-semibold text-zinc-600">* You can pick two main specializations only.</h1>
        </div>
    </div>


</template>

<script setup>
import {computed, onMounted, ref, watch, watchEffect} from "vue";

import {editMode, getSelectables} from "../../utils/storeHelpers.js";

const specializations = ref([]);
const subSpecialities = ref([]);

const specialities = ref([])

onMounted(async () => {
    axios.get('').then(async res => {
        specialities.value = await getSelectables('specialities');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });

});
const addSpecializations = (speciality, index) => {

    if (!specializations.value.includes(speciality)) {
        specializations.value.push(speciality)
    } else {
        specializations.value.splice(index, 1)
    }
    if (specializations.value.length > 2) {
        specializations.value.splice(0, 1)

    }


}
const {modelValue} = defineProps(["modelValue"]);

const emit = defineEmits(["update:modelValue"])

watch([specializations, subSpecialities], () => {
    emit('update:modelValue', {
        specializations: specializations.value,
        children: subSpecialities.value
    })
}, {deep: true})

onMounted(() => {
});

// const roundness
</script>

<style scoped>
.hidden {
    display: none;
}
</style>
