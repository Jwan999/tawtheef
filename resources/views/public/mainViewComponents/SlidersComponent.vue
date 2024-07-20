<template>
    <div class="mt-32 mb-16">

        <div v-for="(item, index) in applicantsWithSpecialization"
             :key="index">
            <div v-if="item.applicants.length > 0">
                <div class="w-3/12">
                    <div class="mb-10 w-full">
                        <div class="mt-6 w-full">
                            <h1 class="w-full text-end py-1 my-2 font-bold tracking-wider text-3xl uppercase">
                                {{ item.specialization == 'Other' ? 'Other Specialization' : item.specialization }}
                            </h1>
                            <div class="w-full mt-6">
                                <div class="-mt-4 h-5 w-11/12 rounded-br-2xl bg-orange"></div>
                                <div class="-mt-3 ml-8 h-3 w-full rounded-br-2xl bg-dark"></div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto whitespace-nowrap space-x-6 px-0 mx-0 pt-6 pb-4 flex w-full">
                    <div v-for="applicant in item.applicants" :class="applicant === 1 ? 'rounded-r-lg' : 'rounded-lg'"
                         class="relative bg-white w-72 p-4 inline-block cursor-pointer">
                        <div @click="goToResume(applicant.id)" class="w-full">
                            <div>
                                <div
                                    :class="applicant.details.workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                                    class="rounded-full py-1 px-3 font-semibold text-sm absolute top-0 right-0 -mt-4">
                                    {{ applicant.details.workAvailability ? 'Available for work' : 'Not currently looking' }}
                                </div>
                                <div class="flex w-full items-center mt-3">
                                    <div class="border-[1px] border-orange p-1 rounded-tr-3xl rounded-bl-3xl w-64">
                                        <img class="object-cover w-full h-32 rounded-tr-3xl rounded-bl-3xl"
                                             :src="`/storage/${applicant.image}`" alt="">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="font-semibold text-xl">
                                        {{ applicant.details.fullName }}
                                    </div>
                                    <h1 class="text-base text-gray-800 font-semibold mb-4">{{
                                            applicant.contact.email
                                        }}</h1>
                                </div>
                                <div class="mt-3">
                                    <div class="flex items-center space-x-2 capitalize">
                                        <div v-for="tool in applicant.tools">
                                         <span class="bg-dark text-sm text-white font-semibold py-1 px-3 rounded-full">
                                             {{ tool.item }}
                                         </span>
                                        </div>
                                    </div>
                                    <p class="text-sm mt-4 text-wrap text-justify">
                                        {{ applicant.summary }}
                                    </p>
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
import {onMounted, ref} from "vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";
import {useRouter} from "vue-router";

const applicants = ref([])
const specialities = ref([])

const applicantsWithSpecialization = ref([])
const getApplicants = async () => {
    try {
        const response = await axios.get('/api/applicants');
        applicants.value = response.data;
    } catch (error) {
        console.error('Error fetching applicants:', error);
        throw error;
    }
};

const showAdvanceSearch = ref(false)

const router = useRouter();
const goToResume = (index) => {
    router.push({name: 'resume-view', params: {id: index}});
};

onMounted(async () => {
    await getApplicants();
    specialities.value = await getSelectables('specialities');
    for (const speci of specialities.value) {
        applicantsWithSpecialization.value.push({
            applicants: applicants.value.filter(applicant => applicant.speciality.specializations.includes(speci.title)),
            specialization: speci.title
        });
    }
});

</script>

<style scoped>

</style>
