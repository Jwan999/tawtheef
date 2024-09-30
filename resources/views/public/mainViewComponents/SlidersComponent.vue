<template>
    <div class="mt-14 md:mt-32 mb-16">
        <div v-for="(item, index) in applicantsWithSpecialization" :key="index">
            <div v-if="item.applicants.length > 0">
                <div class="w-full md:w-3/12">
                    <div class="mb-10 w-full">
                        <div class="mt-3 md:mt-6 w-full">
                            <h1 class="w-full text-start py-1 my-2 px-3 font-bold tracking-wider text-2xl md:text-3xl">
                                {{ item.specialization === 'Other' ? 'Other Specialization' : item.specialization }}
                            </h1>
                            <div class="mt-6">
                                <div class="-mt-4 h-2.5 mb-2 w-full rounded-full shadow-custom-3d bg-orange"></div>
<!--                                <div class="-mt-3 ml-8 h-2.5 w-full rounded-br-2xl bg-dark"></div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto whitespace-nowrap space-x-6 px-0 mx-0 pt-6 pb-4 flex w-full">
                    <ApplicantCard
                        v-for="applicant in item.applicants"
                        :key="applicant.id"
                        :applicant="applicant"
                        :rounded="applicant === 1 ? 'rounded-r-lg' : 'rounded-lg'"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getSelectables } from '../../../js/utils/storeHelpers.js';
import ApplicantCard from './ApplicantCard.vue';

const applicants = ref([]);
const specialities = ref([]);
const applicantsWithSpecialization = ref([]);

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
