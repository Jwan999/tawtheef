<template>
    <div v-if="!editMode && mainSpeciality !== 'Undecided Yet'" class="">
        <div class="flex items-center space-x-3 mb-4">
            <h1 class="flex-none text-lg font-semibold text-dark">Field of interest:
            </h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>
        <div :class="subSpecialities.length && mainSpeciality !== 'Other' ? '' : 'rounded-b-md'"
             class="bg-orange text-start px-2 text-white py-3 rounded-t-md font-semibold">
            {{ mainSpeciality?.title === 'Other' ? subSpecialities.toString() : mainSpeciality?.title }}
        </div>
        <div v-if="mainSpeciality !== 'Other'">
            <div v-if="subSpecialities.length" class="bg-white rounded-b-md px-4 py-2 space-y-1">
                <h1 class="text-orange text-xs font-semibold mb-2">Experienced with the following:</h1>
                <h1 class="font-semibold text-sm" v-for="subSpeciality in subSpecialities">{{ subSpeciality }}.</h1>
            </div>
        </div>

    </div>

    <div :class="!editMode ? 'hidden':'flex'"
         class="flex">
        <!--so the preview mode for this component can't be shown from here becaouse this component is included in the editmode div in the parent comonene tof this component-->
        <div class="flex justify-between space-x-6 w-full">
            <div class="w-full">
                <div v-for="(speciality,index) in specialities" :key="index"
                     class="">
                    <div @click="mainSpeciality = speciality"
                         :class="{'rounded-t-md': index === 0,
                 'rounded-b-md': index === specialities.length - 1,
                 'bg-orange text-white': mainSpeciality == speciality,
                 'bg-white text-dark': mainSpeciality !== speciality}"
                         class="border-b-[1px] border-orange-400 py-3 cursor-pointer focus:bg-orange focus:text-white hover:bg-dark hover:text-white">
                        <h1 class="text-center text-sm font-semibold">{{ speciality.title }}</h1>

                    </div>

                    <div class="w-full bg-white py-4 px-2" v-if="mainSpeciality == speciality">
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


</template>

<script setup>
import {computed, onMounted, ref, watch, watchEffect} from "vue";

import {editMode, getSelectables} from "../../utils/storeHelpers.js";

const mainSpeciality = ref();
const subSpecialities = ref([]);

const specialities = ref([])

onMounted(async () => {
    axios.get('').then(async res => {
        specialities.value = await getSelectables('specialities');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });

});


const emit = defineEmits(["update:modelValue"])

watch([mainSpeciality, subSpecialities], () => {
    emit('update:modelValue', {
        title: mainSpeciality.value.title,
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
