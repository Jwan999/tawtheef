<template>
    <div>
        <template v-if="isDashboard">
            <NavigationComponent/>
        </template>
        <template v-if="!isDashboard">
            <NavbarComponent></NavbarComponent>
        </template>

        <div :class="isDashboard ? 'p-4 sm:ml-64 mt-16' : 'mt-10'">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>
import {useRoute} from 'vue-router';
import NavigationComponent from '../js/components/NavigationComponent.vue';
import {computed} from "vue";
import NavbarComponent from "../views/public/mainViewComponents/NavbarComponent.vue";

const route = useRoute();

const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
</script>

<style scoped>

</style>
