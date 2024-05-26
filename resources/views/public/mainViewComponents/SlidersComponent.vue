<template>
    <div class="mt-32">
        <div class="mx-32">
            <h1 class="text-end text-3xl font-bold">All applicants based</h1>
            <h1 class="text-end text-3xl font-bold">on their specializations</h1>
        </div>
        <div v-for="speci in specialities" class="mb-24">

            <div class="mb-10">
                <h1 class="px-32 py-1 my-2 font-bold tracking-wider text-2xl uppercase">
                    {{ speci.title }}
                </h1>
                <div class="mt-6">
                    <div class="-mt-4 h-5 w-2/12 rounded-tr-2xl bg-orange"></div>
                    <div class="-mt-3 ml-8 h-3 w-3/12 rounded-t-2xl bg-dark"></div>
                </div>
            </div>


            <div class="flex flex-shrink-0 overflow-y space-x-6 px-0 mx-0 pt-6 pb-4">
                <div v-for="item in 6" :class="item === 1 ? 'rounded-r-lg' : 'rounded-lg'"
                     class="relative bg-white h-80 rounded-r-lg w-72x p-4">
                    <div :class="1 == 1? 'bg-orange text-white':'bg-gray-400 text-gray-600'"
                         class="rounded-l-full py-1 px-3 bg-orange text-white font-semibold text-xs absolute top-0 right-0 mt-3">
                        Available for work
                    </div>
                    <div class="flex items-center space-x-4 mt-8">
                        <div class="bg-gray-300 w-24 h-16 rounded-md"></div>
                        <div class="font-semibold text-xl">
                            Full name
                        </div>
                    </div>
                    <div class="mt-3">
                        <h1 class="text-xs text-gray-800 font-semibold mb-4">email@email.com</h1>
                        <div class="flex space-x-2 mt-1">
                            <span class="bg-dark text-xs text-white font-semibold py-1 px-2 rounded-full">Laravel</span>
                            <span class="bg-dark text-xs text-white font-semibold py-1 px-2 rounded-full">Sketch</span>
                        </div>

                        <p class="text-xs mt-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur at
                            ducimus,
                            fugiat harum in
                            ...</p>
                    </div>


                </div>
            </div>
        </div>

    </div>

</template>

<script setup>
import {onMounted, ref} from "vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";

const specialities = ref([])

onMounted(async () => {
    axios.get('').then(async res => {
        specialities.value = await getSelectables('specialities');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });

});
</script>

<style scoped>

</style>
