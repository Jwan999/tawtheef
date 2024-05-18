<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">
        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Skills</h1>
            <div v-if="!editMode && selectedSkills.length == 0">
                <p class="text-sm text-zinc-700">
                    Not all data filled yet.
                </p>
            </div>

            <div class="flex flex-wrap items-center space-y-2">
                <div class="flex flex-wrap gap-2">
                    <span v-for="skill in selectedSkills"
                          class="inline-block bg-dark text-white px-3 py-1 rounded-full uppercase font-semibold text-xs">
                        {{ skill }}
                    </span>
                </div>
            </div>

        </div>
    </div>
    <div v-else>
        <div class="rounded-md p-4 bg-white text-sm md:text-xs">
            <div class="space-y-1">
                <h1 class="text-lg font-semibold text-dark pb-4">Skills</h1>
                <div class="space-y-3">

                    <div class="">
                        <div v-for="(item,index) in skills" class="me-4 mt-2">
                            <input :disabled="selectedSkills.length>4"
                                   :id="'checkbox-' + index"
                                   type="checkbox" :value="item" v-model="selectedSkills"
                                   class="text-xs w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600">
                            <label :for="'checkbox-' + index" class="mb-2 text-xs font-medium text-zinc-800">
                                {{ item }}
                            </label>
                        </div>
                    </div>

                    <!--                <h1 class="text-red-500 text-xs font-semibold">This field is required.</h1>-->


                </div>


            </div>

        </div>

        <div class="mt-2">
            <h1 class="text-xs font-semibold text-zinc-600">* You can pick five soft-skills only.</h1>
        </div>
    </div>


</template>

<script setup>
import {onMounted, ref, watch} from 'vue';
import {editMode, getSelectables} from "../../utils/storeHelpers.js";

const skills = ref([''])


onMounted(async () => {
    axios.get('/api/selectables/skills').then(async res => {
        skills.value = await getSelectables('skills');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });
    selectedSkills.value = ['Teamwork']
//     selectedSkills.value = modelValue;

});

const {modelValue} = defineProps(["modelValue"]);
const selectedSkills = ref([])


watch(selectedSkills, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})

const emit = defineEmits(["update:modelValue"])


</script>


<style scoped>

</style>
