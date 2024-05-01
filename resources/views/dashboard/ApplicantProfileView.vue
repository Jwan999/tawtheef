<template>

    <div :class="!isDashboard ? 'px-10 mt-6 mb-10' : ''" class="grid grid-cols-12 gap-6 relative">
        <!--first row-->
        <ResumeActionButtonsComponent class="z-40 absolute top-0 right-0 mr-10"></ResumeActionButtonsComponent>

        <div class="col-span-3 space-y-6">
            <ApplicantPhotoUpload v-model="image"></ApplicantPhotoUpload>
            <FieldOfInterestComponent v-model="speciality"></FieldOfInterestComponent>
            <EducationComponent v-model="education"></EducationComponent>
            <LanguagesComponent v-model="languages"></LanguagesComponent>
            <SkillsComponent v-model="skills"></SkillsComponent>
            <ToolsComponent v-model="tools"></ToolsComponent>
        </div>

        <div class="col-span-9 space-y-8">
            <ApplicantDetailsComponent v-model="details"></ApplicantDetailsComponent>

            <div class="w-full">
                <ContactComponent v-model="contact"></ContactComponent>
            </div>

            <SummaryComponent v-model="summary"></SummaryComponent>
            <EmploymentComponent v-model="employment"></EmploymentComponent>
            <CoursesComponent v-model="courses"></CoursesComponent>
            <ActivitiesComponent v-model="activities"></ActivitiesComponent>
        </div>

    </div>

</template>


<script setup>
import {useRoute} from "vue-router";
import {computed, onMounted, ref} from "vue";
import ActivitiesComponent from "../../js/components/applicantProfileComponents/ActivitiesComponent.vue";
import CoursesComponent from "../../js/components/applicantProfileComponents/CoursesComponent.vue";
import EmploymentComponent from "../../js/components/applicantProfileComponents/EmploymentComponent.vue";
import SummaryComponent from "../../js/components/applicantProfileComponents/SummaryComponent.vue";
import SkillsComponent from "../../js/components/applicantProfileComponents/SkillsComponent.vue";
import EducationComponent from "../../js/components/applicantProfileComponents/EducationComponent.vue";
import ApplicantDetailsComponent from "../../js/components/applicantProfileComponents/ApplicantDetailsComponent.vue";
import store from '../../js/store/index.js';
import FieldOfInterestComponent from "../../js/components/applicantProfileComponents/FieldOfInterestComponent.vue";
import ApplicantPhotoUpload from "../../js/components/applicantProfileComponents/ApplicantPhotoUpload.vue";
import ResumeActionButtonsComponent
    from "../../js/components/applicantProfileComponents/ResumeActionButtonsComponent.vue";
import LanguagesComponent from "../../js/components/applicantProfileComponents/LanguagesComponent.vue";
import ToolsComponent from "../../js/components/applicantProfileComponents/ToolsComponent.vue";
import ContactComponent from "../../js/components/applicantProfileComponents/ContactComponent.vue";
import {editMode} from "../../js/utils/storeHelpers.js";

const image = ref(null);
const speciality = ref(null);
const education = ref(null);
const languages = ref([]);
const tools = ref([]);
const skills = ref([]);
const summary = ref(null);
const details = ref(null);
const courses = ref([]);
const contact = ref(null);
const employment = ref([]);
const activities = ref([]);

const saveResume = () => {
    const data = {
        image: image.value,
        speciality: speciality.value,
        education: education.value,
        languages: languages.value,
        skills: skills.value,
        tools: tools.value,
        details: details.value,
        summary: summary.value,
        employment: employment.value,
        courses: courses.value,
        activities: activities.value
    };

    axios.post('/', data)
        .then(response => {
            console.log('Data sent successfully:', response.data);
        })
        .catch(error => {
            console.error('Error sending data:', error);
        });
};

const checkEditMode = () => {
    return store.dispatch('checkEditMode', window.location.href);
}

onMounted(checkEditMode);

const route = useRoute();

const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
</script>
