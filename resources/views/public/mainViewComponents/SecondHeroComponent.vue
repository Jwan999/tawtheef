<template>
    <div class="md:px-16 px-6">
        <div class="md:my-24 my-12">
            <!-- Stats Container -->
            <div class="md:pt-6 md:pb-7 py-7 md:px-12 px-6 inline-block bg-dark md:rounded-full rounded-3xl shadow-lg">
                <div class="space-x-6">
                    <div class="bg-white rounded-full w-[10rem] py-2 font-semibold md:text-4xl text-3xl text-center inline-block shadow-inner">
                        <span ref="counterRef" class="text-dark">{{ displayedCount }}</span>
                    </div>
                    <span class="text-white md:text-5xl text-3xl font-bold tracking-tight">Resumes crafted and ready for opportunities.</span>
                </div>
            </div>

            <!-- Action Section -->
            <div class="flex md:justify-normal justify-between items-center gap-12 mt-10 md:px-8 px-4">
                <h1 class="md:text-4xl text-3xl font-bold text-zinc-900 tracking-tight">Build yours today.</h1>
                <router-link
                    :to="user ? `/profile/${user?.applicant?.id}` : '/login'"
                    custom
                    v-slot="{ navigate }">
                    <div class="relative">
                        <!-- Expanding rings -->
                        <div class="absolute inset-0">
                            <div class="absolute inset-0 animate-ring-1 rounded-full border-2 border-orange opacity-25"></div>
                            <div class="absolute inset-0 animate-ring-2 rounded-full border-2 border-orange opacity-20"></div>
                            <div class="absolute inset-0 animate-ring-3 rounded-full border-2 border-orange opacity-15"></div>
                        </div>

                        <!-- Main button with enhanced visibility -->
                        <button
                            @click="handleClick(navigate)"
                            @mouseenter="isHovered = true"
                            @mouseleave="isHovered = false"
                            class="relative bg-orange hover:bg-orange-600 rounded-full md:size-14 size-12 transform transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-orange/20 hover:-translate-y-0.5"
                            :class="{
                                'scale-95': isClicked,
                            }"
                        >
                            <!-- Ripple effect on click -->
                            <span
                                v-if="isClicked"
                                class="absolute inset-0 rounded-full animate-ripple bg-orange-400 opacity-30"
                            ></span>

                            <!-- Arrow icon -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="md:h-7 md:w-7 h-6 w-6 text-white transition-transform duration-300"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </router-link>
            </div>
        </div>

        <!-- Search Section -->
        <div class="md:mt-36 mt-28 md:px-6 px-4">
            <div>
                <h2 class="md:text-2xl text-xl font-semibold text-zinc-800">
                    Or looking for a specific candidate?
                </h2>
                <div class="text-xl mt-4 flex items-center gap-3">
                    <div class="text-zinc-700">Everyone you need in one place
                        <button
                            @click="scrollToSearch"
                            class="text-orange z-30 font-semibold hover:text-orange-600 transition-colors duration-300 relative group"
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

    <!-- Background Circles -->
    <div class="flex justify-end mt-10 -mt-0 md:-mt-48 px-4">
        <img class="md:w-10/12 w-full md:flex hidden object-cover mt-10 opacity-90" src="/public/svgs/circles.svg" alt="Background circles">
        <img class="md:w-6/12 w-6/12 flex md:hidden object-cover mt-10 opacity-90" src="/public/svgs/mobile-circles.svg" alt="Background circles mobile">
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import axios from 'axios';

const store = useStore();
const user = computed(() => store.getters.user);
const totalCandidates = ref(0);
const displayedCount = ref(0);
const counterRef = ref(null);
const isHovered = ref(false);
const isClicked = ref(false);

const handleClick = (navigate) => {
    isClicked.value = true;
    navigate();
    setTimeout(() => {
        isClicked.value = false;
    }, 300);
};

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
    // Get the search area element
    const searchArea = document.querySelector('#search-area');
    if (searchArea) {
        // Get the header height (assuming there's a fixed header)
        const headerHeight = 80; // Adjust this value based on your header height

        // Calculate the element's position relative to the viewport
        const elementPosition = searchArea.getBoundingClientRect().top;

        // Calculate the offset position
        const offsetPosition = elementPosition + window.pageYOffset - headerHeight;

        // Smooth scroll to the element
        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
};

// Initialize component
onMounted(() => {
    fetchTotalCandidates();
});
</script>

<style scoped>
.counter-wrapper {
    position: relative;
    overflow: hidden;
}

@keyframes ring-expand-1 {
    0% {
        transform: scale(1);
        opacity: 0.2;
    }
    100% {
        transform: scale(1.8);
        opacity: 0;
    }
}

@keyframes ring-expand-2 {
    0% {
        transform: scale(1);
        opacity: 0.15;
    }
    100% {
        transform: scale(2.2);
        opacity: 0;
    }
}

@keyframes ring-expand-3 {
    0% {
        transform: scale(1);
        opacity: 0.1;
    }
    100% {
        transform: scale(2.6);
        opacity: 0;
    }
}

.animate-ring-1 {
    animation: ring-expand-1 2s infinite cubic-bezier(0, 0, 0.2, 1);
}

.animate-ring-2 {
    animation: ring-expand-2 2s infinite cubic-bezier(0, 0, 0.2, 1);
    animation-delay: 0.5s;
}

.animate-ring-3 {
    animation: ring-expand-3 2s infinite cubic-bezier(0, 0, 0.2, 1);
    animation-delay: 1s;
}

@keyframes ripple {
    0% {
        transform: scale(1);
        opacity: 0.4;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.animate-ripple {
    animation: ripple 0.6s ease-out;
}
</style>
