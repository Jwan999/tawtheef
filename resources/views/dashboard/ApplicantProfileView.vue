<template>
    <div class="flex justify-end">
        <button @click="saveResume" class="bg-dark text-white px-6 py-2 mx-10 rounded-md">Save Resume</button>

    </div>

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
            <ApplicantDetailsComponent class="w-9/12" v-model="details"></ApplicantDetailsComponent>

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
const education = ref([]);
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
    const formData = new FormData();

    formData.append('image', image.value);

    formData.append('speciality', JSON.stringify(speciality.value));
    formData.append('education', JSON.stringify(education.value));
    formData.append('languages', JSON.stringify(languages.value));
    formData.append('skills', skills.value); // No need to stringify
    formData.append('tools', JSON.stringify(tools.value));
    formData.append('details', JSON.stringify(details.value));
    formData.append('summary', summary.value);
    formData.append('courses', JSON.stringify(courses.value));
    formData.append('contact', JSON.stringify(contact.value));
    formData.append('employment', JSON.stringify(employment.value));
    formData.append('activities', JSON.stringify(activities.value));

    // Send the form data using Axios
    axios.post('/api/applicant', formData)
        .then(response => {
            console.log('Data sent successfully:', response.data);
        })
        .catch(error => {
            console.error('Error sending data:', error);
        });
};


const fetchData = async () => {
    try {
        const response = await axios.get('/api/applicants/1');
        const data = response.data;
        console.log(data)
        // Update the values of ref variables with the fetched data
        image.value = data.image;
        speciality.value = data.speciality;
        education.value = data.education;
        languages.value = data.languages;
        tools.value = data.tools;
        skills.value = data.skills;
        summary.value = data.summary;
        details.value = data.details;
        courses.value = data.courses;
        contact.value = data.contact;
        employment.value = data.employment;
        activities.value = JSON.parse(data.activities);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

onMounted(() => {
    checkEditMode();
    fetchData();
});

const checkEditMode = () => {
    return store.dispatch('checkEditMode', window.location.href);
}


const route = useRoute();

const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
</script>
