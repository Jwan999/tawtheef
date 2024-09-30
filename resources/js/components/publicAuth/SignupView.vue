<template>
    <div class="flex justify-center my-14">
        <div class="w-full mx-4 md:w-4/12 bg-white rounded-md">
            <div class="flex justify-between items-center mt-8 px-6">
                <h2 class="text-lg text-zinc-600 font-semibold">Start with creating your account</h2>
            </div>
            <div class="pl-6">
                <hr class="h-0.5 w-full bg-orange  border-0 mt-2 mb-8">
            </div>

            <div class="px-6 space-y-6 my-10">
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Full Name</h1>
                    <input type="text" v-model="name"
                           class="block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="Jane Doe">
                </div>
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Pick your profile type</h1>
                    <div class="flex space-x-2">
                        <button @click="profileType = 'Applicant'"
                                @mouseover="profileType = 'Applicant'"
                                :class="profileType === 'Applicant' ?'bg-dark text-white':'bg-zinc-50 text-orange'"
                                class="rounded-b-md rounded-tl-md w-full  text-sm font-semibold tracking-wider hover:bg-dark hover:text-white py-2">
                            Applicant
                        </button>
                        <button @click="profileType = 'Company'"
                                @mouseover="profileType = 'Company'"
                                :class="profileType === 'Company' ?'bg-dark text-white':'bg-zinc-50 text-orange'"
                                class="rounded-t-md rounded-br-md w-full  text-sm font-semibold tracking-wider hover:bg-dark hover:text-white py-2">
                            Company
                        </button>
                    </div>
                </div>
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Email Address</h1>
                    <input type="text" v-model="email"
                           class=" block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="name@flowbite.com">
                </div>
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Password</h1>
                    <PasswordInput ref="passwordInput" />
                </div>
            </div>

            <div class="flex justify-end space-x-6 items-center pt-2 p-6">
                <router-link to="/login" class="text-orange text-lg font-semibold hover:text-zinc-800">or Login</router-link>

                <form
                      class="bg-orange cursor-pointer text-white font-semibold text-md px-12 py-2 shadow-custom-3d rounded-full hover:bg-zinc-800 hover:shadow-none transition-all duration-300 ease-in-out transform hover:scale-105"
                      @click.prevent="handleSignup">
                    Signup
                </form>

            </div>

        </div>
    </div>

    <Alert :message="alertMessage" :type="alertType" />

</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import PasswordInput from "./PasswordInput.vue";
import Alert from '../AlertComponent.vue';  // Make sure to create this file in the same directory

const email = ref('');
const profileType = ref('Applicant');
const name = ref('');
const passwordInput = ref(null);
const hovered = ref(false);
const alertMessage = ref('');
const alertType = ref('info');

const router = useRouter();
const store = useStore();

const handleSignup = async () => {
    // Check if passwordInput ref is properly set
    if (!passwordInput.value) {
        alertMessage.value = 'Password input component not found';
        alertType.value = 'error';
        return;
    }

    // Validate all fields including password
    if (!email.value || !profileType.value || !name.value || !passwordInput.value.isValid) {
        alertMessage.value = 'Please fill out all fields correctly and ensure passwords match';
        alertType.value = 'error';
        return;
    }

    try {
        const response = await axios.post('/signup', {
            email: email.value,
            profileType: profileType.value,
            name: name.value,
            password: passwordInput.value.password, // Use the password from the PasswordInput component
        });

        store.commit('setUser', response.data.user);

        if (response.status === 201) {
            alertMessage.value = 'Sign up successful! Redirecting...';
            alertType.value = 'success';
            setTimeout(() => {
                router.push('/');
            }, 2000);
        } else {
            alertMessage.value = 'Sign up failed. Please try again.';
            alertType.value = 'error';
        }
    } catch (error) {
        console.error('Signup error:', error.response?.data || error.message);
        alertMessage.value = error.response?.data?.message || 'An error occurred during sign up. Please try again.';
        alertType.value = 'error';
    }
};
</script>
