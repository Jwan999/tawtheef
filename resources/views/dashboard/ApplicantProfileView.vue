<template>
    <!--    sm:ml-64 mt-16-->
    <div :class="!isDashboard ? 'px-10 mt-6 mb-10' : ''" class="grid grid-cols-12 gap-6 relative">
        <!--first row-->
        <ResumeActionButtonsComponent class="z-40 absolute top-0 right-0 mr-10"
        ></ResumeActionButtonsComponent>


        <div class="col-span-3 space-y-6">
            <ApplicantPhotoUpload v-model="imageUpdated"
                                  @imageUpdated="getImage(imageUpdated,$event)"></ApplicantPhotoUpload>

            <FieldOfInterestComponent v-model="specialityUpdated"
                                      @specialityUpdated="getValues('specialityUpdated',$event)"
                                      editMode></FieldOfInterestComponent>
            <EducationComponent v-model="educationUpdated" @educationUpdated="getValues('educationUpdated',$event)"
                                editMode></EducationComponent>

            <LanguagesComponent v-model="languagesUpdated" @languagesUpdated="getValues('languagesUpdated',$event)"
                                editMode></LanguagesComponent>

            <SkillsComponent v-model="skillsUpdated" @skillsUpdated="getValues('skillsUpdated',$event)"
                             editMode></SkillsComponent>
            <ToolsComponent v-model="toolsUpdated" @toolsUpdated="getValues('toolsUpdated',$event)"
                            editMode></ToolsComponent>
        </div>

        <div class="col-span-9 space-y-8">
            <ApplicantDetailsComponent v-model="detailsUpdated"
                                       @detailsUpdated="getValues('detailsUpdated',$event)"></ApplicantDetailsComponent>

            <div class="w-9/12">
                <ContactComponent v-model="contactUpdated"
                                  @detailsUpdated="getValues('contactUpdated',$event)"></ContactComponent>
            </div>

            <!--summary section-->
            <SummaryComponent v-model="summaryUpdated" @summaryUpdated="getValues('summaryUpdated',$event)"
                              editMode></SummaryComponent>
            <!--employment section-->
            <EmploymentComponent v-model="employmentUpdated" @employmentUpdated="getValues('employmentUpdated',$event)"
                                 editMode></EmploymentComponent>
            <!--            courses section-->
            <CoursesComponent v-model="coursesUpdated" @coursesUpdated="getValues('coursesUpdated',$event)"
                              editMode></CoursesComponent>
            <!--            activities section-->
            <ActivitiesComponent v-model="activitiesUpdated" @activitiesUpdated="getValues('activitiesUpdated',$event)"
                                 editMode></ActivitiesComponent>
        </div>


    </div>

</template>


<script setup>
import {useRoute} from "vue-router";
import {computed, onMounted, ref, watchEffect} from "vue";
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

const imageUpdated = ref(null);
const specialityUpdated = ref(null);
const educationUpdated = ref(null);
const languagesUpdated = ref(null);
const skillsUpdated = ref(null);
const toolsUpdated = ref(null);
const detailsUpdated = ref(null);
const contactUpdated = ref(null);
const summaryUpdated = ref(null);
const employmentUpdated = ref(null);
const coursesUpdated = ref(null);
const activitiesUpdated = ref(null);

const getImage = (title,data) => {
    console.log(data)
}

// const field = 'fullName'; // Replace with your child component's field name
const sendData = () => {
    const postData = {
        // Construct your data object to be sent to the server
        image: imageUpdated.value,
        speciality: specialityUpdated.value,
        education: educationUpdated.value,
        languages: languagesUpdated.value,
        skills: skillsUpdated.value,
        tools: toolsUpdated.value,
        details: detailsUpdated.value,
        summary: summaryUpdated.value,
        employment: employmentUpdated.value,
        courses: coursesUpdated.value,
        activities: activitiesUpdated.value
    };

    // Send POST request using Axios
    axios.post('your-api-endpoint', postData)
        .then(response => {
            console.log('Data sent successfully:', response.data);
            // Handle response if needed
        })
        .catch(error => {
            console.error('Error sending data:', error);
            // Handle error if needed
        });
};

// Watch for changes in reactive variables and trigger sendData when data is ready
watchEffect(() => {
    // Check if all data is available
    if (
        imageUpdated.value &&
        specialityUpdated.value &&
        educationUpdated.value &&
        languagesUpdated.value &&
        skillsUpdated.value &&
        toolsUpdated.value &&
        detailsUpdated.value &&
        summaryUpdated.value &&
        employmentUpdated.value &&
        coursesUpdated.value &&
        activitiesUpdated.value
    ) {
        // All data is available, trigger sendData
        sendData();
    }
});

// Methods to receive emitted data from each component
const getValues = (key, value) => {

    switch (key) {
        case 'imageUpdated':
            imageUpdated.value = value;
            console.log(value)
            break;
        case 'specialityUpdated':
            specialityUpdated.value = value;
            break;
        case 'educationUpdated':
            educationUpdated.value = value;
            break;
        case 'languagesUpdated':
            languagesUpdated.value = value;
            break;
        case 'skillsUpdated':
            skillsUpdated.value = value;
            break;
        case 'toolsUpdated':
            console.log(value)
            toolsUpdated.value = value;
            break;
        case 'detailsUpdated':
            detailsUpdated.value = value;
            break;
        case 'summaryUpdated':
            summaryUpdated.value = value;
            break;
        case 'employmentUpdated':
            employmentUpdated.value = value;
            break;
        case 'coursesUpdated':
            coursesUpdated.value = value;
            break;
        case 'activitiesUpdated':
            activitiesUpdated.value = value;
            break;
        default:
            console.warn('Unknown key:', key);
    }
};

// const handlePublishResume = () => {
//     console.log('publishResume function called in ApplicantProfileView');
//     publishResume();
// }

// const validateAllFields = () => {
//     const invalidFields = [];
//
//     // Validate basic fields
//     if (!fullName.value.trim()) {
//         invalidFields.push('fullName');
//     }
//     if (!email.value.trim()) {
//         invalidFields.push('email');
//     }
//     if (!image.value.trim()) {
//         invalidFields.push('image');
//     }
//     if (!speciality.value.trim()) {
//         invalidFields.push('speciality');
//     }
//     if (!subSpeciality.value.trim()) {
//         invalidFields.push('subSpeciality');
//     }
//     if (!workAvailability.value.trim()) {
//         invalidFields.push('workAvailability');
//     }
//     if (!phone.value.trim()) {
//         invalidFields.push('phone');
//     }
//     if (!location.value.trim()) { // Added validation for location
//         invalidFields.push('location');
//     }
//     if (!birthdate.value.trim()) { // Added validation for birthdate
//         invalidFields.push('birthdate');
//     }
//     if (!summary.value.trim()) {
//         invalidFields.push('summary');
//     }
//
//     // Validate nested arrays (education, employment, etc.)
//     invalidFields.push(...validateNestedArray(education.value, ['university', 'degree', 'college', 'year']));
//     invalidFields.push(...validateNestedArray(employment.value, ['title', 'employer', 'duration', 'description']));
//     invalidFields.push(...validateNestedArray(languages.value, ['language', 'rating']));
//     invalidFields.push(...validateNestedArray(skills.value, ['skill']));
//     invalidFields.push(...validateNestedArray(courses.value, ['title', 'duration', 'description']));
//     invalidFields.push(...validateNestedArray(activities.value, ['title', 'participatedAs', 'year']));
//
//     // Validate sub-objects (links)
//     links.value.forEach((link, index) => {
//         if (!link.hyperLink.trim() || !link.link.trim()) {
//             invalidFields.push(`links[${index}]`); // Include index for specific link error
//         }
//     });
//
//     tools.value.forEach((tool, index) => {
//         if (!tool.title.trim() || !tool.rating.trim()) {
//             invalidFields.push(`tools[${index}]`); // Include index for specific link error
//         }
//     });
//
//     // Validate other data structures as needed
//
//     // Return the array of invalid fields
//     return invalidFields;
// };
// const validateNestedArray = (array, requiredFields) => {
//     // Create an empty array to store errors for the nested array
//     const nestedErrors = [];
//
//     array.forEach((item, index) => {
//         requiredFields.forEach(field => {
//             if (!item[field].trim()) {
//                 nestedErrors.push(`[${index}].${field}`);  // Include index and field for specific error
//             }
//         });
//     });
//
//     return nestedErrors;
// };
// with alerts and ifs
// const publishResume = async () => {
//     // 1. Clear Validation Errors (important if user previously fixed issues)
//     store.commit('clearValidationErrors');
//
//     const isValid = validateAllFields();
//
//     if (!isValid) {
//         // 3. Update invalidFields Array in Store (if validation fails)
//         store.commit('setValidationErrors', validateAllFields()); // Pass the result of validateAllFields (likely an array of invalid fields)
//
//         alert('Validation failed. Please check your form.');
//         return; // Exit function if there are validation errors
//     }
//
//     // 4. Submit Form (if validation passes)
//     try {
//         const response = await axios.post('/', {
//             // FormData object or other data structure based on your API requirements
//             fullName: fullName.value,
//             email: email.value,
//             // ... other data from your state
//         });
//         // Handle successful response (e.g., show success message)
//     } catch (error) {
//         // Handle API request error (e.g., show error message)
//     }
// };
const saveResume = () => {
    // this would store everything without validation
}

const editMode = computed({
    get() {
        return store.getters.editMode;
    }
})

const checkEditMode = () => {
    return store.dispatch('checkEditMode', window.location.href);
}

onMounted(checkEditMode);

const route = useRoute();

const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
</script>

<!--const image = ref('');-->
<!--const speciality = ref('');-->
<!--const subSpeciality = ref('');-->
<!--const workAvailability = ref('');-->
<!--const fullName = ref('');-->
<!--const email = ref('');-->
<!--const location = ref('');-->
<!--const birthdate = ref('');-->
<!--const phone = ref('');-->
<!--const links = ref([]);-->
<!--const summary = ref('');-->
<!--const education = ref([]);-->
<!--const languages = ref([]);-->
<!--const skills = ref([])-->
<!--const tools = ref([])-->
<!--const employment = ref([])-->
<!--const courses = ref([])-->
<!--const activities = ref([])-->
<!--const published = ref(false)-->

<!--const getValues = (componentTitle, data) => {-->

<!--if (componentTitle === 'educationUpdated') {-->
<!--education.value.push({-->
<!--university: data.university,-->
<!--degree: data.degree,-->
<!--college: data.college,-->
<!--year: data.year,-->
<!--})-->
<!--}-->
<!--if (componentTitle === 'languagesUpdated') {-->
<!--}-->
<!--if (componentTitle === 'skillsUpdated') {-->
<!--skills.value.push({skill: 'test'})-->
<!--}-->
<!--if (componentTitle === 'toolsUpdated') {-->
<!--}-->
<!--if (componentTitle === 'summaryUpdated') {-->
<!--summary.value = data.summary-->
<!--}-->
<!--if (componentTitle === 'employmentUpdated') {-->
<!--}-->
<!--if (componentTitle === 'coursesUpdated') {-->
<!--}-->
<!--if (componentTitle === 'activitiesUpdated') {-->
<!--const activity = {title: data.title, participatedAs: data.participatedAs, year: data.year}-->
<!--activities.value.push(activity)-->
<!--}-->
<!--if (componentTitle === 'detailsUpdated') {-->
<!--image.value = data.image-->
<!--workAvailability.value = data.workAvailability-->
<!--fullName.value = data.fullName-->
<!--email.value = data.email-->
<!--phone.value = data.phone-->
<!--links.value = data.links-->
<!--location.value = data.location-->
<!--birthdate.value = data.birthdate-->
<!--}-->
<!--if (componentTitle === 'imageUpdated') {-->
<!--}-->
<!--if (componentTitle === 'specialityUpdated') {-->
<!--speciality.value = data.speciality-->
<!--subSpeciality.value = data.subSpeciality-->
<!--}-->

<!--}-->
