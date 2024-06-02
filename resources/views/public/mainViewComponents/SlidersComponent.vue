<template>
    <div class="mt-40">
        <div v-for="speci in specialities" class="mb-16">
            <div class="w-3/12">
                <div class="mb-10 w-full">
                    <h1 class="w-full text-end py-1 my-2 font-bold tracking-wider text-3xl uppercase">
                        {{ speci.title == 'Other' ? 'Other Specialization' : speci.title }}
                    </h1>
                    <div class="mt-6 w-full">
                        <div class="-mt-4 h-5 w-11/12 rounded-br-2xl bg-orange"></div>
                        <div class="-mt-3 ml-8 h-3 w-full rounded-br-2xl bg-dark"></div>
                    </div>
                </div>

            </div>
            <div class="w-full">
                <div class="overflow-x-auto whitespace-nowrap space-x-6 px-0 mx-0 pt-6 pb-4 flex w-full">
                    <div v-for="item in 6" :key="item"
                         :class="item === 1 ? 'rounded-r-lg' : 'rounded-lg'"
                         class="relative bg-white w-64 p-4 inline-block">
                        <a href="/resume/1">
                            <div :class="1 == 1 ? 'bg-orange text-white' : 'bg-gray-400 text-gray-600'"
                                 class="rounded-full py-1 px-3 font-semibold text-sm absolute top-0 right-0 -mt-4">
                                Available for work
                            </div>
                            <div class="flex justify-center items-center space-x-4 mt-3">
                                <div class="border-[1px] border-orange p-1 rounded-full">
                                    <div class="bg-gray-300 w-24 h-24 rounded-full"></div>

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
                                <p class="text-sm mt-4 text-wrap">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Aspernatur
                                    at
                                    ducimus, fugiat harum in ...</p>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
            <!--            <div class="overflow-x space-x-6 px-0 mx-0 pt-6 pb-4">-->
            <!--                <div v-for="item in 6" :class="item === 1 ? 'rounded-r-lg' : 'rounded-lg'"-->
            <!--                     class="relative bg-white h-80 rounded-r-lg w-72 p-4">-->
            <!--                    <div :class="1 == 1? 'bg-orange text-white':'bg-gray-400 text-gray-600'"-->
            <!--                         class="rounded-l-full py-1 px-3 bg-orange text-white font-semibold text-sm absolute top-0 right-0 mt-3">-->
            <!--                        Available for work-->
            <!--                    </div>-->
            <!--                    <div class="flex items-center space-x-4 mt-8">-->
            <!--                        <div class="bg-gray-300 w-24 h-16 rounded-md"></div>-->
            <!--                        <div class="font-semibold text-xl">-->
            <!--                            Full name-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                    <div class="mt-3">-->
            <!--                        <h1 class="text-sm text-gray-800 font-semibold mb-4">email@email.com</h1>-->
            <!--                        <div class="flex space-x-2 mt-1">-->
            <!--                            <span class="bg-dark text-sm text-white font-semibold py-1 px-2 rounded-full">Laravel</span>-->
            <!--                            <span class="bg-dark text-sm text-white font-semibold py-1 px-2 rounded-full">Sketch</span>-->
            <!--                        </div>-->

            <!--                        <p class="text-sm mt-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur at-->
            <!--                            ducimus,-->
            <!--                            fugiat harum in-->
            <!--                            ...</p>-->
            <!--                    </div>-->


            <!--                </div>-->
            <!--            </div>-->
        </div>

    </div>

</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";
import {useRoute} from "vue-router";


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
