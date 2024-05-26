<template>
    <div class="bg-zinc-800 py-20 text-white px-32">
        <div class="flex ">
            <div class="mb-16 w-6/12">
                <h1 class="text-3xl font-bold">Find all of applicants resumes</h1>
                <h1 class="text-3xl font-bold">right here.</h1>
            </div>
            <div class="mt-10 -ml-10 w-10/12 flex items-center space-x-6">
                <div class="w-full">
                    <div class="relative group w-full">
                        <svg
                            class="absolute rotate-90 group-hover:fill-orange top-0 mt-[11px] ml-4 w-6 h-6 fill-zinc-400"
                            viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" id="fi_2811806">
                            <g id="_x32_-Magnifying_Glass">
                                <path
                                    d="m40.8994141 39.4853516-7.8127441-7.8127441c2.3978882-2.734375 3.7209473-6.1942749 3.7209473-9.8649902 0-4.0068359-1.5605469-7.7734375-4.3935547-10.6064453s-6.5996094-4.3935547-10.6064453-4.3935547-7.7734375 1.5605469-10.6064453 4.3935547-4.3935547 6.5996094-4.3935547 10.6064453 1.5605469 7.7734375 4.3935547 10.6064453 6.5996094 4.3935547 10.6064453 4.3935547c3.6707153 0 7.1306152-1.3230591 9.8649902-3.7209473l7.8127441 7.8127441c.1953125.1953125.4511719.2929688.7070313.2929688s.5117188-.0976563.7070313-.2929688c.3906249-.390625.3906249-1.0234375-.0000001-1.4140625zm-28.2841797-8.4853516c-2.4550781-2.4555664-3.8076172-5.7202148-3.8076172-9.1923828s1.3525391-6.7368164 3.8076172-9.1923828c2.4555664-2.4550781 5.7202148-3.8076172 9.1923828-3.8076172s6.7368164 1.3525391 9.1923828 3.8076172c2.4550781 2.4555664 3.8076172 5.7202148 3.8076172 9.1923828s-1.3525391 6.7368164-3.8076172 9.1923828c-2.4555664 2.4550781-5.7202148 3.8076172-9.1923828 3.8076172s-6.7368164-1.3525391-9.1923828-3.8076172z"></path>
                            </g>
                        </svg>
                        <input placeholder="Start looking for what you need..."
                               class="w-full text-dark text-sm focus:border-orange py-3 px-16 focus:ring-0 bg-white w-full rounded-full border-0 border-[1px] border-zinc-200 group-hover:border-orange focus:outline-none"
                               type="text">
                    </div>

                </div>
                <div class="">
                    <button @click="showAdvanceSearch = !showAdvanceSearch">
                        <svg
                            class="bg-white w-11 h-11 mt-1 hover:fill-orange border-0 border-zinc-200 border-[1px] border-transparent hover:border-orange rounded-full px-2 py-1 fill-zinc-600"
                            id="fi_3024539" viewBox="0 0 189.524 189.524"
                            xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path clip-rule="evenodd"
                                          d="m56.26 52.119c-2.104 8.174-9.524 14.214-18.355 14.214-10.467 0-18.952-8.485-18.952-18.952s8.485-18.952 18.952-18.952c8.831 0 16.251 6.04 18.355 14.214h128.526c2.616 0 4.738 2.121 4.738 4.738s-2.122 4.738-4.738 4.738zm-8.879-4.738c0 5.234-4.243 9.476-9.476 9.476s-9.476-4.243-9.476-9.476 4.243-9.476 9.476-9.476 9.476 4.242 9.476 9.476z"
                                          fill-rule="evenodd"></path>
                                </g>
                                <g>
                                    <path
                                        d="m4.738 52.119h14.811c-.39-1.514-.597-3.102-.597-4.738s.207-3.224.597-4.738h-14.811c-2.617 0-4.738 2.121-4.738 4.738s2.121 4.738 4.738 4.738z"></path>
                                </g>
                                <g>
                                    <path clip-rule="evenodd"
                                          d="m113.117 137.405c-2.104-8.174-9.525-14.214-18.355-14.214s-16.252 6.04-18.355 14.214h-71.669c-2.617 0-4.738 2.122-4.738 4.738s2.121 4.738 4.738 4.738h71.668c2.104 8.174 9.525 14.214 18.355 14.214s16.252-6.04 18.355-14.214h71.668c2.616 0 4.738-2.122 4.738-4.738s-2.122-4.738-4.738-4.738zm-18.355 14.214c5.234 0 9.476-4.242 9.476-9.476s-4.242-9.476-9.476-9.476-9.476 4.242-9.476 9.476 4.242 9.476 9.476 9.476z"
                                          fill-rule="evenodd"></path>
                                </g>
                                <g>
                                    <path clip-rule="evenodd"
                                          d="m169.974 90.024c-2.104-8.174-9.525-14.214-18.355-14.214s-16.252 6.04-18.355 14.214h-128.526c-2.617 0-4.738 2.122-4.738 4.738s2.121 4.738 4.738 4.738h128.526c2.104 8.174 9.525 14.214 18.355 14.214s16.252-6.04 18.355-14.214h14.811c2.616 0 4.738-2.122 4.738-4.738s-2.122-4.738-4.738-4.738zm-18.355 14.214c5.234 0 9.476-4.242 9.476-9.476s-4.243-9.476-9.476-9.476c-5.234 0-9.476 4.242-9.476 9.476s4.242 9.476 9.476 9.476z"
                                          fill-rule="evenodd"></path>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showAdvanceSearch" class="bg-white text-dark rounded-md p-4 mt-10">
            <h1 class="mb-6 font-semibold text-orange">Advance search</h1>
            <div class="grid grid-cols-2 gap-4 ">
                <div class="flex">
                    <select v-model="city" :value="city"
                            :class="city == 'Baghdad' ?'rounded-l-md' :'rounded'"
                            class="h-[37.1px] text-xs focus:border-orange focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                        <option value="" class="hidden" selected>Choose your city...</option>
                        <option v-for="city in cities">{{ city }}</option>

                    </select>

                    <select v-if="city == 'Baghdad'" v-model="zone" :value="zone" @change="emitInputData"
                            class="h-[37.1px] text-xs focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                        <option value="none" selected>Choose your zone...</option>
                        <option>Karkh</option>
                        <option>Risafa</option>
                    </select>

                </div>
                <select v-model="gender"
                        class="h-[37.1px] focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option selected>Female</option>
                    <option>Male</option>
                </select>
                <select v-model="degree"
                        class="focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option value="" class="hidden" selected>Choose your degree...</option>
                    <option v-for="option in degrees">{{ option }}</option>
                </select>
                <div class="relative w-full">
                    <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                    <select v-model="gender"
                            class="h-[37.1px] focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                        <option selected>Female</option>
                        <option>Male</option>
                    </select>
                    <!--<h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

                </div>

            </div>

        </div>

    </div>

</template>

<script setup>

import {onMounted, ref} from "vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";

const showAdvanceSearch = ref(false)
const gender = ref('Female');

const degrees = ref([])
const degree = ref('')

const cities = ref([])
const city = ref('');

onMounted(async () => {
    axios.get('/api/selectables/degrees').then(async res => {
        degrees.value = await getSelectables('degrees');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });

    axios.get('/api/selectables/cities').then(async res => {
        cities.value = await getSelectables('cities');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);
    });

});


</script>

<style>
.bg-lines {
    background-image: url('../../../../public/svgs/lines.svg');
}
</style>
