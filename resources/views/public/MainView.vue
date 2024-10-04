<template>
    <div class="relative">
        <div class="bg-pattern relative">
            <div class="fade-overlay"></div>
            <HeroComponent class="mb-10 relative z-10"></HeroComponent>
            <NumbersComponent class="md:mb-44 mb-32 relative z-10"></NumbersComponent>
            <div class="mt-10 relative z-10" id="search-area-spacer"></div>
            <SearchComponent class="pb-24 relative z-10" id="search-area"></SearchComponent>
        </div>
        <SlidersComponent v-if="(!searchMode) && (!isAdvanceSearchInUse)"></SlidersComponent>
        <PreviewAllComponent v-if="(searchMode) || (isAdvanceSearchInUse)"></PreviewAllComponent>
        <FooterComponent></FooterComponent>
    </div>
</template>

<script setup>
import {computed, onMounted, nextTick} from "vue";
import {useRouter} from 'vue-router';
import {useStore} from 'vuex';
import SearchComponent from "./mainViewComponents/SearchComponent.vue";
import SlidersComponent from "./mainViewComponents/SlidersComponent.vue";
import PreviewAllComponent from "./mainViewComponents/PreviewAllComponent.vue";
import FooterComponent from "./mainViewComponents/FooterComponent.vue";
import HeroComponent from "./mainViewComponents/HeroComponent.vue";
import NumbersComponent from "./mainViewComponents/NumbersComponent.vue";

const router = useRouter();
const store = useStore();

const isAdvanceSearchInUse = computed(() => store.state.advanceSearchInUse);
const searchMode = computed(() => store.state.searchMode);

onMounted(() => {
    const hash = window.location.hash;
    if (hash === '#search-area') {
        nextTick(() => {
            const searchArea = document.getElementById('search-area');
            if (searchArea) {
                const yOffset = -80; // Adjust this value to fine-tune the final scroll position
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
    height: 1.5rem; /* Equivalent to Tailwind's mt-10 */
}
.bg-pattern {
    background-image: url("/svgs/background-pattern.svg");
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
    rgba(255,255,255,0) 0%,
    rgba(255,255,255,0) 70%,
    #F4F4F5 100%);
    pointer-events: none;
    z-index: 1;
}
</style>
