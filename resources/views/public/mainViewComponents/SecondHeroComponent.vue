<template>
    <div class="md:px-16 px-6">
        <div class="md:my-20 my-10">
            <div class="md:pt-4 md:pb-5 py-6 md:px-10 px-4 inline-block bg-orange md:rounded-full rounded-3xl">
                <div class="space-x-3">
                    <span
                        class="bg-white w-32 text-center md:text-4xl text-3xl font-semibold rounded-full px-6 py-1 counter-wrapper">
                        <span ref="counterRef">{{ displayedCount }}</span>
                    </span>
                    <span class="text-white md:text-5xl text-3xl font-semibold">Resumes crafted and ready for opportunities.</span>
                </div>
            </div>
            <div class="flex md:justify-normal justify-between items-center gap-6 mt-8 md:px-8 px-4">
                <h1 class="md:text-4xl text-3xl font-semibold text-zinc-900">Build yours today.</h1>
                <router-link
                    :to="user ? `/profile/${user?.applicant?.id}` : '/login'"
                    custom
                    v-slot="{ navigate }">
                    <button
                        @click="navigate"
                        class="bg-orange hover:bg-orange-600 rounded-full size-8 mt-1 transform hover:translate-x-1 transition-transform duration-300 flex items-center justify-center transform transition-all duration-300 "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-white "
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                </router-link>
            </div>
        </div>

        <div class="md:mt-32 mt-24 md:px-6 px-4">
            <div>
                <h1 class="md:text-4xl text-3xl font-semibold">
                    Or looking for a specific candidate?
                </h1>
                <div class="text-2xl mt-3 flex items-center gap-2">
                    <div class="">everyone you need in one place
                        <button
                            @click="scrollToSearch"
                            class="text-orange  font-semibold hover:text-orange-600 transition-colors duration-300 relative group"
                        >
                            <span>go to candidates</span>
                            <span
                                class="absolute -bottom-1 left-0 w-0 h-0.5 bg-orange group-hover:w-full transition-all duration-300"></span>
                        </button>
                    </div>

                </div>
            </div>
        </div>


    </div>
    <div class="flex justify-end mt-18 -mt-0 md:-mt-32 px-4">
        <img class="md:w-7/12 w-full md:flex hidden object-cover mt-10" src="/public/svgs/circles.svg" alt="">
        <img class="md:w-7/12 w-6/12 flex md:hidden object-cover mt-10" src="/public/svgs/mobile-circles.svg" alt="">
    </div>
</template>

<script setup>
import {ref, computed, onMounted} from 'vue';
import {useStore} from 'vuex';
import axios from 'axios';

const store = useStore();
const user = computed(() => store.getters.user);
const totalCandidates = ref(0);
const displayedCount = ref(0);
const counterRef = ref(null);

const fetchTotalCandidates = async () => {
    try {
        const response = await axios.get('/api/statistics');
        totalCandidates.value = response.data.totalResumes;
        animateCounter();
    } catch (error) {
        console.error('Failed to fetch total candidates:', error);
    }
};

const animateCounter = () => {
    const duration = 2000;
    const start = performance.now();
    const startValue = 0;
    const endValue = totalCandidates.value;

    const updateCounter = (currentTime) => {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function for smooth animation
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        displayedCount.value = Math.floor(startValue + (endValue - startValue) * easeOutQuart);

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    };

    requestAnimationFrame(updateCounter);
};

const scrollToSearch = () => {
    const searchArea = document.getElementById('search-area');
    if (searchArea) {
        const yOffset = -80;
        const y = searchArea.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({
            top: y,
            behavior: 'smooth'
        });
    }
};

onMounted(() => {
    fetchTotalCandidates();
});
</script>

<style scoped>


.counter-wrapper {
    position: relative;
    overflow: hidden;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.counter-wrapper span {
    display: inline-block;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
