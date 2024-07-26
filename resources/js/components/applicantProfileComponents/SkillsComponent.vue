<template>
    <div class="flex items-center space-x-3">
        <h1 class="flex-none text-xl font-semibold text-dark">Personal Skills</h1>
        <hr class="h-px w-full bg-orange border-0 mt-1">
    </div>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-sm">
        <div class="space-y-1">
            <div v-if="!editMode && selectedSkills.length == 0">
                <p class="text-sm text-zinc-700">
                    Not all data filled yet.
                </p>
            </div>

            <div class="flex flex-wrap items-center space-y-2">
                <div class="flex flex-wrap gap-2">
                    <span v-for="skill in selectedSkills"
                          class="inline-block bg-dark text-white px-3 py-1 rounded-full uppercase font-semibold text-sm tracking-wide">
                        {{ skill }}
                    </span>
                </div>
            </div>

        </div>
    </div>
    <div v-else>
        <div class="rounded-md p-4 bg-white text-sm md:text-sm">
            <div class="space-y-1">
                <div class="py-2">
                    <h1 class="block mb-3 text-zinc-500 mt-2">* You can pick five personal-skills only.</h1>
                </div>
                <div class="space-y-3">

                    <div class="">
                        <div v-for="(item,index) in skills" class="me-4 mt-2">
                            <input
                                :id="'checkbox-' + index"
                                type="checkbox"
                                :value="item"
                                :checked="selectedSkills?.includes(item)"
                                @click="selectSkill"
                                class="text-sm w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600"
                            />
                            <label :for="'checkbox-' + index" class="mb-2 text-sm font-medium text-zinc-800">
                                {{ item }}
                            </label>
                        </div>
                    </div>

                    <!--<h1 class="text-red-500 text-sm font-semibold">This field is required.</h1>-->

                </div>


            </div>

        </div>


    </div>


</template>

<script setup>
import {computed, onMounted, onUpdated, ref, watch} from 'vue';
import {editMode, getSelectables} from "../../utils/storeHelpers.js";


const skills = ref([''])
const props = defineProps(["modelValue"]);
const selectedSkills = ref([])

const selectSkill = (event) => {
    const skill = event.target.value;
    const index = selectedSkills.value.indexOf(skill);
    if(selectedSkills.value.length == 5 && index == -1) {
        return event.preventDefault();
    }

    if(index == -1) {
        selectedSkills.value.push(skill)
    } else {
        selectedSkills.value.splice(index,1);
    }
}

onMounted(async () => {
    selectedSkills.value = props?.modelValue;

    axios.get('/api/selectables/skills').then(async res => {
        skills.value = await getSelectables('skills');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);
    });
});

onUpdated(() => {
    selectedSkills.value = props?.modelValue;
});

watch(selectedSkills, (newValue) => {
    emit('update:modelValue', newValue);
}, {deep: true})

const emit = defineEmits(["update:modelValue"])

</script>


<style scoped>

</style>
