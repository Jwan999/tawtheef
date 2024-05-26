<template>
    <div>
        <HeroComponent></HeroComponent>
        <SearchComponent></SearchComponent>
        <SlidersComponent></SlidersComponent>
        <PreviewAllComponent></PreviewAllComponent>
        <FooterComponent></FooterComponent>
    </div>
</template>

<script>
import SearchComponent from "./mainViewComponents/SearchComponent.vue";
import NavbarComponent from "./mainViewComponents/NavbarComponent.vue";
import SlidersComponent from "./mainViewComponents/SlidersComponent.vue";
import PreviewAllComponent from "./mainViewComponents/PreviewAllComponent.vue";
import FooterComponent from "./mainViewComponents/FooterComponent.vue";
import HeroComponent from "./mainViewComponents/HeroComponent.vue";

export default {
    name: "MainView",
    components: {HeroComponent, FooterComponent, PreviewAllComponent, SlidersComponent, NavbarComponent, SearchComponent}
}
</script>
