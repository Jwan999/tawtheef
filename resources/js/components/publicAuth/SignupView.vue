<template>
    <div class="flex justify-center my-14">
        <div class="w-full mx-4 md:w-4/12 bg-white rounded-md">
            <div class="flex justify-between items-center mt-8 px-6">
                <h2 class="text-lg text-zinc-600 font-semibold">Start with creating your account</h2>
            </div>
            <div class="pl-6">
                <hr class="h-0.5 w-full bg-orange border-0 mt-2 mb-8">
            </div>

            <form @submit.prevent="handleSignup" class="px-6 space-y-6 my-10">
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Full Name</h1>
                    <input
                        type="text"
                        v-model="name"
                        required
                        class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                        placeholder="Jane Doe">
                </div>

                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Pick your profile type</h1>
                    <div class="flex space-x-2">
                        <button
                            type="button"
                            @click="profileType = 'Applicant'"
                            :class="profileType === 'Applicant' ? 'bg-dark text-white' : 'bg-zinc-50 text-orange'"
                            class="rounded-b-md rounded-tl-md w-full text-sm font-semibold tracking-wider hover:bg-dark hover:text-white py-2">
                            Applicant
                        </button>
                        <button
                            type="button"
                            @click="profileType = 'Company'"
                            :class="profileType === 'Company' ? 'bg-dark text-white' : 'bg-zinc-50 text-orange'"
                            class="rounded-t-md rounded-br-md w-full text-sm font-semibold tracking-wider hover:bg-dark hover:text-white py-2">
                            Company
                        </button>
                    </div>
                </div>

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
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Password</h1>
                    <PasswordInput ref="passwordInput" />
                </div>

                <div class="flex justify-end space-x-6 items-center pt-2">
                    <router-link to="/login" class="text-orange text-lg font-semibold hover:text-zinc-800">
                        or Login
                    </router-link>

                    <button
                        type="submit"
                        :disabled="loading"
                        :class="{'opacity-50 cursor-not-allowed': loading}"
                        class="bg-orange cursor-pointer text-white font-semibold text-md px-12 py-2 rounded-full hover:bg-zinc-800 hover:shadow-none transition-all duration-300 ease-in-out transform hover:scale-105">
                        {{ loading ? 'Signing up...' : 'Signup' }}
                    </button>
                </div>
            </form>
        </div>

        <Alert :message="alertMessage" :type="alertType" v-if="alertMessage" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import PasswordInput from './PasswordInput.vue';
import Alert from '../AlertComponent.vue';

const email = ref('');
const profileType = ref('Applicant');
const name = ref('');
const passwordInput = ref(null);
const alertMessage = ref('');
const alertType = ref('info');
const loading = ref(false);

const router = useRouter();
const store = useStore();

onMounted(() => {
    // Load reCAPTCHA v3
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render=' + import.meta.env.VITE_RECAPTCHA_SITE_KEY;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
});

const validateForm = () => {
    if (!email.value || !name.value) {
        alertMessage.value = 'Please fill in all required fields';
        alertType.value = 'error';
        return false;
    }

    if (!passwordInput.value?.isValid) {
        alertMessage.value = 'Please ensure your passwords match';
        alertType.value = 'error';
        return false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        alertMessage.value = 'Please enter a valid email address';
        alertType.value = 'error';
        return false;
    }

    return true;
};

const handleSignup = async () => {
    try {
        if (!validateForm()) return;

        loading.value = true;
        alertMessage.value = '';

        // Get reCAPTCHA token
        const token = await new Promise((resolve, reject) => {
            window.grecaptcha.ready(() => {
                window.grecaptcha.execute(import.meta.env.VITE_RECAPTCHA_SITE_KEY, { action: 'signup' })
                    .then(resolve)
                    .catch(reject);
            });
        });

        const response = await store.dispatch('signup', {
            email: email.value,
            profile_type: profileType.value,
            name: name.value,
            password: passwordInput.value.password,
            recaptcha_token: token
        });

        if (response.data.user) {
            alertMessage.value = 'Sign up successful! Redirecting...';
            alertType.value = 'success';
            setTimeout(() => router.push('/'), 2000);
        }
    } catch (error) {
        console.error('Signup error:', error);
        alertMessage.value = error.response?.data?.message || 'An error occurred during sign up';
        alertType.value = 'error';
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.bg-dark {
    background-color: #2d3748;
}
</style>
