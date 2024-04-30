<template>
    <div v-if="!editMode && chosenMainSpeciality !== 'Undecided Yet'" class="">
        <div class="flex items-center space-x-3 mb-4">
            <h1 class="flex-none text-lg font-semibold text-dark">Field of interest:
            </h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>
        <div :class="chosenSubSpeciality.length && chosenMainSpeciality !== 'Other' ? '' : 'rounded-b-md'"
             class="bg-orange text-start px-2 text-white py-3 rounded-t-md font-semibold">
            {{ chosenMainSpeciality === 'Other' ? chosenSubSpeciality.toString() : chosenMainSpeciality }}
        </div>
        <div v-if="chosenMainSpeciality !== 'Other'">
            <div v-if="chosenSubSpeciality.length" class="bg-white rounded-b-md px-4 py-2 space-y-1">
                <h1 class="text-orange text-xs font-semibold mb-2">Experienced with the following:</h1>
                <h1 class="font-semibold text-sm" v-for="subSpeciality in chosenSubSpeciality">{{ subSpeciality }}.</h1>
            </div>
        </div>

    </div>
    <div v-else :class="chosenMainSpeciality === 'Undecided Yet' && !editMode ? 'hidden':'flex'"
         class="flex">
        <!--so the preview mode for this component can't be shown from here becaouse this component is included in the editmode div in the parent comonene tof this component-->
        <div class="flex justify-between space-x-6 w-full">
            <div class="w-full">
                <div v-for="(speciality,index) in specialities" :key="index"
                     class="">
                    <div @click="pickMainSpeciality(speciality)"
                         :class="{'rounded-t-md': index === 0,
                 'rounded-b-md': index === specialities.length - 1,
                 'bg-orange text-white': chosenMainSpeciality === speciality,
                 'bg-white text-dark': chosenMainSpeciality !== speciality}"
                         class="border-b-[1px] border-orange-400 py-3 cursor-pointer focus:bg-orange focus:text-white hover:bg-dark hover:text-white">
                        <h1 class="text-center text-sm font-semibold">{{ speciality }}</h1>

                    </div>

                    <div class="" v-if="chosenMainSpeciality === speciality">
                        <div class="w-full bg-white py-4 px-2">
                            <div class="flex items-start space-y-2  me-4 "
                                 v-for="(subSpeciality, index) in shownSubSpecialities"
                                 :key="index">
                                <div>
                                    <input v-if="chosenMainSpeciality !== 'Undecided Yet'"
                                           @change="pickSubSpeciality(subSpeciality)"
                                           type="checkbox"
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


    </div>


</template>

<script setup>
import {computed, onMounted, ref, watch, watchEffect} from "vue";
import store from "../../store/index.js";

const chosenMainSpeciality = ref('Creative & Design')
const chosenSubSpeciality = ref([])
const shownSubSpecialities = ref([])
const specialities = ref(['Creative & Design', 'Development', 'Business & Management', 'Writing & Editing', 'Science & Engineering', 'Other', 'Undecided Yet'])
const subSpecialities = ref({
    design: ['Graphic Design', 'User Experience (UX) Design', 'User Interface (UI) Design', 'Visual Design', 'Motion Graphics', 'Interaction Design', 'Product Design', 'Content Design', 'Fashion Design', 'Interior Design', 'Architecture'],
    development: ['Front-End Development', 'Back-End Development', 'Full-Stack Development', 'Web Development', 'Mobile Development', 'Software Development', 'Data Science', 'Machine Learning', 'Artificial Intelligence', 'DevOps', 'Cloud Computing'],
    business: ['Marketing', 'Sales', 'Project Management', 'Operations Management'],
    writing: ['Copywriting', 'Content Writing', 'Technical Writing', 'Editing', 'Proofreading'],
    science: ['Biology', 'Chemistry', 'Engineering (various specializations)', 'Mathematics', 'Statistics', 'Physics'],
    other: ['Education & Training', 'Healthcare', 'Law', 'Social Work', 'Communications', 'Public Relations', 'Customer Service', 'Translation & Interpretation'],
    yet: ['This will be hidden on your resume.']
});
const pickMainSpeciality = (speciality) => {
    chosenSubSpeciality.value = []
    chosenMainSpeciality.value = speciality
    getSubSpecialities()
    emitSpeciality()
}
const pickSubSpeciality = (subSpeciality) => {
    if (!chosenSubSpeciality.value.includes(subSpeciality)) {
        chosenSubSpeciality.value.push(subSpeciality)
        emitSpeciality()
    }
    // Perform actions based on the changes
}
const getSubSpecialities = () => {
    if (chosenMainSpeciality.value === 'Creative & Design') return shownSubSpecialities.value = subSpecialities.value.design;
    if (chosenMainSpeciality.value === 'Development') return shownSubSpecialities.value = subSpecialities.value.development;
    if (chosenMainSpeciality.value === 'Business & Management') return shownSubSpecialities.value = subSpecialities.value.business;
    if (chosenMainSpeciality.value === 'Writing & Editing') return shownSubSpecialities.value = subSpecialities.value.writing;
    if (chosenMainSpeciality.value === 'Science & Engineering') return shownSubSpecialities.value = subSpecialities.value.science;
    if (chosenMainSpeciality.value === 'Other') return shownSubSpecialities.value = subSpecialities.value.other;
    if (chosenMainSpeciality.value === 'Undecided Yet') return shownSubSpecialities.value = subSpecialities.value.yet;
    return {}; // Return empty object if no chosen Speciality
}

const emit = defineEmits(["specialityUpdated"])
const emitSpeciality = () => {

    const specialityUpdated = {
        speciality: chosenMainSpeciality.value,
        subSpeciality: chosenSubSpeciality.value
    };
    emit('specialityUpdated', specialityUpdated); // Use emit here
};

onMounted(() => {
    getSubSpecialities()
});
const editMode = computed({
    get() {
        return store.getters.editMode;
    },
})

// const roundness
</script>

<style scoped>
.hidden {
    display: none;
}
</style>
