<template>
    <div class="flex justify-center mt-14">
        <div class="w-4/12 bg-white rounded-md">
            <h2 class="text-lg text-zinc-600 font-semibold mt-8 px-6">Start with creating your account <router-link to="/signup" class="text-orange font-semibold hover:text-orange-500">OR LOGIN</router-link>

            </h2>
            <div class="pl-6">
                <hr class="h-px w-full bg-orange  border-0 mt-1 mb-8">

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

                    <input type="password" v-model="password"
                           class=" block w-full p-2.5 bg-zinc-50 w-full rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                           placeholder="password">
                </div>
            </div>


            <div class="flex justify-end border-t"
                 :class="hovered ? 'border-dark' : 'border-orange'">
                <form @mouseover="hovered = !hovered"
                      @mouseleave="hovered = !hovered"
                      class="uppercase text-sm cursor-pointer inline-block font-semibold bg-orange text-white hover:bg-dark px-4 py-2 rounded-br-md"
                      @click="handleSignup">
                    signup
                </form>

            </div>


        </div>

    </div>
</template>

<script setup>
import {ref, defineEmits} from 'vue';
import axios from 'axios';
import {useRouter} from "vue-router";
import {useStore} from "vuex";

const email = ref('');
const profileType = ref('Applicant');
const name = ref('');
const password = ref('');
const hovered = ref(false);
const router = useRouter();
const store = useStore();
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
        store.commit('setUser',response.data.user);

        if (response.status === 201) {
            router.push('/');

        } else {
            console.error('Signup failed:', response.data);
        }
    } catch (error) {
        console.error('Signup error:', error.response.data);
    }
};
</script>
