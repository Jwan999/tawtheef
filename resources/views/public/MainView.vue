<template>
    <div>
        <div class="bg-pattern">
            <HeroComponent class="mb-44"></HeroComponent>
            <NumbersComponent class="mb-44"></NumbersComponent>
            <div class="mt-10" id="search-area-spacer"></div>
            <SearchComponent id="search-area"></SearchComponent>
        </div>
        <SlidersComponent v-if="(!searchMode) && (!isAdvanceSearchInUse)"></SlidersComponent>
        <PreviewAllComponent v-if="(searchMode) || (isAdvanceSearchInUse)"></PreviewAllComponent>
        <FooterComponent></FooterComponent>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, watch, nextTick} from "vue";
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
.bg-pattern{
    background-image: url("/../../../public/svgs/pattern.svg");
    background-repeat: repeat;
}
</style>
