<template>
    <div class="flex justify-center mt-24">
        <div class="w-full mx-4 md:w-4/12 bg-white rounded-md">
            <h2 class="text-lg text-zinc-600 font-semibold mt-8 px-6">Login to your account <router-link to="/signup" class="text-orange font-semibold hover:text-orange-500">OR SIGN UP</router-link>
            </h2>
            <div class="pl-6">
                <hr class="h-px w-full bg-orange  border-0 mt-1 mb-8">
            </div>

            <div class="px-6 space-y-4 my-10">
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Email Address</h1>
                    <input type="text" v-model="email"
                           class="block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="name@flowbite.com">
                </div>
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-sm text-zinc-500 font-semibold tracking-wider">Password</h1>
                    <input type="password" v-model="password"
                           class="block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="password">
                </div>
            </div>

            <div class="flex justify-end border-t"
                 :class="hovered ? 'border-dark' : 'border-orange'">
                <form @mouseover="hovered = !hovered"
                      @mouseleave="hovered = !hovered"
                      class="uppercase text-sm cursor-pointer inline-block font-semibold bg-orange text-white hover:bg-dark px-4 py-2 rounded-br-md"
                      @click.prevent="handleLogin">
                    login
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
import Alert from '../AlertComponent.vue';  // Make sure to create this file in the same directory

const email = ref('');
const password = ref('');
const hovered = ref(false);
const alertMessage = ref('');
const alertType = ref('info');

const router = useRouter();
const store = useStore();

const handleLogin = async () => {
    if (!email.value || !password.value) {
        alertMessage.value = 'Email and password are required!';
        alertType.value = 'error';
        return;
    }
    try {
        const { data } = await axios.post('/login', {
            email: email.value,
            password: password.value,
        });
        store.commit('setUser', data.user);
        alertMessage.value = 'Login successful!';
        alertType.value = 'success';
        await router.push('/');
    } catch (error) {
        console.error('Login error:', error.response.data);
        alertMessage.value = error.response.data.message || 'Login failed. Please try again.';
        alertType.value = 'error';
    } finally {
        email.value = '';
        password.value = '';
    }
};
</script>
