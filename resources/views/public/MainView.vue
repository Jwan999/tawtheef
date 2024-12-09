<template>
    <div class="relative">
        <div class="relative">
            <div class="fade-overlay"></div>
            <SecondHeroComponent class="relative z-10"></SecondHeroComponent>
            <div class="relative z-10" id="search-area-spacer"></div>
            <SearchComponent class="relative z-10 -mt-32" id="search-area"></SearchComponent>
        </div>
        <PreviewAllComponent></PreviewAllComponent>
        <FooterComponent></FooterComponent>
    </div>
</template>

<script setup>
import { onMounted, nextTick } from "vue";
import { useRouter } from 'vue-router';
import SearchComponent from "./mainViewComponents/SearchComponent.vue";
import PreviewAllComponent from "./mainViewComponents/PreviewAllComponent.vue";
import FooterComponent from "./mainViewComponents/FooterComponent.vue";
import SecondHeroComponent from "./mainViewComponents/SecondHeroComponent.vue";
import WhyBuildWithUs from "./mainViewComponents/WhyBuildWithUs.vue";

const router = useRouter();

onMounted(() => {
    const hash = window.location.hash;
    if (hash === '#search-area') {
        nextTick(() => {
            const searchArea = document.getElementById('search-area');
            if (searchArea) {
                const yOffset = -80;
                const y = searchArea.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({
                    top: y,
                    behavior: 'smooth'
                });
            }
        });
    }
});
</script>

<style scoped>
#search-area-spacer {
    height: 1.5rem;
}

.bg-pattern {
    background-image: url("/public/svgs/background-pattern.svg");
    background-repeat: repeat;
    position: relative;
}

.fade-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 0) 70%,
    #F4F4F5 100%);
    pointer-events: none;
    z-index: 1;
}
</style>
