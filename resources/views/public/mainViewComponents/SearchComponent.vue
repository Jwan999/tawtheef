<template>

    <div class="flex justify-start -mt-32">
        <div :class="showAdvanceSearch ? 'pt-28 pb-10' : 'py-28'"
             class="w-10/12 bg-zinc-800 text-white rounded-r-full">
            <div class="flex justify-center items-center w-full">
                <div class="w-10/12">
                    <div class="w-8/12 flex flex-wrap">
                        <h1 class="text-5xl font-bold">Discover Exceptional Talent or Inspiring Resumes <span
                            class="text-orange">Right Here</span></h1>
                        <!--                        <h1 class="text-5xl font-bold text-orange">right here.</h1>-->
                    </div>
                    <div class="flex justify-start mt-14">
                        <div class="w-7/12 flex items-center space-x-4">
                            <div class="w-full">
                                <div class="relative group w-full">
                                    <svg
                                        class="absolute rotate-90 group-hover:fill-orange top-0 mt-[13px] ml-4 w-6 h-6 fill-zinc-400"
                                        viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" id="fi_2811806">
                                        <g id="_x32_-Magnifying_Glass">
                                            <path
                                                d="m40.8994141 39.4853516-7.8127441-7.8127441c2.3978882-2.734375 3.7209473-6.1942749 3.7209473-9.8649902 0-4.0068359-1.5605469-7.7734375-4.3935547-10.6064453s-6.5996094-4.3935547-10.6064453-4.3935547-7.7734375 1.5605469-10.6064453 4.3935547-4.3935547 6.5996094-4.3935547 10.6064453 1.5605469 7.7734375 4.3935547 10.6064453 6.5996094 4.3935547 10.6064453 4.3935547c3.6707153 0 7.1306152-1.3230591 9.8649902-3.7209473l7.8127441 7.8127441c.1953125.1953125.4511719.2929688.7070313.2929688s.5117188-.0976563.7070313-.2929688c.3906249-.390625.3906249-1.0234375-.0000001-1.4140625zm-28.2841797-8.4853516c-2.4550781-2.4555664-3.8076172-5.7202148-3.8076172-9.1923828s1.3525391-6.7368164 3.8076172-9.1923828c2.4555664-2.4550781 5.7202148-3.8076172 9.1923828-3.8076172s6.7368164 1.3525391 9.1923828 3.8076172c2.4550781 2.4555664 3.8076172 5.7202148 3.8076172 9.1923828s-1.3525391 6.7368164-3.8076172 9.1923828c-2.4555664 2.4550781-5.7202148 3.8076172-9.1923828 3.8076172s-6.7368164-1.3525391-9.1923828-3.8076172z"></path>
                                        </g>
                                    </svg>
                                    <input placeholder="Looking for blah or blah or blah?"
                                           class="w-full text-dark text-base focus:border-orange py-3 px-16 focus:ring-0 bg-white w-full rounded-full border-0 border-[1px] border-zinc-200 group-hover:border-orange focus:outline-none"
                                           type="text">
                                </div>

                            </div>
                            <div>
                                <button @click="toggleSearchMode">
                                    <svg
                                        class="bg-white w-[45px] h-[45px] mt-1 hover:fill-orange border-0 border-zinc-200 border-[1px] border-transparent hover:border-orange rounded-full px-2 py-1 fill-zinc-600"
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
                    <h1 v-if="showAdvanceSearch"
                        class="mt-10 font-semibold text-4xl capitalize tracking-wider text-white">Advance search</h1>

                </div>

            </div>

        </div>

    </div>
    <transition name="slide-down">
        <div id="advanceSearch" v-if="showAdvanceSearch" class="relative w-8/12">
            <div
                class="advance-search bg-zinc-800 rounded-br-[7rem] pl-28 pb-10 border-[4px] border-t-0 border-orange rounded-rt-[5rem] z-40">
                <div class="flex justify-end w-full items-center px-32">
                    <div class="hover:text-white text-orange cursor-pointer font-semibold">
                        <button>CLEAR ALL</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 pr-32">

                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold capitalize">
                            Gender</h1>
                        <span @click="toggleGender()"
                              :class="gender?'bg-orange text-white':'bg-zinc-200 text-zinc-600'"
                              class="font-semibold hover:bg-dark hover:text-white text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                         Female
                    </span>
                        <span @click="toggleGender()"
                              :class="!gender?'bg-orange text-white':'bg-zinc-200 text-zinc-600'"
                              class="font-semibold hover:bg-dark hover:text-white text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                      Male
                    </span>
                    </div>
                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold capitalize">
                            Based on
                            age</h1>
                        <div class="relative mb-6">
                            <label for="labels-range-input" class="sr-only">Labels range</label>
                            <input id="labels-range-input" type="range" value="1000" min="100" max="1500"
                                   class="progress w-full accent-orange h-2 bg-zinc-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                            <span class="text-sm text-zinc-200 dark:text-gray-400 absolute start-0 -bottom-6">Min (18 Year Old)</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-1/3 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6">25 Year Old</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-2/3 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6">35 Year Old</span>
                            <span class="text-sm text-zinc-200 dark:text-gray-400 absolute end-0 -bottom-6">Max (45 Year Old)</span>
                        </div>
                    </div>
                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold capitalize">
                            Location</h1>
                        <div class="flex space">
                            <select v-model="city"
                                    :class="city == 'Baghdad' ?'rounded-l-md' :'rounded-md'"
                                    class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-l-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                <!--                                <option value="" class="hidden" selected>Choose your city...</option>-->
                                <option v-for="city in cities">{{ city }}</option>
                            </select>

                            <select v-if="city == 'Baghdad'" v-model="zone"
                                    class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-r-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                                <!--                                <option value="Choose your zone..." class="hidden">Choose your zone...</option>-->
                                <option>Karkh</option>
                                <option>Risafa</option>
                            </select>

                        </div>
                    </div>

                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold">
                            Educational
                            degree</h1>
                        <select v-model="degree"
                                class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                            <option value="" class="hidden" selected>Choose a degree...</option>
                            <option v-for="option in degrees">{{ option }}</option>
                        </select>
                    </div>
                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold capitalize">
                            Based on
                            graduation year</h1>
                        <div class="relative mb-6">
                            <label for="labels-range-input" class="sr-only">Labels range</label>
                            <input id="labels-range-input" type="range" value="1000" min="100" max="1500"
                                   class="progress w-full accent-orange h-2 bg-zinc-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-0 -bottom-6">Min (1 Year)</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-1/3 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6">3 Years</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-2/3 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6">6 Years</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute end-0 -bottom-6">Max (10 Years)</span>
                        </div>
                    </div>


                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold capitalize">
                            Work
                            availability</h1>
                        <span @click="toggleWorkAvailability"
                              :class="workAvailability?'bg-orange text-white':'bg-zinc-200 text-zinc-600'"
                              class="font-semibold hover:bg-dark hover:text-white text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                          Available for Work
                    </span>
                        <span @click="toggleWorkAvailability"
                              :class="!workAvailability?'bg-orange text-white':'bg-zinc-200 text-zinc-600'"
                              class="font-semibold hover:bg-dark hover:text-white  text-base font-medium me-2 px-2.5 py-0.5 rounded-full">
                     Not Available for work
                    </span>
                    </div>
                    <div class="group cursor-pointer">
                        <input
                            :id="'checkbox-' + 'index'"
                            type="checkbox"
                            :value="freshGraduate"
                            :checked="freshGraduate?.includes('item')"
                            @click="freshGraduate"
                            class="text-sm w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600"
                        />
                        <label :for="'checkbox-' + 'index'" class="text-lg mb-2 font-medium text-white">
                            Fresh Graduates
                        </label>
                    </div>
                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold capitalize">
                            Based on
                            work experience</h1>
                        <div class="relative mb-6">
                            <label for="labels-range-input" class="sr-only">Labels range</label>
                            <input id="labels-range-input" type="range" value="1000" min="100" max="1500"
                                   class="progress w-full accent-orange h-2 bg-zinc-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-0 -bottom-6">Min (1 Year)</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-1/3 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6">3 Years</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute start-2/3 -translate-x-1/2 rtl:translate-x-1/2 -bottom-6">6 Years</span>
                            <span
                                class="text-sm text-zinc-200 dark:text-gray-400 absolute end-0 -bottom-6">Max (10 Years)</span>
                        </div>
                    </div>

                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold capitalize">
                            Tools
                            and Technologies</h1>
                        <input v-model="tool" placeholder="Tool or Technology" type="text"
                               class="h-10 px-3 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none"/>
                    </div>
                    <div class="group cursor-pointer">
                        <h1 class="text-xl text-white group-hover:text-orange mb-2 tracking-wider font-semibold">Main
                            Specialization</h1>
                        <select v-model="mainSpecialization"
                                class="h-10 focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md md:text-sm text-sm border-0 border-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                            <option selected>Female</option>
                            <option>Male</option>
                        </select>
                        <div class="flex space-x-2 items-center mt-6">
                            <input
                                :id="`subSpeciality-${'index'}`"
                                type="checkbox"
                                :value="subSpeciality"
                                @change="toggleSubSpeciality(subSpeciality)"
                                class="w-4 h-4 text-orange bg-white border-zinc-400 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-2 dark:bg-zinc-700 dark:border-zinc-600"
                            />
                            <label :for="`subSpeciality-${'index'}`"
                                   class="ms-2 text-base font-medium text-white">
                                {{ subSpeciality }}.testing
                            </label>
                        </div>
                        <button
                            class="capitalize mt-3 flex-none w-auto appearance-none px-8 py-1.5 rounded-bl-md font-semibold text-orange bg-zinc-100 hover:bg-orange hover:text-white">
                            add component
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </transition>

</template>

<script setup>

import {computed, onMounted, ref} from "vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";
import store from "../../../js/store/index.js";

const showAdvanceSearch = ref(false)
const gender = ref(true);

const degrees = ref([])
const degree = ref('')

const subSpeciality = ref([])
const toggleSubSpeciality = (test) => {
    console.log(test)
}
const toggleSearchMode = () => {
    searchMode.value = !searchMode.value;
    showAdvanceSearch.value = !showAdvanceSearch.value
};

const searchMode = computed({
    get() {
        return store.getters.searchMode;
    },
    set() {
        store.dispatch('setSearchMode', !searchMode)
    }
})

const tool = ref('')

const workAvailability = ref(true)

const mainSpecialization = ref('')

const freshGraduate = ref('')

const cities = ref([])

const city = ref('Choose a city...');
const zone = ref('Choose a zone...');

const toggleGender = () => {
    gender.value = !gender.value
}

const toggleWorkAvailability = () => {
    workAvailability.value = !workAvailability.value
}
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

input[type=range]::-moz-range-thumb {
    background: #f97316;
}

input[type="range"]::-moz-range-progress {
    background-color: #f97316;
}

.slide-down-enter-active, .slide-down-leave-active {
    transition: all 200ms cubic-bezier(0.17, 0.04, 0.03, 0.94);
}

.slide-down-enter-from, .slide-down-leave-to {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
}

.slide-down-enter-to, .slide-down-leave-from {
    max-height: 1000px; /* Adjust this value as needed */
    opacity: 1;
    overflow: hidden;
}

</style>
