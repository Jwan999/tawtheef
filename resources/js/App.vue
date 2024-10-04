<template>
    <div v-if="!isLoading">
        <template v-if="isDashboard">
            <NavigationComponent/>
        </template>
        <template v-if="!isDashboard">
            <NavbarComponent></NavbarComponent>
        </template>

        <div :class="isDashboard ? 'p-4 sm:ml-64 mt-16' : ''">
            <router-view ></router-view>
        </div>
    </div>
    <div v-else>
        <LottieLoader />
    </div>


</template>

<script setup>
import {useRoute} from 'vue-router';
import NavigationComponent from '../js/components/NavigationComponent.vue';
import {computed, onMounted, ref} from "vue";
import NavbarComponent from "../views/public/mainViewComponents/NavbarComponent.vue";
import {useStore} from "vuex";
import LottieLoader from "./components/LottieLoader.vue";

const route = useRoute();
const user = computed(() => {
    return store.getters.user;
})
const isDashboard = computed(() => {
    return route.path.includes('/dashboard');
});
const store = useStore();
const isLoading = ref(true);
onMounted(async () => {
    const user = await store.dispatch('getUser', true);
    isLoading.value = false;
})
</script>

<style scoped>

</style>
