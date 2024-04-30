<template>
    <!--preview-->
    <div class="relative grid grid-cols-12 gap-x-6 place-items-start">

        <div v-if="!editMode" class="col-span-8 w-full">

            <span
                :class="workAvailability ? 'bg-orange text-white':'bg-slate-200 text-slate-400'"
                class="font-semibold text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    {{ workAvailability ? 'Available for Work' : 'Not Available for work' }}
            </span>

            <h1 class="text-2xl text-orange font-semibold mt-4 tracking-wider">Martin Garrix</h1>

        </div>
        <div v-else class="col-span-8 w-full space-y-6">
            <div class="flex">
                <span @click="workAvailability = !workAvailability"
                      :class="workAvailability ? 'bg-orange text-white':'bg-slate-200 text-slate-400'"
                      class="cursor-pointer font-semibold hover:bg-orange hover:text-white text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    Available for Work
            </span>
                <span @click="workAvailability = !workAvailability"
                      :class="!workAvailability ? 'bg-orange text-white':'bg-slate-200 text-slate-400'"
                      class="cursor-pointer font-semibold hover:bg-orange hover:text-white text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    Not Available for Work
            </span>
            </div>

            <div class="">
                <span
                    class="block mb-2 text-xs font-medium text-gray-600">Please provide your First and Last name.</span>

                <div class="relative">
                    <input @input="emitDetails" v-model="fullName"
                           placeholder="Full name"
                           class="bg-slate-50 w-full rounded-md text-xl text-dark py-1 tracking-wider font-semibold border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           type="text">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>

                </div>

<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->
            </div>
            <ContactComponent v-model="contactUpdated" @contactUpdated="getValues('contactUpdated',$event)"
                              editMode></ContactComponent>


        </div>
<!--        <div class="col-span-6 w-full flex justify-end items-start">-->
<!--        </div>-->
    </div>

</template>


<script setup>
import ResumeActionButtonsComponent from '../applicantProfileComponents/ResumeActionButtonsComponent.vue'
import {computed, ref} from "vue";
import store from "../../store/index.js";
import ContactComponent from "./ContactComponent.vue";

// const image = ref(null)
const workAvailability = ref(null)
const fullName = ref(null)
const email = ref(null)
const phone = ref(null)
const birthdate = ref(null)
const links = ref(null)
const location = ref(null)

const invalidFields = computed(() => store.state.validationErrors.map(error => error.field));
const updateFieldValue = (field, value) => {
    // Update the corresponding state variable (e.g., fullName.value = value)

    // Remove the field from invalidFields if the value is no longer empty or null
    if (value && value.trim()) {
        const newInvalidFields = invalidFields.value.filter(f => f !== field);
        store.commit('setValidationErrors', newInvalidFields.map(f => ({ field: f, message: '' }))); // Clear message for removed field
    }
};

const editMode = computed({
    get() {
        return store.getters.editMode;
    },
    set() {
        store.dispatch('setEditMode', true)
    }
})

const emit = defineEmits(["detailsUpdated"])

const getValues = (componentTitle, data) => {
    email.value = data.email
    phone.value = data.phone
    links.value = data.links
    birthdate.value = data.birthdate
    location.value = data.location
}
const emitDetails = () => {

    const detailsData = {
        workAvailability: workAvailability.value,
        fullName: fullName.value,

        email: email.value,
        phone: phone.value,
        links: links.value,

        location: location.value,
        birthdate: birthdate.value,
    };
    emit('detailsUpdated', detailsData); // Use emit here
};
</script>


<style scoped>

</style>
