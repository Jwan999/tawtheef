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
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">New Password</h1>
                    <input
                        type="password"
                        v-model="password"
                        :disabled="loading"
                        class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                        placeholder="Enter new password">
                </div>

                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Confirm Password</h1>
                    <input
                        type="password"
                        v-model="passwordConfirmation"
                        :disabled="loading"
                        class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                        placeholder="Confirm new password">
                </div>

                <div class="flex justify-center">
                    <button
                        @click="handleReset"
                        :disabled="!isValid || loading"
                        :class="{
                            'opacity-50 cursor-not-allowed': !isValid || loading,
                            'bg-orange hover:bg-zinc-800': isValid && !loading
                        }"
                        class="text-white font-semibold text-md px-12 py-2 rounded-full transition-all duration-300">
                        {{ loading ? 'Resetting...' : 'Reset Password' }}
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
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';
import Alert from '../AlertComponent.vue';

const store = useStore();
const route = useRoute();
const router = useRouter();

const password = ref('');
const passwordConfirmation = ref('');
const loading = ref(false);
const alertMessage = ref('');
const alertType = ref('info');

const isValid = computed(() => {
    return password.value.length >= 8 && password.value === passwordConfirmation.value;
});

const handleReset = async () => {
    if (!isValid.value) {
        alertMessage.value = 'Please ensure your passwords match and are at least 8 characters long';
        alertType.value = 'error';
        return;
    }

    const token = route.params.token;  // Changed from route.query.token
    const email = route.query.email;

    if (!token || !email) {
        alertMessage.value = 'Invalid reset link. Please request a new one.';
        alertType.value = 'error';
        setTimeout(() => router.push('/forgot-password'), 3000);
        return;
    }

    try {
        loading.value = true;
        await store.dispatch('resetPassword', {
            token,
            email,
            password: password.value,
            password_confirmation: passwordConfirmation.value
        });

        alertMessage.value = 'Password reset successfully! Redirecting to login...';
        alertType.value = 'success';

        setTimeout(() => {
            router.push('/login');
        }, 2000);
    } catch (error) {
        console.error('Password reset error:', error);
        alertMessage.value = error.response?.data?.message || 'An error occurred. Please try again.';
        alertType.value = 'error';

        if (error.response?.data?.message?.includes('token')) {
            setTimeout(() => {
                router.push('/forgot-password');
            }, 3000);
        }
    } finally {
        loading.value = false;
    }
};
</script>

