<template>
    <div class="flex justify-center mt-24">
        <div class="w-full mx-4 md:w-4/12 bg-white rounded-md">
            <div class="flex justify-between items-center mt-8 px-6">
                <h2 class="text-xl text-zinc-600 font-semibold">Login to your account</h2>
            </div>
            <div class="pl-6">
                <hr class="h-0.5 w-full bg-orange border-0 mt-2 mb-8">
            </div>

            <form @submit.prevent="handleLogin" class="px-6 space-y-4 my-10">
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Email Address</h1>
                    <input
                        type="email"
                        v-model="email"
                        required
                        class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                        placeholder="name@example.com">
                </div>

                <div class="form-group flex flex-col space-y-3">
                    <div class="flex justify-between items-center">
                        <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Password</h1>
                                                <router-link
                                                    to="/forgot-password"
                                                    class="text-sm text-orange hover:text-zinc-800">
                                                    Forgot Password?
                                                </router-link>
                    </div>
                    <input
                        type="password"
                        v-model="password"
                        required
                        class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                        placeholder="Enter your password">
                </div>

                <div class="flex justify-end space-x-6 items-center pt-2">
                    <router-link to="/signup" class="text-orange text-lg font-semibold hover:text-zinc-800">
                        or Signup
                    </router-link>

                    <button
                        type="submit"
                        :disabled="loading"
                        :class="{'opacity-50 cursor-not-allowed': loading}"
                        class="bg-orange text-white font-semibold text-md px-12 py-2 rounded-full hover:bg-zinc-800 hover:shadow-none transition-all duration-300 ease-in-out transform hover:scale-105">
                        {{ loading ? 'Logging in...' : 'Login' }}
                    </button>
                </div>
            </form>
        </div>

        <Alert :message="alertMessage" :type="alertType" v-if="alertMessage"/>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import {useRouter, useRoute} from 'vue-router';
import {useStore} from 'vuex';
import Alert from '../AlertComponent.vue';

const email = ref('');
const password = ref('');
const loading = ref(false);
const alertMessage = ref('');
const alertType = ref('info');

const router = useRouter();
const route = useRoute();
const store = useStore();

onMounted(() => {
    // Load reCAPTCHA v3
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render=' + import.meta.env.VITE_RECAPTCHA_SITE_KEY;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
});

const handleLogin = async () => {
    try {
        if (!email.value || !password.value) {
            alertMessage.value = 'Email and password are required!';
            alertType.value = 'error';
            return;
        }

        loading.value = true;
        alertMessage.value = '';

        // Get reCAPTCHA token
        const token = await new Promise((resolve, reject) => {
            window.grecaptcha.ready(() => {
                window.grecaptcha.execute(import.meta.env.VITE_RECAPTCHA_SITE_KEY, {action: 'login'})
                    .then(resolve)
                    .catch(reject);
            });
        });

        const response = await store.dispatch('login', {
            email: email.value,
            password: password.value,
            recaptcha_token: token
        });

        if (response.data.user) {
            alertMessage.value = 'Login successful!';
            alertType.value = 'success';

            if (response.data.user.role === 'admin') {
                const redirect = route.query.redirect === '/dashboard' ? '/dashboard' : '/dashboard';
                await router.push(redirect);
            } else {
                const redirect = route.query.redirect || '/';
                await router.push(redirect !== '/dashboard' ? redirect : '/');
            }
        }
    } catch (error) {
        console.error('Login error:', error);
        alertMessage.value = error.response?.data?.message || 'Login failed. Please try again.';
        alertType.value = 'error';
    } finally {
        loading.value = false;
    }
};
</script>
