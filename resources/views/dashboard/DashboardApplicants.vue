<template>
    <div v-if="!dataFetched" class="flex justify-center items-center h-screen">
        <LottieLoader/>
    </div>
    <div v-else :class="!isDashboard ? 'px-4 md:px-10 mt-6 mb-10' : ''" class="grid grid-cols-12 grid-flow-row gap-6 md:mb-0 mb-10">
        <ResumeActionButtonsComponent
            @saveResume="saveResume"
            @publishResume="publishResume"
            @validateAndPublish="validateAndPublish"
            :published="published"
            class="col-span-12"
        />

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

    <Alert :key="alertKey" :message="alertMessage" :type="alertType" :duration="5000" />
</template>

<script setup>
import { useRoute } from "vue-router";
import { computed, nextTick, onMounted, ref } from "vue";
import store from '../../js/store/index.js';
import router from "../../js/router/index.js";
import { editMode } from "../../js/utils/storeHelpers.js";

import Alert from "../../js/components/AlertComponent.vue";
import LottieLoader from "../../js/components/LottieLoader.vue";
import ResumeActionButtonsComponent from "../../js/components/applicantProfileComponents/ResumeActionButtonsComponent.vue";
import ApplicantPhotoUpload from "../../js/components/applicantProfileComponents/ApplicantPhotoUpload.vue";
import FieldOfInterestComponent from "../../js/components/applicantProfileComponents/FieldOfInterestComponent.vue";
import EducationComponent from "../../js/components/applicantProfileComponents/EducationComponent.vue";
import LanguagesComponent from "../../js/components/applicantProfileComponents/LanguagesComponent.vue";
import SkillsComponent from "../../js/components/applicantProfileComponents/SkillsComponent.vue";
import ToolsComponent from "../../js/components/applicantProfileComponents/ToolsComponent.vue";
import ContactComponent from "../../js/components/applicantProfileComponents/ContactComponent.vue";
import SummaryComponent from "../../js/components/applicantProfileComponents/SummaryComponent.vue";
import EmploymentComponent from "../../js/components/applicantProfileComponents/EmploymentComponent.vue";
import CoursesComponent from "../../js/components/applicantProfileComponents/CoursesComponent.vue";
import ActivitiesComponent from "../../js/components/applicantProfileComponents/ActivitiesComponent.vue";

const alertMessage = ref('');
const alertType = ref('info');
const alertKey = ref(0);
const image = ref(null);
const speciality = ref([]);
const education = ref([]);
const languages = ref([]);
const tools = ref([]);
const skills = ref([]);
const summary = ref('');
const details = ref({});
const courses = ref([]);
const contact = ref(null);
const employment = ref([]);
const activities = ref([]);
const published = ref(false);
const dataFetched = ref(false);

// Component refs
const contactComponent = ref(null);
const summaryComponent = ref(null);
const fieldOfInterestComponent = ref(null);
const educationComponent = ref(null);
const languagesComponent = ref(null);
const skillsComponent = ref(null);
const toolsComponent = ref(null);
const employmentComponent = ref(null);
const coursesComponent = ref(null);
const activitiesComponent = ref(null);

const user = computed(() => store.getters.user);

const showAlert = (message, type) => {
    alertMessage.value = '';
    alertType.value = 'info';
    alertKey.value += 1;

    nextTick(() => {
        alertMessage.value = message;
        alertType.value = type;
    });
};

const saveResume = () => {
    console.log('Trying to save');
    const requestData = {
        image: image.value,
        speciality: speciality.value,
        education: education.value,
        languages: languages.value,
        skills: skills.value,
        tools: tools.value,
        summary: summary.value,
        courses: courses.value,
        contact: contact.value,
        employment: employment.value,
        activities: activities.value,
        published: published.value,
    };

    const formData = new FormData();

    formData.append('data', JSON.stringify(requestData));

    if (image.value instanceof File) {
        formData.append('image', image.value);
    } else if (typeof image.value === 'string') {
        formData.append('image', image.value);
    }

    axios.post('/api/applicant', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            showAlert('Data saved successfully.', 'success');
            console.log('Data sent successfully:', response.data);
        })
        .catch(error => {
            console.error('Error sending data:', error);
            showAlert('Unable to save. Please try again.', 'error');
        });
};

const publishResume = (event) => {
    published.value = event.value;
    saveResume();
};

const validateAndPublish = () => {
    const components = [
        { ref: contactComponent, name: 'Contact' },
        { ref: summaryComponent, name: 'Summary' },
        { ref: fieldOfInterestComponent, name: 'Field of Interest' },
        { ref: educationComponent, name: 'Education' },
        { ref: languagesComponent, name: 'Languages' },
        { ref: skillsComponent, name: 'Skills' },
        { ref: toolsComponent, name: 'Tools' },
        { ref: employmentComponent, name: 'Employment' },
        { ref: coursesComponent, name: 'Courses' },
        { ref: activitiesComponent, name: 'Activities' }
    ];

    let isValid = true;
    let firstInvalidComponent = null;

    components.forEach(({ ref, name }) => {
        if (ref.value && typeof ref.value.validateFields === 'function') {
            const componentValid = ref.value.validateFields();
            if (!componentValid && !firstInvalidComponent) {
                firstInvalidComponent = { ref, name };
            }
            isValid = isValid && componentValid;
        }
    });

    if (isValid) {
        publishResume({ value: !published.value });
    } else {
        showAlert(`Please fill in all required fields in ${firstInvalidComponent.name} before publishing.`, 'error');
        if (firstInvalidComponent.ref.value && typeof firstInvalidComponent.ref.value.$el.scrollIntoView === 'function') {
            firstInvalidComponent.ref.value.$el.scrollIntoView({ behavior: 'smooth' });
        }
    }
};

const route = useRoute();
const routeName = computed(() => route.name);
const routeParams = computed(() => route.params);

const checkRouteAndFetchData = async () => {
    // resume-view
    if (routeName.value === 'preview-view') {
        await fetchApplicantData(`/api/applicants/${routeParams.value.id}`);
    }
    // profile-edit
    if (routeName.value === 'profile-view') {
        await fetchApplicantData(`/api/profile`);
    }
};

const fetchApplicantData = async (url) => {
    try {
        const response = await axios.get(url);

        image.value = response.data.image;
        speciality.value = response.data.speciality;
        education.value = response.data.education;
        languages.value = response.data.languages;
        tools.value = response.data.tools;
        skills.value = response.data.skills;
        summary.value = response.data.summary;
        details.value = response.data.details;
        courses.value = response.data.courses;
        contact.value = response.data.contact;
        employment.value = response.data.employment;
        activities.value = response.data.activities;
        published.value = response.data.published;
        dataFetched.value = true;
    } catch (error) {
        if (error.response && error.response.status === 401) {
            await router.push('/login');
        } else {
            console.error('Error fetching applicant data:', error);
        }
    }
};

onMounted(() => {
    checkEditMode();
    checkRouteAndFetchData();
});

const checkEditMode = () => {
    return store.dispatch('checkEditMode', window.location.href);
};

const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
</script>
