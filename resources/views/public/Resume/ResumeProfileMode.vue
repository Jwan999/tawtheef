<template>
    <div v-if="!dataFetched" class="flex justify-center items-center h-screen">
        <LottieLoader/>
    </div>

    <div v-else :class="!isDashboard ? 'px-4 md:px-8 mt-6 mb-10' : ''">
        <div class="flex w-full relative space-x-0 md:space-x-6">
            <!-- Overlay -->
            <div v-if="mobileMenuOpen"
                 class="fixed inset-0 bg-black bg-opacity-50 z-30"
                 @click="toggleMobileMenu"></div>

            <!-- Side Menu -->
            <div class="md:w-2/12 relative">
                <!-- Mobile Menu Toggle -->
                <button
                    @click="toggleMobileMenu"
                    :class="[
                        'md:hidden fixed z-50 bg-orange px-2 py-2 rounded-full shadow-lg transition-all duration-300 ease-in-out',
                        mobileMenuOpen ? 'left-[240px] top-5' : 'left-4 top-16'
                    ]"
                >
                    <span v-if="mobileMenuOpen">
                        <svg class="fill-current fill-white w-6 h-6" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                        </svg>
                    </span>
                    <span v-else>
                        <img class="w-5 h-5" src="/public/svgs/sidemenu.svg" alt="Menu">
                    </span>
                </button>

                <!-- Side Menu Content -->
                <div :class="[
                    'transition-all duration-300 ease-in-out',
                    'bg-white rounded-lg',
                    'fixed inset-y-0 left-0 z-40 w-64 md:w-auto md:relative',
                    mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
                    'flex flex-col h-screen',
                    'shadow-lg md:shadow-none'
                ]">
                    <!-- Main Menu Content -->
                    <div class="flex-1 overflow-y-auto px-4 py-6">
                        <!-- Status Section -->
                        <div class="mb-8">
                            <h2 class="text-base font-semibold text-zinc-700 mb-3 tracking-wide">Status</h2>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-zinc-600">Resume Status</span>
                                    <span :class="published ? 'text-orange font-semibold' : 'text-zinc-700 font-semibold'">
                                        {{ published ? 'Published' : 'Draft' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-zinc-600">Last Updated</span>
                                    <span class="text-zinc-800 font-semibold">{{ formatLastUpdated(updatedAt) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <h2 class="text-base font-semibold text-zinc-700 mb-3 tracking-wide">Actions</h2>
                            <button
                                @click="saveResume"
                                class="w-full px-4 py-2 bg-white border border-zinc-200 rounded-lg text-zinc-700 hover:bg-zinc-50 transition-colors duration-200 text-sm font-medium flex items-center justify-center space-x-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                <span>Save progress</span>
                            </button>

                            <button
                                @click="validateAndPublish"
                                class="w-full px-4 py-2 bg-orange text-white rounded-lg hover:bg-orange/90 transition-colors duration-200 text-sm font-medium flex items-center justify-center space-x-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                </svg>
                                <span>{{ published ? 'Hide Resume' : 'Publish Resume' }}</span>
                            </button>
                        </div>

                        <!-- Additional Options -->
                        <div class="mt-8">
                            <h2 class="text-base font-semibold text-zinc-700 mb-3 tracking-wide">Options</h2>
                            <div class="space-y-2">
                                <a
                                    :href="`/preview/${currentApplicantId}`"
                                    target="_blank"
                                    class="w-full text-center px-3 py-2 text-sm text-zinc-600 hover:bg-zinc-100 rounded-md transition-colors duration-200 flex items-center space-x-2 cursor-pointer"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <span>Preview Resume</span>
                                </a>
                                <a
                                    :href="`/applicants/${currentApplicantId}/view-profile`"
                                    target="_blank"
                                    class="w-full text-center px-3 py-2 text-sm text-zinc-600 hover:bg-zinc-100 rounded-md transition-colors duration-200 flex items-center space-x-2 cursor-pointer"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <path d="M9 15h6"></path>
                                        <path d="M9 11h6"></path>
                                    </svg>
                                    <span>Preview PDF</span>
                                </a>
                                <div
                                    v-if="editMode"
                                    @click="generatePdf"
                                    class="w-full text-center px-3 py-2 text-sm text-zinc-600 hover:bg-zinc-100 rounded-md transition-colors duration-200 flex items-center space-x-2 cursor-pointer"
                                    :class="{ 'opacity-75 cursor-not-allowed': isGenerating }"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"></path>
                                    </svg>
                                    <span>{{ isGenerating ? 'Generating PDF...' : 'Download PDF' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Section -->
                    <div class="p-4 border-t border-zinc-200">
                        <p class="text-xs text-zinc-500 text-center">
                            Last saved: {{ formatLastSaved(updatedAt) }}
                        </p>
                    </div>
                </div>
            </div>

            <!--content area with tabs-->
            <div class="w-full md:w-11/12 md:ml-4">
                <!-- Navigation Tabs -->
                <div class="w-full mb-6 bg-white rounded-lg">
                    <div class="overflow-x-auto no-scrollbar">
                        <div class="flex whitespace-nowrap border-b border-zinc-200 min-w-full">
                            <button
                                @click="openTab('personalInformation')"
                                :class="[
                                    'px-6 py-3 font-semibold tracking-wider transition-colors duration-200',
                                    'hover:text-orange focus:outline-none shrink-0',
                                    personalInformation ? 'text-orange border-b-[1px] border-orange' : 'text-zinc-600'
                                ]"
                            >
                                Personal Information
                            </button>
                            <button
                                @click="openTab('educationalInformation')"
                                :class="[
                                    'px-6 py-3 font-semibold tracking-wider transition-colors duration-200',
                                    'hover:text-orange focus:outline-none shrink-0',
                                    educationalInformation ? 'text-orange border-b-[1px] border-orange' : 'text-zinc-600'
                                ]"
                            >
                                Educational Background
                            </button>
                            <button
                                @click="openTab('professionalInformation')"
                                :class="[
                                    'px-6 py-3 font-semibold tracking-wider transition-colors duration-200',
                                    'hover:text-orange focus:outline-none shrink-0',
                                    professionalInformation ? 'text-orange border-b-[1px] border-orange' : 'text-zinc-600'
                                ]"
                            >
                                Professional Information
                            </button>
                            <button
                                @click="openTab('skillsAndActivities')"
                                :class="[
                                    'px-6 py-3 font-semibold tracking-wider transition-colors duration-200',
                                    'hover:text-orange focus:outline-none shrink-0',
                                    skillsAndActivities ? 'text-orange border-b-[1px] border-orange' : 'text-zinc-600'
                                ]"
                            >
                                Skills and Activities
                            </button>
                        </div>
                    </div>
                </div>

                <!--content sections-->
                <!--personal information-->
                <div :class="{ 'hidden': !personalInformation && isAnyOtherTabActive }"
                     class="w-full space-y-6 bg-white p-4 md:p-6 rounded-lg">
                    <ApplicantPhotoUpload v-model="image"></ApplicantPhotoUpload>
                    <ContactComponent v-model="contact" ref="contactComponent"></ContactComponent>
                </div>

                <!--educational background-->
                <div :class="{ 'hidden': !educationalInformation }"
                     class="w-full space-y-8 bg-white p-4 md:p-6 rounded-lg">
                    <FieldOfInterestComponent class="w-full" v-model="speciality"
                                              ref="fieldOfInterestComponent"></FieldOfInterestComponent>
                    <div class="w-full">
                        <EducationComponent v-model="education" ref="educationComponent"></EducationComponent>
                    </div>
                    <CoursesComponent v-model="courses" ref="coursesComponent"></CoursesComponent>
                </div>

                <!--professional information-->
                <div :class="{ 'hidden': !professionalInformation }"
                     class="w-full space-y-8 bg-white p-4 md:p-6 rounded-lg">
                    <SummaryComponent v-model="summary" ref="summaryComponent"></SummaryComponent>
                    <ToolsComponent v-model="tools" ref="toolsComponent"></ToolsComponent>
                    <EmploymentComponent v-model="employment" ref="employmentComponent"></EmploymentComponent>
                </div>

                <!--skills and activities-->
                <div :class="{ 'hidden': !skillsAndActivities }"
                     class="w-full space-y-8 bg-white p-4 md:p-6 rounded-lg">
                    <SkillsComponent v-model="skills" ref="skillsComponent"></SkillsComponent>
                    <LanguagesComponent v-model="languages" ref="languagesComponent"></LanguagesComponent>
                    <ActivitiesComponent v-model="activities" ref="activitiesComponent"></ActivitiesComponent>
                </div>
            </div>
        </div>
    </div>

    <Alert :key="alertKey" :message="alertMessage" :type="alertType" :duration="5000"/>
    <Alert :key="pdfAlertKey" :message="pdfAlertMessage" :type="pdfAlertType" :duration="5000"/>
</template>

<style scoped>
/* Hide scrollbar while allowing scrolling */
.no-scrollbar {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.no-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}

.bg-white {
    background-color: white;
}
</style>
<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import store from '../../../js/store/index';
import { useRoute, useRouter } from 'vue-router';
import Alert from "../../../js/components/AlertComponent.vue";
import LottieLoader from "../../../js/components/LottieLoader.vue";
import ApplicantPhotoUpload from "../../../js/components/applicantProfileComponents/ApplicantPhotoUpload.vue";
import FieldOfInterestComponent from "../../../js/components/applicantProfileComponents/FieldOfInterestComponent.vue";
import EducationComponent from "../../../js/components/applicantProfileComponents/EducationComponent.vue";
import LanguagesComponent from "../../../js/components/applicantProfileComponents/LanguagesComponent.vue";
import SkillsComponent from "../../../js/components/applicantProfileComponents/SkillsComponent.vue";
import ToolsComponent from "../../../js/components/applicantProfileComponents/ToolsComponent.vue";
import ContactComponent from "../../../js/components/applicantProfileComponents/ContactComponent.vue";
import SummaryComponent from "../../../js/components/applicantProfileComponents/SummaryComponent.vue";
import EmploymentComponent from "../../../js/components/applicantProfileComponents/EmploymentComponent.vue";
import CoursesComponent from "../../../js/components/applicantProfileComponents/CoursesComponent.vue";
import ActivitiesComponent from "../../../js/components/applicantProfileComponents/ActivitiesComponent.vue";
import { useResumeLogic } from '../Resume/ResumeLogic';

dayjs.extend(relativeTime);

const route = useRoute();
const router = useRouter();

const {
    alertMessage,
    alertType,
    alertKey,
    image,
    speciality,
    education,
    languages,
    tools,
    skills,
    summary,
    courses,
    contact,
    employment,
    activities,
    published,
    dataFetched,
    contactComponent,
    summaryComponent,
    fieldOfInterestComponent,
    educationComponent,
    languagesComponent,
    skillsComponent,
    toolsComponent,
    employmentComponent,
    coursesComponent,
    activitiesComponent,
    saveResume,
    validateAndPublish,
    isDashboard,
    personalInformation,
    educationalInformation,
    professionalInformation,
    skillsAndActivities,
    openTab,
    mobileMenuOpen,
    toggleMobileMenu,
    isAnyOtherTabActive,
    isEditable,
    updatedAt,
    formatLastUpdated,
    formatLastSaved,
    checkRouteAndFetchData
} = useResumeLogic();

const editMode = computed(() => store.getters.editMode);
const currentApplicantId = computed(() => store.getters.getCurrentApplicantId);

// PDF Generation related refs and functions
const isGenerating = ref(false);
const pdfAlertMessage = ref('');
const pdfAlertType = ref('info');
const pdfAlertKey = ref(0);

const showPdfAlert = (message, type) => {
    pdfAlertMessage.value = '';
    pdfAlertType.value = 'info';
    pdfAlertKey.value += 1;

    nextTick(() => {
        pdfAlertMessage.value = message;
        pdfAlertType.value = type;
    });
};

const generatePdf = async () => {
    if (isGenerating.value) return;

    isGenerating.value = true;

    try {
        const response = await axios.get(`/applicants/${currentApplicantId.value}/generate-profile`, {
            responseType: 'blob'
        });

        const contentType = response.headers['content-type'];
        if (contentType && contentType.indexOf('application/json') !== -1) {
            const reader = new FileReader();
            reader.onload = function() {
                const result = JSON.parse(reader.result);
                showPdfAlert(result.error || 'An error occurred while generating the PDF.', 'error');
            };
            reader.readAsText(response.data);
            return;
        }

        const file = new Blob([response.data], {type: 'application/pdf'});
        const fileURL = URL.createObjectURL(file);
        const link = document.createElement('a');
        link.href = fileURL;
        link.setAttribute('download', `applicant_profile_${currentApplicantId.value}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(fileURL);

    } catch (error) {
        console.error('Error generating PDF:', error);
        showPdfAlert('An error occurred while generating the PDF. Please try again in a minute.', 'error');
    } finally {
        isGenerating.value = false;
    }
};

onMounted(() => {
    store.dispatch('setPreviewMode', route.name === 'preview-view');
    store.dispatch('setEditMode', true); // Add this line to ensure edit mode is enabled
    checkRouteAndFetchData();
});
</script>
