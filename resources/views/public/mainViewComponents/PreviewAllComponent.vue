<template>
    <div class="w-3/12 mt-24">
        <div class="mb-10 w-full">
            <h1 class="w-full text-end py-1 my-2 font-bold tracking-wider text-3xl uppercase">
                Search results
            </h1>
            <div class="mt-6 w-full">
                <div class="-mt-4 h-5 w-11/12 rounded-br-2xl bg-orange"></div>
                <div class="-mt-3 ml-8 h-3 w-full rounded-br-2xl bg-dark"></div>
            </div>
        </div>
    </div>
    <div class="mx-10">
        <div v-if="combinedApplicants.length > 0" class="grid grid-cols-5 justify-items-center gap-6">
            <div v-for="applicant in combinedApplicants" :class="applicant === 1 ? 'rounded-r-lg' : 'rounded-lg'"
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
        <div v-else class="flex justify-center items-center h-64">
            <p class="text-2xl font-semibold text-zinc-600">No search results available with your search query.</p>
        </div>
    </div>
</template>

<script setup>
import {computed} from "vue";
import router from "../../../js/router/index.js";
import store from "../../../js/store/index.js";

const goToResume = (index) => {
    router.push({name: 'resume-view', params: {id: index}});
};

const filteredApplicants = computed(() => store.getters.filteredApplicants);
const searchedApplicants = computed(() => store.getters.searchedApplicants);

const combinedApplicants = computed(() => {
    if (searchedApplicants.value.length > 0) {
        return searchedApplicants.value;
    } else {
        return filteredApplicants.value;
    }
});
</script>

<style scoped>
/* Your scoped styles here */
</style>
