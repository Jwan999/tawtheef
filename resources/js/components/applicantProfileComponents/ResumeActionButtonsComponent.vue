<template>

    <div class="flex md:flex-row-reverse flex-wrap md:items-center md:space-y-0 space-y-3 w-full md:justify-between justify-end">
        <div class="flex">
            <div v-if="isEditable">
                <button
                    @click="!editMode ? editResume() :emit('saveResume')"
                    class="appearance-none text-sm rounded-bl-md px-3 py-2 font-semibold text-white bg-orange hover:bg-dark">
                    <svg v-if="!editMode" class="fill-white w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="m21.783 3.766-1.548-1.548c-1.088-1.088-2.858-1.088-3.946 0l-13.939 13.937c-.092.092-.151.211-.169.339l-.774 5.42c-.027.187.036.375.169.509.113.113.266.176.424.176.028 0 .057-.002.085-.006l5.42-.774c.128-.018.248-.078.339-.169l13.938-13.938c1.088-1.088 1.088-2.858 0-3.946zm-14.645 16.894-4.431.633.633-4.431 11.364-11.363 3.797 3.797-11.364 11.364zm13.796-13.796-1.584 1.584-3.797-3.797 1.584-1.584c.62-.62 1.629-.62 2.249 0l1.548 1.548c.62.62.62 1.629 0 2.249z"/>
                    </svg>
                    <span v-else>Save</span>
                </button>

                <button v-if="editMode" @click="publishResume"
                        class="appearance-none text-sm px-3 py-2 font-semibold text-zinc-500 bg-zinc-200 hover:bg-dark hover:text-white">
                    <span v-if="!published">Publish</span>
                    <span v-else>Un publish</span>
                </button>
            </div>
            <div>
                <button @click="generatePdf" :disabled="isGenerating"
                        class="appearance-none text-sm px-3 py-2 font-semibold hover:bg-dark hover:text-white text-zinc-500 bg-zinc-200">
                    {{ isGenerating ? 'Generating PDF...' : 'Generate Resume PDF' }}
                </button>
                <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
                <!-- <p v-if="pdfGenerated" class="success-message">PDF has been generated and downloaded.</p>-->
            </div>
        </div>

        <PublishState v-if="isEditable" class="md:w-auto w-full" :isPublished="published"/>


    </div>


</template>

<script setup>

import {computed, onMounted, onUpdated, ref, watch} from "vue";
import store from '../../store/index.js';
import {useRoute, useRouter} from "vue-router";
import {getSelectables} from "../../utils/storeHelpers.js";
import PublishState from "./PublishState.vue";

const isEditable = computed(() => {
    // different id data type
    return route.params.id == store.getters.user?.applicant.id;
})
const editMode = computed(() => store.getters.editMode)

const emit = defineEmits(["publishResume", "saveResume"])
const props = defineProps(["published"])
const published = ref(false)
const userId = computed(() => {
    return store.getters.user?.id;
});
const router = useRouter();
const thisApplicantId = ref(null)
const getPageApplicantId = () => {
    const url = new URL(window.location.href);
    const pathSegments = url.pathname.split('/');

    if (pathSegments.length >= 3 && (pathSegments[1] === 'resume' || pathSegments[1] === 'profile')) {
        thisApplicantId.value = pathSegments[2];
    }
}

const isGenerating = ref(false);
const pdfGenerated = ref(false);
const errorMessage = ref('');
const generatePdf = async () => {
    isGenerating.value = true;
    errorMessage.value = '';
    pdfGenerated.value = false;

    try {
        console.log('Generating PDF for applicant ID:', thisApplicantId.value);
        const response = await axios.get(`/applicants/${thisApplicantId.value}/generate-profile`, {
            responseType: 'blob'
        });

        console.log('Response received:', response);

        // Check if the response is JSON (error message) instead of a PDF
        const contentType = response.headers['content-type'];
        if (contentType && contentType.indexOf('application/json') !== -1) {
            // It's an error response
            const reader = new FileReader();
            reader.onload = function () {
                const result = JSON.parse(reader.result);
                console.error('Error from server:', result);
                errorMessage.value = result.error || 'An error occurred while generating the PDF.';
            };
            reader.readAsText(response.data);
            return;
        }

        // If it's not an error, proceed with PDF download
        const file = new Blob([response.data], {type: 'application/pdf'});
        const fileURL = URL.createObjectURL(file);
        const link = document.createElement('a');
        link.href = fileURL;
        link.setAttribute('download', `applicant_profile_${thisApplicantId.value}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(fileURL);

        pdfGenerated.value = true;
        console.log('PDF generated and download triggered');
    } catch (error) {
        console.error('Error generating PDF:', error);
        errorMessage.value = 'An error occurred while generating the PDF. Please try again later.';
    } finally {
        isGenerating.value = false;
    }
};
const saveResume = () => {
    console.log('testing save')
    emit('saveResume')
    toggleEditMode()
};
const publishResume = () => {
    published.value = !published.value
    emit('publishResume', published)
}
const editResume = () => {
    router.push({name: 'profile-edit'});
    toggleEditMode()
}
const toggleEditMode = () => {
    store.dispatch('setEditMode', !editMode.value);
};

const routeName = computed(() => {
    return route.name;
})

const route = useRoute();

onMounted(async () => {
    published.value = props?.published;
    getPageApplicantId()
});

onUpdated(() => {
    published.value = props?.published;
});

</script>

<style scoped>

</style>
