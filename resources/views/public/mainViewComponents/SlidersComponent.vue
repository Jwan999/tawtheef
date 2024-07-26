<template>
    <div class="mt-14 md:mt-32 mb-16">
        <div v-for="(item, index) in applicantsWithSpecialization"
             :key="index">
            <div v-if="item.applicants.length > 0">
                <div class="w-full md:w-3/12">
                    <div class="mb-10 w-full">
                        <div class="mt-3 md:mt-6 w-full">
                            <h1 class="w-full text-start py-1 my-2 px-3 font-bold tracking-wider text-2xl md:text-3xl uppercase">
                                {{ item.specialization == 'Other' ? 'Other Specialization' : item.specialization }}
                            </h1>
                            <div class="w-10/12 mt-6">
                                <div class="-mt-4 h-2.5 mb-2 w-11/12 rounded-br-2xl bg-orange"></div>
                                <div class="-mt-3 ml-8 h-2.5 w-full rounded-br-2xl bg-dark"></div>
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
                                    :class="applicant.contact.workAvailability ? 'bg-orange text-white':'bg-zinc-200 text-zinc-400'"
                                    class="rounded-full py-1 px-3 font-semibold text-sm absolute top-0 right-0 -mt-4">
                                    {{
                                        applicant.contact.workAvailability ? 'Available for work' : 'Not currently looking'
                                    }}
                                </div>
                                <div class="flex w-full items-center mt-3">
                                    <div class="border-[1px] border-orange p-1 rounded-tr-3xl rounded-bl-3xl w-64">
                                        <img class="object-cover w-full h-44 rounded-tr-3xl rounded-bl-3xl"
                                             :src="`/storage/${applicant.image}`" alt="">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="font-semibold text-lg">
                                        {{ applicant.contact.fullName }}
                                    </div>
                                    <h1 class="text-sm text-gray-800 font-semibold mb-4">{{
                                            applicant.contact.email
                                        }}</h1>
                                </div>
                                <div class="mt-2">
                                    <div v-if="applicant.topTools.length > 0" class="flex items-center space-x-2 capitalize">
                                        <div v-for="tool in applicant.topTools">
                                         <span class="bg-dark text-xs text-white font-semibold py-1 px-3 rounded-full">
                                             {{ tool.item }}
                                         </span>
                                        </div>
                                    </div>
                                    <p class="text-sm mt-4 text-wrap text-justify">
                                        {{ truncatedSummary(applicant) }}
                                        <button
                                            v-if="isTruncated(applicant)"
                                            @click.stop="goToResume(applicant.id)"
                                            class="inline-block text-orange hover:underline focus:outline-none">
                                            See more...
                                        </button>
<!--                                        <span v-if="isTruncated(applicant)">...</span>-->

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
import {computed, onMounted, ref} from "vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";
import {useRouter} from "vue-router";

const applicants = ref([])
const specialities = ref([])

const applicantsWithSpecialization = ref([])
const wordLimit = 14;

const isTruncated = (applicant) => {
    return applicant.summary.split(' ').length > wordLimit;
};

const truncatedSummary = (applicant) => {
    if (isTruncated(applicant)) {
        return applicant.summary.split(' ').slice(0, wordLimit).join(' ');
    }
    return applicant.summary;
};
const getApplicants = async () => {
    try {
        const response = await axios.get('/api/applicants');
        applicants.value = response.data.map(applicant => ({
            ...applicant,
            topTools: (applicant.tools || [])
                .filter(tool => tool.rating === 5)
                .slice(0, 2)
        }));
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
