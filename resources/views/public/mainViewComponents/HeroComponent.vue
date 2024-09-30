<template>
    <div class="space-y-20">
        <div class="flex justify-center">
            <div class="w-11/12">
                <h1 class="text-7xl text-start shadow-text font-bold text-orange tracking-wider mt-6">
                    Tawtheef
                </h1>
                <div class="md:block hidden md:text-6xl text-start font-bold text-orange tracking-wider mt-6">
                    <span class="shadow-text">Nexus of </span>
                    <span class="bg-orange text-white md:text-5xl px-10 pt-1 pb-2 shadow-custom-3d rounded-full">talent</span>
                    <span class="shadow-text"> and </span>
                    <span class="bg-zinc-800 rounded-full text-white md:text-5xl  px-10 pt-0.5 pb-3 shadow-custom-3d-orange">opportunity</span>
                </div>
                <h1 class="block md:hidden text-6xl text-start font-bold text-orange tracking-wider shadow-text mt-6">
                    Nexus of talent and opportunity</h1>
            </div>
        </div>
        <div class="lg:flex lg:space-y-0 space-y-16 md:flex-nowrap flex-wrap justify-center justify-around">
            <div class="w-full lg:w-5/12">
                <div
                    @click="scrollToSearch"
                    class="hover:bg-white shadow-custom-3d hover:shadow-none hover:border-[1px] cursor-pointer hover:border-orange group bg-orange rounded-full w-full relative flex justify-center py-6 animated-bubble orange transition-all duration-300 ease-in-out transform hover:scale-105">
                    <div class="ring"></div>
                    <div class="md:m-6 mx-4 my-2">
                        <div class="flex flex-col items-start">
                            <img class="h-16" src="../../../../public/svgs/search.svg" alt="Search">
                            <div class="flex items-center space-x-3">
                                <h1 class="text-white mt-3 text-3xl md:text-4xl font-semibold tracking-wider group-hover:text-orange transition-colors duration-300">
                                    Discover exceptional talent</h1>
                                <img class="h-6 w-6 mt-4 transition-transform duration-300 group-hover:translate-x-1"
                                     src="../../../../public/svgs/down-arrow-3.svg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:w-5/12 w-full">
                <router-link :to="user ? `/profile/${user?.applicant?.id}` : '/login'">
                    <div
                        class="hover:bg-white shadow-custom-3d-orange hover:shadow-none hover:border-[1px] cursor-pointer hover:border-zinc-800 group bg-zinc-800 rounded-full w-full relative flex justify-center py-6 animated-bubble dark transition-all duration-300 ease-in-out transform hover:scale-105">
                        <div class="ring"></div>
                        <div class="md:m-6 mx-4 my-2 flex flex-col items-start">
                            <img class="h-16 mb-2" src="../../../../public/svgs/cv.svg" alt="Resume">
                            <div class="flex items-center space-x-3">
                                <h1 class="text-white text-3xl md:text-4xl font-semibold tracking-wider group-hover:text-zinc-800 transition-colors duration-300">
                                    {{ buttonText }} resume</h1>
                                <img class="h-6 w-6 mt-3 transition-transform duration-300 group-hover:translate-x-1"
                                     src="../../../../public/svgs/down-arrow-2.svg" alt="Arrow">
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import {useStore} from 'vuex';
import {getSelectables} from '../../../js/utils/storeHelpers.js';

const store = useStore();
const user = computed(() => store.getters.user);

const specialities = ref([]);

const buttonText = computed(() => {
    return user.value ? "Visit your" : "Build job-ready";
});

onMounted(async () => {
    try {
        specialities.value = await getSelectables('specialities');
    } catch (error) {
        console.error('Failed to fetch select options:', error);
    }
});

const scrollToSearch = () => {
    const searchArea = document.getElementById('search-area');
    if (searchArea) {
        const yOffset = -80; // Adjust this value to fine-tune the final scroll position
        const y = searchArea.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({
            top: y,
            behavior: 'smooth'
        });
    }
};
</script>

<style scoped>
.shadow-text {
    text-shadow: 1px 3px 0 rgba(0, 0, 0, 0.8);
}

.bg-herobg {
    background-image: url('../../../../public/svgs/Group 8.svg');
}

@keyframes bubble-grow {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    70% {
        transform: scale(1.05);
        opacity: 0.7;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes ring-pulse {
    0% {
        transform: scale(0);
        opacity: 0.5;
    }
    100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animated-bubble {
    position: relative;
    overflow: hidden;
    opacity: 0;
}

.animated-bubble.orange {
    animation: bubble-grow 1.2s cubic-bezier(0.25, 0.1, 0.25, 1) forwards;
}

.animated-bubble.dark {
    animation: bubble-grow 1.2s cubic-bezier(0.25, 0.1, 0.25, 1) forwards;
    animation-delay: 1.2s;
}

.ring {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
}

.animated-bubble.orange .ring {
    animation: ring-pulse 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) forwards;
}

.animated-bubble.dark .ring {
    animation: ring-pulse 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) forwards;
    animation-delay: 1.2s;
}

.animated-bubble::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
    opacity: 0;
}

.animated-bubble.orange::after {
    animation: shine 1.5s ease-out forwards;
}

.animated-bubble.dark::after {
    animation: shine 1.5s ease-out forwards;
    animation-delay: 1.2s;
}

@keyframes shine {
    0% {
        opacity: 0;
        transform: scale(0);
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        transform: scale(2);
    }
}

html {
    scroll-behavior: smooth;
}

@keyframes smoothScroll {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(var(--scroll-y));
    }
}

.smooth-scroll {
    animation: smoothScroll 0.8s cubic-bezier(0.65, 0, 0.35, 1) forwards;
}

/* Animation for SVG */
@keyframes slide-in {
    0% {
        transform: translateY(-100%);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.animated-svg {
    animation: slide-in 1s ease-out forwards;
    animation-delay: 0.5s; /* Delay to sync with the bubble animation */
}
</style>
