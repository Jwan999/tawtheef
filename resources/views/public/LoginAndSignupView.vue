<template>
    <div>
        <!--login form-->
        <!--this form is shown using v-if and based on the link of the page if login then true-->
        <template v-if="showLoginForm">
        <LoginView @emitLoginData="handleLogin" />
        </template>
        <!--signup form-->
        <!--this form is shown using v-else and based on the link of the page if signup then true-->
        <template v-if="showSignupForm">
        <SignupView></SignupView>
        </template>

    </div>
</template>


<script setup>
import LoginView from "../../js/components/publicAuth/LoginView.vue";
import SignupView from "../../js/components/publicAuth/SignupView.vue";
import {computed} from "vue";

import { useRoute } from 'vue-router'; // Assuming Vue Router is imported

const route = useRoute();

const showLoginForm = computed(() => {
    return route.path === '/login'; // Show form on /login route
});

const showSignupForm = computed(() => {
    return route.path === '/signup'; // Show form on /signup route
});
const handleLogin = (data) => {
    // Handle login data from the child component (potentially using Axios)
    console.log('Login data:', data);
    // You might send the data to your Laravel API for authentication
};



</script>


<style scoped>

</style>
