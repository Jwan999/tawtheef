<template>
    <div class="mt-6 px-4 md:px-10 flex w-full justify-end">
        <div class="flex justify-between w-full mb-6">
            <div class="md:w-1/12 sm:w-1/12 w-2/12">
                <!-- Back button commented out -->
            </div>
            <div class="fixed bottom-12 right-6 z-50">
                <GeneratePDFButton/>
            </div>
        </div>
    </div>
    <div v-if="!dataFetched" class="flex justify-center items-center h-screen">
        <LottieLoader/>
    </div>
    <div v-else :class="!isDashboard ? 'px-4 md:px-10 mt-6 mb-10' : ''"
         class="grid grid-cols-12 grid-flow-row gap-6 mb-10">

        <div class="md:hidden block col-span-12">
            <ApplicantPhotoUpload v-model="image"></ApplicantPhotoUpload>
        </div>
        <div class="col-span-12 md:col-span-3 space-y-6 md:order-1 order-2">

            <div class="hidden md:block">
                <ApplicantPhotoUpload v-model="image"></ApplicantPhotoUpload>
            </div>
            <FieldOfInterestComponent v-model="speciality" ref="fieldOfInterestComponent"></FieldOfInterestComponent>
            <EducationComponent v-model="education" ref="educationComponent"></EducationComponent>
            <LanguagesComponent v-model="languages" ref="languagesComponent"></LanguagesComponent>
            <SkillsComponent v-model="skills" ref="skillsComponent"></SkillsComponent>
            <ToolsComponent v-model="tools" ref="toolsComponent"></ToolsComponent>
        </div>

        <div class="col-span-12 md:col-span-9 space-y-8 md:order-2 order-1">

            <div class="w-full">
                <ContactComponent v-model="contact" ref="contactComponent"></ContactComponent>
            </div>
            <SummaryComponent v-model="summary" ref="summaryComponent"></SummaryComponent>
            <EmploymentComponent v-model="employment" ref="employmentComponent"></EmploymentComponent>
            <CoursesComponent v-model="courses" ref="coursesComponent"></CoursesComponent>
            <ActivitiesComponent v-model="activities" ref="activitiesComponent"></ActivitiesComponent>
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
import GeneratePDFButton from "../../../js/components/applicantProfileComponents/GeneratePDFButton.vue";

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
    goBack,
    canGoBack,

} = useResumeLogic();
// handleBackClick function commented out
</script>
