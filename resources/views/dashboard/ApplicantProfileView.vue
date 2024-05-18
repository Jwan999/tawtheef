<template>


    <div :class="!isDashboard ? 'px-10 mt-6 mb-10' : ''" class="grid grid-cols-12 gap-6 relative">
        <!--first row-->
        <ResumeActionButtonsComponent @saveResume="saveResume" @publishResume="publishResume"
                                      class="z-40 absolute top-0 right-0 mr-10"></ResumeActionButtonsComponent>

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
import {editMode, getAuthUser} from "../../js/utils/storeHelpers.js";

const image = ref(null);
const speciality = ref(null);
const education = ref([]);
const languages = ref([]);
const tools = ref([]);
const skills = ref([]);
const summary = ref(null);
const details = ref('');
const courses = ref([]);
const contact = ref(null);
const employment = ref([]);
const activities = ref([]);
const published = ref(false);
const user = ref(null)
const publishResume = (event) => {
    published.value = event.value
}
const saveResume = () => {
    const requestData = {
        image: image.value,
        speciality: speciality.value,
        education: education.value,
        languages: languages.value,
        skills: skills.value,
        tools: tools.value,
        details: details.value,
        summary: summary.value,
        courses: courses.value,
        contact: contact.value,
        employment: employment.value,
        activities: activities.value,
        published: published.value
    };

    const formData = new FormData();
    formData.append('data', JSON.stringify(requestData));
    formData.append('image', image.value);

    axios.post('/api/applicant', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            console.log('Data sent successfully:', response.data);
        })
        .catch(error => {
            console.error('Error sending data:', error);
        });

    // toggleEditMode()
};
const toggleEditMode = () => {
    store.dispatch('setEditMode', !editMode.value);
};


const fetchData = async () => {
    try {
        const response = await axios.get(`/api/applicants/${user.id}`);
        const data = response.data;

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
        activities.value = data.activities;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

onMounted(() => {
    checkEditMode();

    getAuthUser().then(response => {
        user.value = response;
    }).catch(error => {
        console.error('Error fetching user data:', error);
    })
});


const checkEditMode = () => {
    return store.dispatch('checkEditMode', window.location.href);
}


const route = useRoute();

const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
</script>
