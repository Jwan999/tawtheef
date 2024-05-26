<template>
    <div>

        <template v-if="showLoginForm">
            <LoginView></LoginView>
        </template>

        <template v-if="showSignupForm">
            <SignupView></SignupView>
        </template>

    </div>
</template>


<script setup>
import LoginView from "../../js/components/publicAuth/LoginView.vue";
import SignupView from "../../js/components/publicAuth/SignupView.vue";
import {computed} from "vue";

import {useRoute} from 'vue-router';

const route = useRoute();

const showLoginForm = computed(() => {
    return route.path === '/login'; // Show form on /login route
});

const showSignupForm = computed(() => {
    return route.path === '/signup'; // Show form on /signup route
});


</script>


<style scoped>

</style>
