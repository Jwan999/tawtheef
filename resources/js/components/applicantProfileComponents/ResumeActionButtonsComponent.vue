<template>
    <div class="flex md:flex-row-reverse flex-wrap md:items-center md:space-y-0 space-y-3 w-full md:justify-between justify-end">
        <div class="flex">
            <div v-if="isEditable">
                <button
                    v-if="editMode"
                    @click="saveResume"
                    class="appearance-none text-sm rounded-bl-md px-3 py-2 font-semibold text-white bg-orange hover:bg-dark"
                >
                    Save
                </button>

                <button v-if="editMode" @click="handlePublishResume"
                        class="appearance-none text-sm px-3 py-2 font-semibold text-zinc-500 bg-zinc-200 hover:bg-dark hover:text-white">
                    <span v-if="!published">Publish</span>
                    <span v-else>Unpublish</span>
                </button>

                <button
                    @click="togglePreviewMode"
                    :class="!editMode ? 'text-white bg-orange rounded-bl-md': 'text-zinc-500 bg-zinc-200'"
                    class="appearance-none text-sm px-3 py-2 font-semibold hover:bg-dark hover:text-white"
                >
                    {{ editMode ? 'Preview' : 'Edit' }}
                </button>
            </div>
            <div v-if="!editMode">
                <button @click="generatePdf" :disabled="isGenerating"
                        class="appearance-none text-sm px-3 py-2 font-semibold hover:bg-dark hover:text-white text-zinc-500 bg-zinc-200">
                    {{ isGenerating ? 'Generating PDF...' : 'Generate Resume PDF' }}
                </button>
                <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
            </div>
        </div>

        <PublishState v-if="isEditable" class="md:w-auto w-full" :isPublished="published"/>
    </div>
</template>

<script setup>
import { computed, ref, onMounted, onUpdated } from "vue";
import store from '../../store/index.js';
import { useRoute, useRouter } from "vue-router";
import PublishState from "./PublishState.vue";

const props = defineProps(["published"]);
const emit = defineEmits(["publishResume", "saveResume", "validateAndPublish"]);

const editMode = computed(() => store.getters.editMode);
const isEditable = computed(() => {
    return route.params.id == store.getters.user?.applicant.id;
});

const published = ref(false);
const isGenerating = ref(false);
const errorMessage = ref('');
const thisApplicantId = ref(null);

const route = useRoute();
const router = useRouter();

const saveResume = () => {
    emit('saveResume');
};

const handlePublishResume = () => {
    emit('validateAndPublish');
};

const togglePreviewMode = () => {
    if (editMode.value) {
        store.dispatch('setEditMode', false);
    } else {
        editResume();
    }
};

const editResume = () => {
    router.push({name: 'profile-edit'});
    store.dispatch('setEditMode', true);
};

const generatePdf = async () => {
    isGenerating.value = true;
    errorMessage.value = '';

    try {
        const response = await axios.get(`/applicants/${thisApplicantId.value}/generate-profile`, {
            responseType: 'blob'
        });

        const contentType = response.headers['content-type'];
        if (contentType && contentType.indexOf('application/json') !== -1) {
            const reader = new FileReader();
            reader.onload = function () {
                const result = JSON.parse(reader.result);
                errorMessage.value = result.error || 'An error occurred while generating the PDF.';
            };
            reader.readAsText(response.data);
            return;
        }

        const file = new Blob([response.data], {type: 'application/pdf'});
        const fileURL = URL.createObjectURL(file);
        const link = document.createElement('a');
        link.href = fileURL;
        link.setAttribute('download', `applicant_profile_${thisApplicantId.value}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(fileURL);

    } catch (error) {
        console.error('Error generating PDF:', error);
        errorMessage.value = 'An error occurred while generating the PDF. Please try again later.';
    } finally {
        isGenerating.value = false;
    }
};

const getPageApplicantId = () => {
    const url = new URL(window.location.href);
    const pathSegments = url.pathname.split('/');

    if (pathSegments.length >= 3 && (pathSegments[1] === 'resume' || pathSegments[1] === 'profile')) {
        thisApplicantId.value = pathSegments[2];
    }
};

onMounted(() => {
    published.value = props?.published;
    getPageApplicantId();
});

onUpdated(() => {
    published.value = props?.published;
});
</script>
