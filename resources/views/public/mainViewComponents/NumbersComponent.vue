<template>
    <div ref="componentRef" class="flex flex-wrap justify-start items-center w-full">
        <div
            class="rounded-full bg-white text-orange flex justify-center items-center md:h-64 h-32 md:w-64 w-32 ml-0 md:-mb-10 -mb-6 z-40">
            <div class="mb-2">
                <h1 class="text-center font-semibold text-3xl md:text-6xl">
                    {{ displayedTotal }}
                </h1>
                <h1 class="text-md font-semibold tracking-wider text-center mt-1 md:mt-3">
                    Total resumes
                </h1>
            </div>
        </div>
        <div class="bg-zinc-800 flex justify-center rounded-full w-full">
            <div class="md:pl-16 px-16 md:py-16 py-8 sm:px-24 lg:px-32 w-full">
                <div class="flex items-center">
                    <transition name="fade-slide" mode="out-in">
                        <p v-if="isNumberAnimationComplete"
                           :key="currentSentenceIndex"
                           class="flex md:flex-nowrap flex-wrap justify-start items-center md:space-x-3 space-x-0 text-lg md:text-5xl text-white font-semibold text-start kanit-black-italic tracking-wider animated-sentence">
                            <span class="text-orange-500 text-3xl md:text-7xl m-0 md:mr-3">
                                {{ currentSentence.number }}
                            </span>
                            <span class="text-xl md:text-4xl">
                                {{ currentSentence.text }}
                            </span>
                        </p>
                    </transition>
                </div>
                <h2 class="text-zinc-100 md:text-md text-sm text-start mt-0  md:mt-4">
                    Tawtheef Talent Insights
                </h2>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';

const sentences = ref([]);
const componentRef = ref(null);
const currentSentenceIndex = ref(0);
const currentSentence = ref({});

const totalResumes = ref(0);
const displayedTotal = ref(0);
const isNumberAnimationComplete = ref(false);
const isComponentVisible = ref(false);

let intervalId;
let animationFrameId;
let observer;

const fetchStatistics = async () => {
    try {
        const response = await axios.get('/api/statistics');
        const data = response.data;
        totalResumes.value = data.totalResumes;
        sentences.value = [
            {number: data.totalResumes.toString(), text: "Total resumes in our database"},
            {number: data.availableForWork.toString(), text: "Candidates are available for immediate work"},
            {number: data.bachelorOrHigher.toString(), text: "Candidates have a Bachelor's degree or higher"},
            {number: data.undergraduates.toString(), text: "Undergraduates seeking opportunities"},
            {number: data.creativeDesign.toString(), text: "Candidates specialize in Creative & Design"},
            {number: data.engineering.toString(), text: "Candidates have expertise in Engineering"},
            {number: data.graphicDesign.toString(), text: "Candidates are proficient in Graphic Design"},
            {number: data.development.toString(), text: "Candidates are skilled in various Development fields"},
            {number: data.maleCandidates.toString(), text: "Male candidates in our talent pool"},
            {number: data.femaleCandidates.toString(), text: "Female candidates ready for new opportunities"},
            {number: data.microsoftOffice.toString(), text: "Candidates are proficient in Microsoft Office tools"},
            {number: data.adobeSuite.toString(), text: "Candidates have experience with Adobe Creative Suite"},
            {number: data.bilingualCandidates.toString(), text: "Candidates are fluent in both Arabic and English"},
            {number: data.businessManagement.toString(), text: "Candidates have expertise in Business & Management"},
            {number: data.eventParticipants.toString(), text: "Candidates have participated in relevant events or workshops"}
        ];
        currentSentence.value = sentences.value[0];
    } catch (error) {
        console.error('Error fetching statistics:', error);
    }
};

const rotateSentences = () => {
    currentSentenceIndex.value = (currentSentenceIndex.value + 1) % sentences.value.length;
    currentSentence.value = sentences.value[currentSentenceIndex.value];
};

const animateTotal = (timestamp) => {
    if (!animateTotal.start) animateTotal.start = timestamp;
    const elapsed = timestamp - animateTotal.start;

    const progress = Math.min(elapsed / 2000, 1);
    const easedProgress = 1 - Math.pow(1 - progress, 4);

    displayedTotal.value = Math.floor(easedProgress * totalResumes.value);

    if (progress < 1) {
        animationFrameId = requestAnimationFrame(animateTotal);
    } else {
        isNumberAnimationComplete.value = true;
        startSentenceRotation();
    }
};

const startSentenceRotation = () => {
    intervalId = setInterval(rotateSentences, 4000);
};

const startAnimation = () => {
    if (!isComponentVisible.value) {
        isComponentVisible.value = true;
        animationFrameId = requestAnimationFrame(animateTotal);
    }
};

onMounted(async () => {
    await fetchStatistics();

    observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            startAnimation();
            observer.disconnect();
        }
    }, {threshold: 0.5});

    if (componentRef.value) {
        observer.observe(componentRef.value);
    }
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
    if (animationFrameId) cancelAnimationFrame(animationFrameId);
    if (observer) observer.disconnect();
});

watch(totalResumes, () => {
    isNumberAnimationComplete.value = false;
    isComponentVisible.value = false;
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }
    animateTotal.start = null;
    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
        animationFrameId = null;
    }
    if (componentRef.value && observer) {
        observer.observe(componentRef.value);
    }
});
</script>

<style scoped>
@keyframes fadeSlideIn {
    0% {
        opacity: 0;
        transform: translateY(20px) scale(0.75);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animated-sentence {
    animation: fadeSlideIn 0.6s cubic-bezier(0.770, 0.000, 0.175, 1.000) both;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.6s cubic-bezier(0.770, 0.000, 0.175, 1.000);
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(20px) scale(0.75);
}

.text-orange {
    @apply text-orange-500;
}
</style>
