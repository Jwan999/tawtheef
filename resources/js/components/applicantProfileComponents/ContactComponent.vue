<template>
    <!-- title -->
    <div class="flex items-center space-x-3 mb-4">
        <h1 class="flex-none text-xl font-semibold text-dark">Personal Information</h1>
        <hr class="h-px w-full bg-orange border-0 mt-1">
    </div>

    <!-- preview -->
    <div v-if="!editMode" class="bg-white p-4 rounded-md">
        <div v-if="city == 'Choose your city...'" class="">
            <h1 class="flex-none font-semibold mb-3 text-zinc-500">Contacts & other info</h1>
            <p class="text-sm text-zinc-700">
                Not all data filled yet.
            </p>
        </div>

        <div v-else class="md:space-y-6 space-y-10">
            <!-- name and work availability -->
            <div class="w-full">
                <div class="flex justify-end w-full">
                    <span
                        :class="workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                        class="text-base px-2.5 py-0.5 rounded-full dark:bg-orange-900 dark:text-orange-300">
                        {{ workAvailability ? 'Available for Work' : 'Not currently looking' }}
                    </span>
                </div>
                <div class="w-full">
                    <h1 class="text-2xl  text-orange font-semibold tracking-wider capitalize">{{ fullName }}</h1>
                </div>
            </div>

            <div class="flex md:flex-nowrap flex-wrap md:space-y-0 space-y-10">
                <!-- Contact Info -->
                <div class="space-y-4 w-full">
                    <h2 class="text-lg font-semibold text-zinc-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact Information
                    </h2>
                    <div class="ml-7 space-y-2">
                        <p class="text-zinc-700">{{ email }}</p>
                        <p v-if="isChecked" class="text-zinc-700">{{ phone }}</p>
                    </div>
                </div>

                <!-- Residence -->
                <div v-if="city" class="space-y-4 w-full">
                    <h2 class="text-lg font-semibold text-zinc-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Residence
                    </h2>
                    <p class="ml-7 text-zinc-700">{{ city }}<span v-if="city=='Baghdad'">, {{ zone }}</span></p>
                </div>

                <!-- Date of Birth -->
                <div v-if="birthdate" class="space-y-4 w-full">
                    <h2 class="text-lg font-semibold text-zinc-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Date of Birth
                    </h2>
                    <p class="ml-7 text-zinc-700">{{ birthdate }}</p>
                </div>

                <!-- Gender -->
                <div v-if="gender !== 'Gender'" class="space-y-4 w-full">
                    <h2 class="text-lg font-semibold text-zinc-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Gender
                    </h2>
                    <p class="ml-7 text-zinc-700">{{ gender }}</p>
                </div>
            </div>

            <!-- Links and Websites -->
            <LinksAndWebsites class="w-full"
                              :links="links"
                              @update:links="updateLinks"
            />
        </div>
    </div>

    <!-- form -->
    <div v-else class="rounded-md pt-2 pb-4 bg-white">
        <div class="px-4 text-sm md:text-sm space-y-6">
            <!-- Work Availability -->
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="workAvailability" class="sr-only peer">
                <div
                    class="relative w-11 h-6 bg-zinc-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-orange"></div>
                <span class="ms-3 text-base text-zinc-700">Available for work</span>
            </label>

            <!-- Full Name -->
            <div class="w-full">
                <span class="mb-2 text-zinc-500 text-base">* Provide your First and Last name.</span>
                <div class="relative mt-1">
                    <input v-model="fullName"
                           placeholder="Full name"
                           :class="{'border-red-500': v$.fullName.$error}"
                           class="text-sm block w-full p-2.5 bg-zinc-50 rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           type="text"
                           @blur="v$.fullName.$touch">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>
                </div>
                <span v-if="v$.fullName.$error" class="text-red-500 text-xs">Full name is required</span>
            </div>

            <!-- Contact Information -->
            <div class="flex md:flex-nowrap flex-wrap md:space-y-0 space-y-8 items-start md:space-x-3 space-x-0">
                <PhoneInput
                    v-model="phone"
                    :is-checked="isChecked"
                    @update:isChecked="updateIsChecked"
                />
                <div class="relative w-full">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                    <input type="email" v-model="emailValue"
                           :class="{'border-red-500': v$.emailValue.$error}"
                           class="text-sm block w-full p-2.5 bg-zinc-50 rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="name@example.com"
                           @blur="v$.emailValue.$touch">
                    <span v-if="v$.emailValue.$error" class="text-red-500 text-xs">Valid email is required</span>
                </div>
            </div>

            <!-- Residence -->
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <div class="flex">
                    <select v-model="city"
                            :class="[city == 'Baghdad' ? 'rounded-l-md' : 'rounded', {'border-red-500': v$.city.$error}]"
                            class="h-10 text-sm focus:border-orange focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                            @blur="v$.city.$touch">
                        <option value="Choose your city..." class="hidden" selected>Choose your city...</option>
                        <option v-for="cityOption in cities" :key="cityOption">{{ cityOption }}</option>
                    </select>

                    <select v-if="city == 'Baghdad'" v-model="zone"
                            :class="{'border-red-500': v$.zone.$error}"
                            class="h-10 text-sm focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                            @blur="v$.zone.$touch">
                        <option value="Choose your zone..." class="hidden">Choose your zone...</option>
                        <option>Karkh</option>
                        <option>Risafa</option>
                    </select>
                </div>
                <span v-if="v$.city.$error" class="text-red-500 text-xs">City is required</span>
                <span v-if="city == 'Baghdad' && v$.zone.$error" class="text-red-500 text-xs">Zone is required for Baghdad</span>
            </div>

            <!-- Date of Birth and Gender -->
            <div class="flex md:flex-nowrap flex-wrap md:space-x-3 space-x-0 md:space-y-0 space-y-8 items-center">
                <div class="relative w-full">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                    <input v-model="birthdate" type="date"
                           :class="{'border-red-500': v$.birthdate.$error}"
                           class="date-picker block w-full p-2.5 bg-zinc-50 rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="Birthdate"
                           @blur="v$.birthdate.$touch">
                    <span v-if="v$.birthdate.$error" class="text-red-500 text-xs">Date of birth is required</span>
                </div>

                <div class="relative w-full">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                    <select v-model="gender"
                            :class="{'border-red-500': v$.gender.$error}"
                            class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                            @blur="v$.gender.$touch">
                        <option value="Gender" class="hidden" selected>Gender</option>
                        <option>Female</option>
                        <option>Male</option>
                    </select>
                    <span v-if="v$.gender.$error" class="text-red-500 text-xs">Gender is required</span>
                </div>
            </div>

            <!-- Links and Websites -->
            <LinksAndWebsites
                :links="links"
                @update:links="updateLinks"
            />
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email as emailValidator } from '@vuelidate/validators';
import { getSelectables } from "../../utils/storeHelpers.js";
import store from "../../store/index.js";
import LinksAndWebsites from "../addableComponents/LinksAndWebsites.vue";
import PhoneInput from "./PhoneInput.vue";

const editMode = computed(() => store.getters.editMode);
const props = defineProps(["modelValue"]);

const cities = ref([]);
const city = ref('');
const gender = ref(props.modelValue.gender);
const zone = ref('');
const emailValue = ref('');
const phone = ref('');
const isChecked = ref(false);
const workAvailability = ref(false);
const fullName = ref('');
const birthdate = ref('');
const links = ref([]);

// Validation rules
const rules = computed(() => ({
    fullName: { required },
    emailValue: { required, email: emailValidator },
    city: { required },
    zone: { required: city.value === 'Baghdad' },
    gender: { required },
    birthdate: { required }
}));

const v$ = useVuelidate(rules, { fullName, emailValue, city, zone, gender, birthdate });

onMounted(async () => {
    try {
        cities.value = await getSelectables('cities');
    } catch (error) {
        console.error('Failed to fetch select options:', error);
    }

    fullName.value = props?.modelValue?.fullName;
    workAvailability.value = props?.modelValue?.workAvailability;
    emailValue.value = props?.modelValue?.email;
    phone.value = props?.modelValue?.phone || '';
    isChecked.value = props?.modelValue?.showPhone || false;
    city.value = props?.modelValue?.city;
    zone.value = props?.modelValue?.zone;
    gender.value = props?.modelValue?.gender;
    birthdate.value = props?.modelValue?.birthdate;
    links.value = props?.modelValue?.links || [];
});

const updateIsChecked = (value) => {
    isChecked.value = value;
};
const updateLinks = (newLinks) => {
    links.value = newLinks;
};

const emit = defineEmits(["update:modelValue"]);

watch([phone, isChecked, emailValue, city, zone, gender, links, birthdate, fullName, workAvailability], () => {
    emit('update:modelValue', {
        fullName: fullName.value,
        workAvailability: workAvailability.value,
        phone: phone.value,
        showPhone: isChecked.value,
        gender: gender.value,
        email: emailValue.value,
        links: links.value,
        birthdate: birthdate.value,
        city: city.value,
        zone: zone.value,
    });
}, { deep: true });

watch([fullName, emailValue, city, zone, gender, birthdate], () => {
    const isValid = !v$.value.$invalid;
    store.dispatch('setFormValidity', isValid);
}, { deep: true });
</script>

<style scoped>
/* Add any scoped styles here if needed */
</style>
