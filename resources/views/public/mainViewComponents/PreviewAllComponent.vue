<template>
    <div class="w-3/12 mt-10">
        <div class="mb-10 w-full">
            <h1 class="w-full text-end py-1 my-2 font-bold tracking-wider text-3xl uppercase">
                Search results
            </h1>
            <div class="mt-6 w-full">
                <div class="-mt-4 h-5 w-11/12 rounded-br-2xl bg-orange"></div>
                <div class="-mt-3 ml-8 h-3 w-full rounded-br-2xl bg-dark"></div>
            </div>
        </div>

    </div>
    <div class="mx-10">
        <div class="grid grid-cols-5 justify-items-center gap-6">

            <div v-for="(applicant,index) in applicants" :key="index"
                 @click="goToResume(index)"
                 :class="applicant === 1 ? 'rounded-r-lg' : 'rounded-lg'"
                 class="relative bg-white w-64 p-4 inline-block cursor-pointer mt-10">
                <div>
                    <div :class="1 == 1 ? 'bg-orange text-white' : 'bg-gray-400 text-gray-600'"
                         class="rounded-full py-1 px-3 font-semibold text-sm absolute top-0 right-0 -mt-4">
                        Available for work
                    </div>
                    <div class="flex justify-center items-center space-x-4 mt-3">
                        <div class="border-[1px] border-orange p-1 rounded-tr-3xl rounded-bl-3xl">
                            <div class="bg-gray-300 w-32 h-24 rounded-tr-3xl rounded-bl-3xl"></div>

                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="font-semibold text-xl">
                            Full name
                        </div>
                        <h1 class="text-base text-gray-800 font-semibold mb-4">email@email.com</h1>
                    </div>
                    <div class="mt-3">
                        <div class="flex space-x-2 mt-1">
                                <span
                                    class="bg-dark text-sm text-white font-semibold py-1 px-3 rounded-full">Laravel</span>
                            <span
                                class="bg-dark text-sm text-white font-semibold py-1 px-3 rounded-full">Sketch</span>
                        </div>
                        <p class="text-sm mt-4 text-wrap">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit.
                            Aspernatur
                            at
                            ducimus, fugiat harum in ...</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
</template>

<script setup>
import {ref} from "vue";

const applicants = ref(14)
</script>

<style scoped>

</style>
