import {useRoute} from "vue-router";
import {computed, nextTick, onMounted, ref} from "vue";
import store from '../../../js/store/index.js';
import router from "../../../js/router/index.js";
import axios from 'axios';

export function useResumeLogic() {
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

    const mobileMenuOpen = ref(false);

    const toggleMobileMenu = () => {
        mobileMenuOpen.value = !mobileMenuOpen.value;
    };

    const personalInformation = ref(true);
    const educationalInformation = ref(false);
    const professionalInformation = ref(false);
    const skillsAndActivities = ref(false);

    const isAnyOtherTabActive = computed(() => {
        return educationalInformation.value || professionalInformation.value || skillsAndActivities.value;
    });

    const openTab = (tabTitle) => {
        // Reset all tabs to false
        personalInformation.value = false;
        educationalInformation.value = false;
        professionalInformation.value = false;
        skillsAndActivities.value = false;

        // Set the appropriate tab to true based on tabTitle
        if (tabTitle === 'personalInformation') {
            personalInformation.value = true;
        } else if (tabTitle === 'educationalInformation') {
            educationalInformation.value = true;
        } else if (tabTitle === 'professionalInformation') {
            professionalInformation.value = true;
        } else if (tabTitle === 'skillsAndActivities') {
            skillsAndActivities.value = true;
        }

        // If no tab is active, set personalInformation to true
        if (!isAnyOtherTabActive.value) {
            personalInformation.value = true;
        }
    };

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
            {ref: contactComponent, name: 'Contact'},
            {ref: summaryComponent, name: 'Summary'},
            {ref: fieldOfInterestComponent, name: 'Field of Interest'},
            {ref: educationComponent, name: 'Education'},
            {ref: languagesComponent, name: 'Languages'},
            {ref: skillsComponent, name: 'Skills'},
            {ref: toolsComponent, name: 'Tools'},
            {ref: employmentComponent, name: 'Employment'},
            {ref: coursesComponent, name: 'Courses'},
            {ref: activitiesComponent, name: 'Activities'}
        ];

        let isValid = true;
        let firstInvalidComponent = null;

        components.forEach(({ref, name}) => {
            if (ref.value && typeof ref.value.validateFields === 'function') {
                const componentValid = ref.value.validateFields();
                if (!componentValid && !firstInvalidComponent) {
                    firstInvalidComponent = {ref, name};
                }
                isValid = isValid && componentValid;
            }
        });

        if (isValid) {
            publishResume({value: !published.value});
        } else {
            showAlert(`Please fill in all required fields in ${firstInvalidComponent.name} before publishing.`, 'error');
            if (firstInvalidComponent.ref.value && typeof firstInvalidComponent.ref.value.$el.scrollIntoView === 'function') {
                firstInvalidComponent.ref.value.$el.scrollIntoView({behavior: 'smooth'});
            }
        }
    };

    const route = useRoute();
    const routeName = computed(() => route.name);
    const routeParams = computed(() => route.params);

    const checkRouteAndFetchData = async () => {
        if (routeName.value === 'preview-view') {
            await fetchApplicantData(`/api/applicants/${routeParams.value.id}`);
            store.dispatch('setEditMode', false);
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
            dataFetched.value = true;

            // Check if the current user is the owner of this applicant data
            const isOwner = store.getters.user?.id === response.data.user_id;
            store.dispatch('setCanEdit', isOwner && !store.getters.isPreviewMode);
        } catch (error) {
            if (error.response && error.response.status === 401) {
                await router.push('/login');
            } else {
                console.error('Error fetching applicant data:', error);
            }
        }
    };

    const isDashboard = computed(() => {
        return route.path.includes('/dashboard');
    });

    const isEditable = computed(() => store.getters.canEdit);
    const isPreviewMode = computed(() => store.getters.isPreviewMode);

    onMounted(() => {
        store.dispatch('setPreviewMode', routeName.value === 'preview-view');
        checkRouteAndFetchData().then(r => {});
    });
    const handleBackButton = () => {
        // Go back to the previous page
        router.back();

        // Use nextTick to ensure the navigation has completed before attempting to access DOM elements
        nextTick(() => {
            const searchMode = store.state.searchMode;
            if (searchMode) {
                const searchArea = document.getElementById('search-area');
                if (searchArea) {
                    searchArea.scrollIntoView({ behavior: 'smooth' });
                }
            } else {
                // If not in search mode, restore the last known scroll position
                const lastScrollPosition = store.state.lastScrollPosition || 0;
                window.scrollTo(0, lastScrollPosition);
            }
        });
    };

    onMounted(() => {
        store.commit('setPreviewMode', route.name === 'preview-view');
        checkRouteAndFetchData().then(r => {});

        // Save scroll position before leaving the page
        window.addEventListener('beforeunload', () => {
            store.commit('setLastScrollPosition', window.pageYOffset);
        });
    });

    return {
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
        isPreviewMode,
        handleBackButton,

    };
}
