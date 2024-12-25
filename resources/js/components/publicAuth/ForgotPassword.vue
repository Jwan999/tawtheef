<!-- ForgotPassword.vue -->
<template>
    <div class="flex justify-center my-14">
        <div class="w-full mx-4 md:w-4/12 bg-white rounded-md">
            <div class="flex justify-between items-center mt-8 px-6">
                <h2 class="text-lg text-zinc-600 font-semibold">Reset Your Password</h2>
            </div>
            <div class="pl-6">
                <hr class="h-0.5 w-full bg-orange border-0 mt-2 mb-8">
            </div>

            <div class="px-6 space-y-6 my-10">
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Email Address</h1>
                    <input
                        type="email"
                        v-model="email"
                        :disabled="loading"
                        class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                        placeholder="Enter your email">
                </div>

                <div class="flex justify-center">
                    <button
                        @click="handleResetRequest"
                        :disabled="loading"
                        :class="{'opacity-50 cursor-not-allowed': loading}"
                        class="bg-orange text-white font-semibold text-md px-12 py-2 rounded-full hover:bg-zinc-800 transition-all duration-300">
                        {{ loading ? 'Sending...' : 'Send Reset Link' }}
                    </button>
                </div>

                <div class="flex justify-center mt-4">
                    <router-link
                        to="/login"
                        class="text-orange text-sm hover:text-zinc-800">
                        Back to Login
                    </router-link>
                </div>
            </div>

            <Alert :message="alertMessage" :type="alertType" v-if="alertMessage" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import Alert from '../AlertComponent.vue';

const email = ref('');
const loading = ref(false);
const alertMessage = ref('');
const alertType = ref('info');

const store = useStore();
const router = useRouter();

const validateEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

const handleResetRequest = async () => {
    if (!email.value) {
        alertMessage.value = 'Please enter your email address';
        alertType.value = 'error';
        return;
    }

    if (!validateEmail(email.value)) {
        alertMessage.value = 'Please enter a valid email address';
        alertType.value = 'error';
        return;
    }

    try {
        loading.value = true;
        await store.dispatch('forgotPassword', email.value);

        alertMessage.value = 'Password reset link has been sent to your email';
        alertType.value = 'success';

        // Clear form
        email.value = '';

        // Redirect to login after 3 seconds
        setTimeout(() => {
            router.push('/login');
        }, 3000);
    } catch (error) {
        console.error('Password reset request error:', error);
        alertMessage.value = error.response?.data?.message || 'An error occurred. Please try again.';
        alertType.value = 'error';
    } finally {
        loading.value = false;
    }
};
</script>
