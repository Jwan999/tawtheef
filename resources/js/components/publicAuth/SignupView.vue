<template>
    <div class="flex justify-center mt-24">
        <div class="w-4/12  bg-white rounded-md">
            <h2 class="text-lg text-orange font-semibold mt-8 px-6">Start with creating your account</h2>
            <div class="pl-6">
                <hr class="h-px w-full bg-orange  border-0 mt-1 mb-8">

            </div>

            <div class="px-6 space-y-4 my-10">
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-xs text-zinc-500 font-semibold tracking-wider">Full Name</h1>
                    <input type="text" v-model="name"
                           class="block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="Jane Doe">
                </div>
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-xs text-zinc-500 font-semibold tracking-wider">Pick your profile type</h1>
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
                    <h1 class="text-xs text-zinc-500 font-semibold tracking-wider">Email Address</h1>

                    <input type="text" v-model="email"
                           class=" block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="name@flowbite.com">
                </div>
                <div class="form-group flex flex-col space-y-3">
                    <h1 class="text-xs text-zinc-500 font-semibold tracking-wider">Password</h1>

                    <input type="password" v-model="password"
                           class=" block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="password">
                </div>
            </div>


            <div class="flex justify-end border-t"
                 :class="hovered ? 'border-dark' : 'border-orange'">
                <form @mouseover="hovered = !hovered"
                      @mouseleave="hovered = !hovered"
                      class="uppercase text-xs cursor-pointer inline-block font-semibold bg-orange text-white hover:bg-dark px-4 py-2"
                      @click="handleSignup">
                    signup
                </form>
                <router-link to="/login" class="uppercase text-xs cursor-pointer inline-block font-semibold text-zinc-700 hover:text-white 0 hover:bg-dark px-4 py-2 rounded-br-md">
                    Or Login
                </router-link>
            </div>


        </div>

    </div>
</template>

<script setup>
import {ref, defineEmits} from 'vue';
import axios from 'axios'; // Assuming Axios is installed

const email = ref('');
const profileType = ref('Applicant');
const name = ref('');
const password = ref('');
const hovered = ref(false);


const handleSignup = async () => {
    // Validate email and password
    if (!email.value || !password.value || !profileType.value || !name.value) {
        console.error('Fill out the empty fields!');
        return;
    }

    try {
        const response = await axios.post('/signup', {
            email: email.value,
            profileType: profileType.value,
            name: name.value,
            password: password.value,
        });

        if (response.status === 200) {
            // Signup successful!
            console.log('Signup successful:', response.data);
            // Handle successful Signup (e.g., redirect, store user data)
        } else {
            console.error('Signup failed:', response.data);
            // Handle Signup errors (e.g., display error messages)
        }
    } catch (error) {
        console.error('Signup error:', error.response.data);
    }
};
</script>
