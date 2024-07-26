<template>
    <div class="flex justify-end">
        <div class="w-full md:w-10/12 pl-4 md:pl-0">
            <div class="border-[1px] border-orange  md:px-0 px-4 pt-24 md:pt-24 pb-40 mt-10 md:mt-6 w-full rounded-l-[4rem] md:rounded-l-full">
                <div class="flex justify-center items-center">
                    <div class="w-full md:w-10/12">
                        <div class="w-full">
                            <h1 class="text-3xl md:text-5xl font-bold">Build Your <span class="text-orange">Job-Ready</span> Resume,</h1>
                            <h1 class="text-3xl md:text-5xl font-bold">In Minutes.</h1>
                        </div>
                        <div class="flex justify-end mt-6 md:mt-0 md:-mt-2">
                            <div class="w-full md:w-4/12">
                                <router-link :to="`/profile/${user?.applicant?.id}`"
                                    class="flex justify-center font-semibold w-full text-zinc-800 hover:text-orange border-b-[2px] border-r-[2px] hover:border-orange border-zinc-800 bg-orange hover:bg-dark md:py-3 py-2 text-center font-semibold tracking-wider text-xl md:text-2xl rounded-full outline-none focus:outline-none">
                                  YOUR <span class="text-white ml-2">RESUME</span>
                                </router-link>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useStore} from "vuex";
import {getSelectables} from "../../../js/utils/storeHelpers.js";
const store = useStore();
const userName = computed(() => {
    return store.getters.user?.name;
})
const user = computed(() => {
    return store.getters.user;
})

const specialities = ref([])

onMounted(async () => {
    axios.get('').then(async res => {
        specialities.value = await getSelectables('specialities');

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
