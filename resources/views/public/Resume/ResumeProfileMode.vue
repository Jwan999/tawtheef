<template>
    <div v-if="!dataFetched" class="flex justify-center items-center h-screen">
        <LottieLoader/>
    </div>

    <div v-else :class="!isDashboard ? 'px-4 md:px-8 mt-6 mb-10' : ''">
        <div class="flex w-full relative space-x-0 md:space-x-6">
            <!-- Overlay to close menu when clicking outside -->
            <div v-if="mobileMenuOpen"
                 class="fixed inset-0 bg-black bg-opacity-50 z-30"
                 @click="toggleMobileMenu"></div>

            <!-- Menu container -->
            <div class="md:w-3/12">
                <div :class="['transition-all duration-300 ease-in-out','bg-white rounded-lg text-zinc-600',
                'fixed md:relative inset-y-0 left-0 z-40',mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0','flex flex-col','pt-4']">
                    <div class="flex space-x-6 pb-6 border-b-[1px] border-zinc-200 mx-4">
                        <div class="space-y-2 w-4/12">
                            <img v-if="image" class="object-cover rounded-md h-20 w-full"
                                 :src="`/storage/${image}`"
                                 alt="">
                            <svg v-else class="h-14 w-full fill-dark" viewBox="-42 0 512 512.002"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/>
                                <path
                                    d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/>
                            </svg>
                        </div>
                        <div class="w-7/12 space-y-3">
                            <h1 class="text-sm font-semibold tracking-wide capitalize">{{ contact.fullName }}</h1>
                            <PublishState v-if="isEditable" class="md:w-auto w-full" :isPublished="published"/>
                        </div>

                    </div>

                    <!-- Menu toggle button (attached to the menu) -->
                    <button
                        @click="toggleMobileMenu"
                        :class="['md:hidden absolute top-16 z-50 bg-orange px-3 py-3 rounded-full shadow-lg',mobileMenuOpen ? '-right-16' : '-right-16','transition-all duration-300 ease-in-out' ]">
                        <span v-if="mobileMenuOpen">
                            <svg class="fill-current group-hover:fill-white fill-white w-6 h-6"
                                 viewBox="0 0 1024 1024"
                                 xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                                </svg>
                        </span>
                        <span v-else>
                            <img class="w-5 h-5" src="../../../../public/svgs/sidemenu.svg" alt="">
                        </span>
                    </button>

                    <div class="flex-grow p-4 overflow-y-auto">
                        <div class="grid grid-cols-1 gap-4 md:mt-0 mt-8">
                            <button :class="personalInformation ? 'bg-zinc-100 text-orange': 'text-zinc-700'"
                                    class="rounded-lg hover:text-orange font-semibold tracking-wider px-10 py-3 text-start hover:bg-zinc-100"
                                    @click="openTab('personalInformation')">Personal Information
                            </button>
                            <button
                                :class="educationalInformation ? 'bg-zinc-100 text-orange': 'text-zinc-700'"
                                class=" rounded-lg hover:text-orange font-semibold tracking-wider px-10 py-3 text-start hover:bg-zinc-100"
                                @click="openTab('educationalInformation')">Educational Background
                            </button>
                            <button
                                :class="professionalInformation ? 'bg-zinc-100 text-orange': 'text-zinc-700'"
                                class="rounded-lg hover:text-orange font-semibold tracking-wider px-10 py-3 text-start hover:bg-zinc-100"
                                @click="openTab('professionalInformation')">Professional Information
                            </button>
                            <button
                                :class="skillsAndActivities ? 'bg-zinc-100 text-orange': 'text-zinc-700'"
                                class="rounded-lg hover:text-orange font-semibold tracking-wider px-10 py-3 text-start hover:bg-zinc-100"
                                @click="openTab('skillsAndActivities')">Skills and Activities
                            </button>
                        </div>
                        <div class="mt-14">
                            <ResumeActionButtonsComponent
                                @saveResume="saveResume"
                                @publishResume="publishResume"
                                @validateAndPublish="validateAndPublish"
                                :published="published"
                            />
                        </div>
                    </div>
                </div>

            </div>
            <!--content area-->
            <div class="w-full md:w-10/12 md:ml-4">
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
</template>

<script setup>
import {useResumeLogic} from '../Resume/ResumeLogic';
import Alert from "../../../js/components/AlertComponent.vue";
import LottieLoader from "../../../js/components/LottieLoader.vue";
import ResumeActionButtonsComponent
    from "../../../js/components/applicantProfileComponents/ResumeActionButtonsComponent.vue";
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
import PublishState from "../../../js/components/applicantProfileComponents/PublishState.vue";

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
    publishResume,
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

} = useResumeLogic();
</script>

<style scoped>
/* Add any additional styles here if needed */
.bg-white {
    background-color: white;
}
</style>
