<template>
    <div v-if="!dataFetched" class="flex justify-center items-center h-screen">
        <LottieLoader/>
    </div>
    <div v-else :class="!isDashboard ? 'px-4 md:px-10 mt-6 mb-10' : ''" class="grid grid-cols-12 grid-flow-row gap-6 md:mb-0 mb-10">
        <!-- Existing content -->
        <ResumeActionButtonsComponent @saveResume="saveResume" @publishResume="publishResume" :published="published"
                                      class="col-span-12"></ResumeActionButtonsComponent>

        <div class="md:hidden block col-span-12">
            <ApplicantPhotoUpload v-model="image"></ApplicantPhotoUpload>

        </div>
        <div class="col-span-12 md:col-span-3 space-y-6 md:order-1 order-2">
            <div class="hidden md:block">
                <ApplicantPhotoUpload v-model="image"></ApplicantPhotoUpload>
            </div>
            <FieldOfInterestComponent v-model="speciality"></FieldOfInterestComponent>
            <EducationComponent v-model="education"></EducationComponent>
            <LanguagesComponent v-model="languages"></LanguagesComponent>
            <SkillsComponent v-model="skills"></SkillsComponent>
            <ToolsComponent v-model="tools"></ToolsComponent>
        </div>

        <div class="col-span-12 md:col-span-9 space-y-8 md:order-2 order-1">
            <div class="w-full">
                <ContactComponent v-model="contact"></ContactComponent>
            </div>
            <SummaryComponent v-model="summary"></SummaryComponent>
            <EmploymentComponent v-model="employment"></EmploymentComponent>
            <CoursesComponent v-model="courses"></CoursesComponent>
            <ActivitiesComponent v-model="activities"></ActivitiesComponent>
        </div>
    </div>

    <Alert :key="alertKey" :message="alertMessage" :type="alertType" :duration="5000" />

</template>


<script setup>
import Alert from "../../js/components/AlertComponent.vue"; // Import the Alert component
import {useRoute} from "vue-router";
import {computed, nextTick, onMounted, ref} from "vue";
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
import router from "../../js/router/index.js";
import LottieLoader from "../../js/components/LottieLoader.vue";
import PublishState from "../../js/components/applicantProfileComponents/PublishState.vue";

const alertMessage = ref('');
const alertType = ref('info');
const alertKey = ref(0); // Add this line
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
const user = computed(() => {
    return store.getters.user;
})
const showAlert = (message, type) => {
    alertMessage.value = '';
    alertType.value = 'info';
    alertKey.value += 1;

    // Use nextTick to ensure the component is updated before setting new values
    nextTick(() => {
        alertMessage.value = message;
        alertType.value = type;
    });
};
const saveResume = () => {
    console.log('Trying to save')
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
    if (validateRequiredFields()) {
        published.value = event.value;
        saveResume(); // Save the resume with the new published state
    } else {
        showAlert('Unable to publish. Please fill all required fields.', 'error');
    }
}

const validateRequiredFields = () => {
    // Add your validation logic here
    // Return true if all required fields are filled, false otherwise
    // You may want to highlight the empty required fields in each component
    return (
        contact.value &&
        contact.value.fullName &&
        contact.value.email &&
        contact.value.city &&
        summary.value &&
        speciality.value.length > 0 &&
        education.value.length > 0
        // Add more conditions as needed
    );
}



const toggleEditMode = () => {
    store.dispatch('setEditMode', !editMode.value);
};
const dataFetched = ref(false);

const route = useRoute();
const routeName = computed(() => {
    return route.name;
})
const routeParams = computed(() => route.params);
const checkRouteAndFetchData = async () => {

    if (routeName.value === 'resume-view') {
        await fetchApplicantData(`/api/applicants/${routeParams.value.id}`);
    }
    if (routeName.value === 'profile-edit') {
        await fetchApplicantData(`/api/profile`);
    }
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
        console.log(published.value)
        dataFetched.value = true;

    } catch (error) {
        if (error.response && error.response.status === 401) {
            // Redirect to login page
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
}

const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
</script>
