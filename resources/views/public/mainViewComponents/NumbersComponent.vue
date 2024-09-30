<!-- NumbersComponent.vue -->
<template>
    <div ref="componentRef" class="flex justify-center items-center w-full">
        <div
            class="rounded-full bg-orange flex justify-center items-center md:h-64 h-32 md:w-64 w-32 md:-mr-24 -mr-10 z-40"
        >
            <div class="mb-2">
                <h1 class="text-white text-center font-semibold text-3xl md:text-6xl">
                    {{ displayedTotal }}
                </h1>
                <h1 class="text-zinc-100 text-sm font-semibold tracking-wider text-center mt-1 md:mt-3">
                    Total resumes
                </h1>
            </div>
        </div>
        <div class="bg-zinc-800 flex justify-center rounded-full md:w-8/12 w-10/12">
            <div class="pl-16 md:py-16 py-6 sm:px-24 lg:px-32 w-full">
                <div class="flex items-center">
                    <transition name="fade-slide" mode="out-in">
                        <p
                            v-if="isNumberAnimationComplete"
                            :key="currentSentenceIndex"
                            class="text-lg md:text-5xl text-white items-center font-semibold text-start kanit-black-italic tracking-wider animated-sentence"
                        >
                            <span class="text-orange text-2xl md:text-5xl mr-1 md:mr-3">{{
                                    currentSentence.number
                                }}</span>
                            {{ currentSentence.text }}
                        </p>
                    </transition>
                </div>
                <h2 class="text-zinc-100 md:text-md text-sm text-start mt-2 md:mt-4">
                    Tawtheef Talent Insights
                </h2>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';

const sentences = [
    { number: "43", text: "job placements this month" },
    { number: "78", text: "new partner companies joined this year" },
    { number: "56", text: "candidates secured interviews within a month" },
    { number: "25", text: "industry sectors represented in our talent pool" },
    { number: "62", text: "placements resulted in long-term employment" },
    { number: "39", text: "countries represented in our global talent network" },
    { number: "81", text: "clients reported high satisfaction with our service" },
    { number: "47", text: "specialized skill categories in our database" },
    { number: "33", text: "new tech sector placements this quarter" },
    { number: "59", text: "leadership positions filled this quarter" },
];

const componentRef = ref(null);
const currentSentenceIndex = ref(0);
const currentSentence = ref(sentences[0]);

const totalResumes = ref(384); // This will be updated with the actual value from the backend
const displayedTotal = ref(0);
const isNumberAnimationComplete = ref(false);
const isComponentVisible = ref(false);

let intervalId;
let animationFrameId;
let observer;

const rotateSentences = () => {
    currentSentenceIndex.value = (currentSentenceIndex.value + 1) % sentences.length;
    currentSentence.value = sentences[currentSentenceIndex.value];
};

const animateTotal = (timestamp) => {
    if (!animateTotal.start) animateTotal.start = timestamp;
    const elapsed = timestamp - animateTotal.start;

    // Use an easing function to slow down the animation near the end
    const progress = Math.min(elapsed / 2000, 1); // 2000ms duration
    const easedProgress = 1 - Math.pow(1 - progress, 4); // Ease out quart

    displayedTotal.value = Math.floor(easedProgress * totalResumes.value);

    if (progress < 1) {
        animationFrameId = requestAnimationFrame(animateTotal);
    } else {
        isNumberAnimationComplete.value = true;
        startSentenceRotation();
    }
};

const startSentenceRotation = () => {
    intervalId = setInterval(rotateSentences, 4000); // Change sentence every 4 seconds
};

const startAnimation = () => {
    if (!isComponentVisible.value) {
        isComponentVisible.value = true;
        animationFrameId = requestAnimationFrame(animateTotal);
    }
};

onMounted(() => {
    observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            startAnimation();
            observer.disconnect(); // Stop observing once animation starts
        }
    }, { threshold: 0.5 }); // Trigger when 50% of the component is visible

    if (componentRef.value) {
        observer.observe(componentRef.value);
    }
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
    if (animationFrameId) cancelAnimationFrame(animationFrameId);
    if (observer) observer.disconnect();
});

// Watch for changes in totalResumes and re-animate when it updates
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
