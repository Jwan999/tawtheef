<template>
    <!--preview-->
    <div v-if="!editMode" class="">

        <div v-if="!showInputs" class="bg-white p-4 text-sm rounded-md">

            <div class="flex justify-between items-center">
                <div class="w-3/12 flex items-center justify-start space-x-4">
                    <h1 class="flex-none font-semibold text-zinc-500">Contact info</h1>
                    <div class="font-semibold text-base">
                        <h1>{{ email }}</h1>
                        <h1 v-if="isChecked">+964 781 615 1297</h1>
                    </div>

                </div>
                <div class="w-3/12 flex justify-start items-center space-x-4">
                    <h1 class="flex-none font-semibold text-zinc-500">Residence</h1>

                    <h1 class="font-semibold text-base">{{ city + ', ' + zone }}</h1>
                </div>

                <div class="w-3/12 flex justify-start items-center space-x-4">

                    <h1 class="flex-none font-semibold text-zinc-500">Date of birth</h1>
                    <h1 class="font-semibold text-base">{{ birthdate }}</h1>

                </div>
                <div class="w-3/12 flex justify-start items-center space-x-4">

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
        <div v-else class="bg-white p-4 rounded-md">
            <h1 class="flex-none font-semibold mb-3 text-zinc-500">Contacts & other info</h1>
            <p class="text-sm text-zinc-700">
                Not all data filled yet.
            </p>
        </div>
    </div>
    <!--form-->
    <div v-else>
        <div class="rounded-md pt-2 pb-4 bg-white">

            <div class="space-y-6 px-4 text-sm md:text-sm">

                <div class="flex items-start space-x-3 mt-3">
                    <div class="w-full relative">
                        <input type="text" v-model="phone"
                               class="text-sm block w-full p-2.5 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                               placeholder="0964-000-0000-000" required/>
                        <div class="me-4 mt-2 absolute mt-[40px] top-0 left-0">
                            <input checked id="orange-checkbox" type="checkbox" value=""
                                   v-model="isChecked"
                                   class="text-sm w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600">
                            <span class="mb-2 text-sm font-medium text-zinc-500">Make phone number public.</span>
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
                                    class="h-[37.1px] text-sm focus:border-orange focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                <option value="" class="hidden" selected>Choose your city...</option>
                                <option v-for="city in cities">{{ city }}</option>

                            </select>

                            <select v-if="city == 'Baghdad'" v-model="zone" :value="zone"
                                    class="h-[37.1px] text-sm focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                <option value="" class="hidden" selected>Choose your zone...</option>
                                <option>Karkh</option>
                                <option>Risafa</option>
                            </select>

                        </div>
                        <!--                        <h1 class="text-red-500 text-sm font-semibold">This field is required.</h1>-->
                    </div>
                </div>

                <div class="flex space-x-3 items-center mt-3">

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
                                class="h-[37.1px] focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                            <option selected>Female</option>
                            <option>Male</option>
                        </select>
                        <!--<h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

                    </div>
                </div>

                <div>
                    <label for="message" class="block mb-3 text-sm font-medium text-zinc-700 mt-2">* You can add links to
                        websites you want hiring managers to see! Perhaps It will be a link to your portfolio, Linkedin
                        profile, or personal website.</label>

                    <div class="relative">
                        <div class="flex">
                            <input type="text" v-model="link" placeholder="http://portfolio.com"
                                   class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-l-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"/>
                            <input type="text" v-model="label" placeholder="Portfolio"
                                   class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"/>

                        </div>
                        <button type="submit" @click="addLink" @keyup:enter="addLink"
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
import {onMounted, ref, watch, watchEffect} from 'vue'; // Import ref and computed
import {editMode, getSelectables, getAuthUser} from "../../utils/storeHelpers.js";

const showInputs = ref(false)
const changeShowInputs = () => {
    if (editMode && birthdate.value === "") {
        showInputs.value = true
    } else {
        showInputs.value = false;
    }
}
onMounted(async () => {
    axios.get('/api/selectables/cities').then(async res => {
        cities.value = await getSelectables('cities');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);
    });
    changeShowInputs()
    getAuthUser().then(response => {
        email.value = response.email;
    }).catch(error => {
        console.error('Error fetching user data:', error);
    })
});

const cities = ref([])

const city = ref('');
const gender = ref('Female');
const zone = ref('');
const email = ref('');
const phone = ref('');
const birthdate = ref('');

const links = ref([]);
const link = ref('');
const label = ref('');
const isChecked = ref(false);


const addLink = () => {
    if (link.value.trim() !== '') { // Check for non-empty link
        links.value.push({link: link.value, label: label.value});
        link.value = '';
        label.value = '';
    }
};
const deleteLink = (index) => {
    links.value.splice(index, 1);
};

const emit = defineEmits(["update:modelValue"])

watch([phone, email, city, zone, gender, birthdate], () => {

    emit('update:modelValue',
        {
            phone: phone.value,
            gender: gender.value,
            email: email.value,
            links: links.value,
            birthdate: birthdate.value,
            city: city.value,
            zone: zone.value
        }
    )
    changeShowInputs()
}, {deep: true})

</script>


<style scoped>

</style>
