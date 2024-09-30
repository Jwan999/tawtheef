<template>
    <button @click="generatePdf" :disabled="isGenerating"
            class="bg-orange cursor-pointer text-white font-semibold text-md px-12 py-2 shadow-custom-3d rounded-full hover:bg-zinc-800 hover:shadow-none transition-all duration-300 ease-in-out transform hover:scale-105">
        {{ isGenerating ? 'Generating PDF...' : 'Generate Resume PDF' }}
    </button>
    <Alert :key="alertKey" :message="alertMessage" :type="alertType" :duration="5000"/>

    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
</template>
<script setup>
import {nextTick, onMounted, ref} from "vue";
const thisApplicantId = ref(null);
const alertMessage = ref('');
const alertType = ref('info');
const alertKey = ref(0);

const showAlert = (message, type) => {
    alertMessage.value = '';
    alertType.value = 'info';
    alertKey.value += 1;

    nextTick(() => {
        alertMessage.value = message;
        alertType.value = type;
    });
};
const getPageApplicantId = () => {
    const url = new URL(window.location.href);
    const pathSegments = url.pathname.split('/');

    if (pathSegments.length >= 3 && (pathSegments[1] === 'preview' || pathSegments[1] === 'profile')) {
        thisApplicantId.value = pathSegments[2];
    }
};

const isGenerating = ref(false);
const errorMessage = ref('');
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
        console.error('Error sending data:', error);
        showAlert('An error occurred while generating the PDF. Please try again in a minute.', 'error');
    } finally {
        isGenerating.value = false;
    }
};


onMounted(() => {
    getPageApplicantId();
});
</script>
