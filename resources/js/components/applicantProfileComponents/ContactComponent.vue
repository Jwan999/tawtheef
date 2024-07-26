<template>
    <!--preview-->
    <div class="flex items-center space-x-3 mb-4">
        <h1 class="flex-none text-xl font-semibold text-dark">Personal Information</h1>
        <hr class="h-px w-full bg-orange border-0 mt-1">
    </div>

    <div v-if="!editMode" class="">

        <div v-if="city == 'Choose your city...'" class="bg-white p-4 rounded-md">
            <h1 class="flex-none font-semibold mb-3 text-zinc-500">Contacts & other info</h1>
            <p class="text-sm text-zinc-700">
                Not all data filled yet.
            </p>
        </div>

        <div v-else class="bg-white p-4 text-sm rounded-md">
                <span
                    :class="workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                    class=" text-base me-2 px-2.5 py-0.5 rounded-full dark:bg-orange-900 dark:text-orange-300">
                {{ workAvailability ? 'Available for Work' : 'Not currently looking' }}
            </span>
            <h1 class="text-2xl text-orange font-semibold mt-4 tracking-wider capitalize">{{ fullName }}</h1>
            <!--contact-->
            <div class="flex flex-wrap justify-between items-center mt-3">
                <div class="flex items-center justify-start space-x-4">
                    <h1 class="flex-none font-semibold text-zinc-500">Contact info</h1>
                    <div class="font-semibold text-base">
                        <h1>{{ email }}</h1>
                        <h1 v-if="isChecked">+964 781 615 1297</h1>
                    </div>

                </div>
                <div v-if="city" class="flex justify-start items-center space-x-4">
                    <h1 class="flex-none font-semibold text-zinc-500">Residence</h1>

                    <h1 class="font-semibold text-base">{{ city }}<span v-if="city=='Baghdad'">{{ ', ' + zone }}</span>
                    </h1>
                </div>

                <div v-if="birthdate" class="flex justify-start items-center space-x-4">

                    <h1 class="flex-none font-semibold text-zinc-500">Date of birth</h1>
                    <h1 class="font-semibold text-base">{{ birthdate }}</h1>

                </div>
                <div v-if="gender !== 'Gender'" class="flex justify-start items-center space-x-4">

                    <h1 class="flex-none font-semibold text-zinc-500">Gender</h1>
                    <h1 class="text-base font-semibold">{{ gender }}</h1>

                </div>
            </div>

            <div class="w-6/12 mt-4">
                <h1 class="flex-none font-semibold mb-2 text-zinc-500">Links & Websites</h1>

                <div v-for="(link, index) in links" :key="index">

                    <a :href="link.link"
                       class="text-orange text-base font-semibold underline">{{ link.label }}</a>
                </div>

            </div>
        </div>

    </div>
    <!--form-->
    <div v-else>
        <div class="rounded-md pt-2 pb-4 bg-white">

            <div class=" px-4 text-sm md:text-sm">
                <!--details-->

                <label class="inline-flex items-center mb-2 mt-2 cursor-pointer">
                    <input type="checkbox" value="" v-model="workAvailability" class="sr-only peer">
                    <div
                        class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none peer-focus:ring-orange dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-zinc-600 peer-checked:bg-orange"></div>
                    <span class="ms-3 text-base text-zinc-700">Available for work</span>
                </label>
                <div class="w-full mt-6">
                    <span class="mb-3 text-zinc-500 mt-2">* Please provide your First and Last name.</span>
                    <div class="relative">
                        <input v-model="fullName"
                               placeholder="Full name"
                               class="text-sm block w-full p-2.5 bg-zinc-50 w-full rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                               type="text">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>
                    </div>
                    <!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->
                </div>

                <!--contact-->
                <div class="flex items-start space-x-3 mt-6">
                    <div class="w-full relative">
                        <input type="text" v-model="phone"
                               class="text-sm block w-full p-2.5 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                               placeholder="0964-000-0000-000" required/>
                        <div class="me-4 mt-2 absolute mt-[44px] top-0 left-0">
                            <input checked id="orange-checkbox" type="checkbox" value=""
                                   v-model="isChecked"
                                   class="text-sm w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600">
                            <span class="mb-3 text-zinc-500 mt-2">Make phone number public.</span>
                        </div>
                    </div>
                    <div class="relative w-full">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <input type="text" v-model="email"
                               class="text-sm block w-full p-2.5 bg-zinc-50 w-full rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                               placeholder="name@flowbite.com">
                        <!--                        <h1 class="text-red-500 text-sm font-semibold">This field is required.</h1>-->
                    </div>

                    <div class="relative w-full">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <div class="flex">
                            <select v-model="city" :value="city"
                                    :class="city == 'Baghdad' ?'rounded-l-md' :'rounded'"
                                    class="h-[40px] text-sm focus:border-orange focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                <option value="Choose your city..." class="hidden" selected>Choose your city...</option>
                                <option v-for="city in cities">{{ city }}</option>
                            </select>

                            <select v-if="city == 'Baghdad'" v-model="zone" :value="zone"
                                    class="h-[40px] text-sm focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                <option value="Choose your zone..." class="hidden">Choose your zone...</option>
                                <option>Karkh</option>
                                <option>Risafa</option>
                            </select>

                        </div>
                        <!--                        <h1 class="text-red-500 text-sm font-semibold">This field is required.</h1>-->
                    </div>
                </div>

                <div class="flex space-x-3 items-center mt-10">

                    <div class="relative w-full">
                        <div class="relative w-full">

                            <input v-model="birthdate" type="date"
                                   class="date-picker z-40 block w-full p-2.5 bg-zinc-50 rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                                   placeholder="Birthdate">

                        </div>

                    </div>

                    <div class="relative w-full">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <select v-model="gender"
                                class="h-[40px] focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                            <option value="Gender" class="hidden" selected>Gender</option>
                            <option>Female</option>
                            <option>Male</option>
                        </select>
                        <!--<h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

                    </div>
                </div>

                <div class="mt-8">
                    <label for="message" class="block mb-3 text-zinc-500 mt-2">* You can add links
                        to
                        websites you want hiring managers to see! Perhaps It will be a link to your portfolio, Linkedin
                        profile, or personal website.</label>

                    <div class="relative">
                        <div class="flex">
                            <input type="text" v-model="link" placeholder="http://portfolio.com"
                                   class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-l-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"/>
                            <input type="text" v-model="label" placeholder="Portfolio"
                                   class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"/>

                        </div>
                        <button @click="addLink"
                                class="absolute top-0 end-0 px-3 h-full text-sm font-semibold text-white bg-orange rounded-e-md hover:bg-dark focus:outline-none">
                            ADD
                        </button>

                    </div>
                    <h1 v-for="(link, index) in links" :key="index"
                        class="flex bg-zinc-100 p-2 justify-between text-orange font-semibold underline mt-4">
                        <a class="cursor-pointer" :href="link.link">{{ link.label }}</a>
                        <button @click="deleteLink(index)"
                                class="focus:outline-none appearance-none hover:text-orange">
                            <svg class="fill-zinc-700 w-4 h-4 hover:fill-orange" viewBox="0 0 1024 1024"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                            </svg>
                        </button>
                    </h1>

                </div>


            </div>

        </div>

    </div>
</template>

<script setup>
import {computed, onMounted, onUpdated, ref, watch} from 'vue'; // Import ref and computed
import {getSelectables} from "../../utils/storeHelpers.js";
import store from "../../store/index.js";
import ApplicantDetailsComponent from "./ApplicantDetailsComponent.vue";

const editMode = computed(() => {
    return store.getters.editMode;
})
const props = defineProps(["modelValue"])

onMounted(async () => {
    axios.get('/api/selectables/cities').then(async res => {
        cities.value = await getSelectables('cities');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);
    });

});

const cities = ref([])

const city = ref('');
const gender = ref(props.modelValue.gender);
const zone = ref('');
const email = ref('');
const phone = ref('');
const isChecked = ref(false);
const workAvailability = ref(false)
const fullName = ref('')

const birthdate = ref('');
const links = ref([]);

const link = ref('');
const label = ref('');

const addLink = () => {
    if (link.value.trim() !== '') {
        links.value.push({link: link.value, label: label.value})
        link.value = '';
        label.value = '';
    }
};
const deleteLink = (index) => {
    links.value.splice(index, 1);
};

const emit = defineEmits(["update:modelValue"])
onMounted(() => {
    fullName.value = props?.modelValue?.fullName;
    workAvailability.value = props?.modelValue?.workAvailability;

    email.value = props?.modelValue?.email;
    phone.value = props?.modelValue?.phone;
    city.value = props?.modelValue?.city;
    zone.value = props?.modelValue?.zone;
    gender.value = props?.modelValue?.gender;
    birthdate.value = props?.modelValue?.birthdate;
    links.value = props?.modelValue?.links;
    isChecked.value = props?.modelValue?.showPhone
    isChecked.value = props?.modelValue?.showPhone
})
onUpdated(() => {
    fullName.value = props?.modelValue?.fullName;
    workAvailability.value = props?.modelValue?.workAvailability;

    email.value = props?.modelValue?.email;
    phone.value = props?.modelValue?.phone;
    city.value = props?.modelValue?.city;
    zone.value = props?.modelValue?.zone;
    gender.value = props?.modelValue?.gender;
    birthdate.value = props?.modelValue?.birthdate;
    links.value = props?.modelValue?.links;
    isChecked.value = props?.modelValue?.showPhone

})
watch([phone, email, city, zone, gender, links, birthdate, isChecked, fullName, workAvailability], () => {

    emit('update:modelValue',
        {
            fullName: fullName.value,
            workAvailability: workAvailability.value,
            phone: phone.value,
            gender: gender.value,
            email: email.value,
            links: links.value,
            birthdate: birthdate.value,
            city: city.value,
            zone: zone.value,
            showPhone: isChecked.value
        }
    )

}, {deep: true})

</script>


<style scoped>

</style>
