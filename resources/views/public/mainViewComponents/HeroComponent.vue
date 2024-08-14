<template>
    <div class="flex justify-end">
        <div class="w-full md:w-10/12 pl-4 md:pl-0">
            <div class="border-[1px] border-orange md:px-0 px-6 pt-24 md:pt-24 pb-40 mt-10 md:mt-6 w-full rounded-l-[4rem] md:rounded-l-full">
                <div class="flex justify-center items-center">
                    <div class="w-full md:w-10/12">
                        <div class="w-full">
                            <h1 class="text-3xl md:text-5xl font-bold">Build Your <span class="text-orange">Job-Ready</span> Resume,</h1>
                            <h1 class="text-3xl md:text-5xl font-bold">In Minutes.</h1>
                        </div>
                        <div class="flex justify-end mt-14 md:-mt-2">
                            <div class="w-full md:w-4/12 group">
                                <router-link :to="user ? `/profile/${user?.applicant?.id}` : '/login'"
                                             class="flex justify-center font-semibold w-full text-white hover:text-orange border-b-[4px] border-r-[4px] hover:border-orange border-dark bg-orange hover:bg-dark md:py-3 py-2 text-center font-semibold tracking-wider text-xl md:text-2xl rounded-full outline-none focus:outline-none">
                                    {{ buttonText }} <span class="text-dark ml-2 group-hover:text-white">RESUME</span>
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
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { getSelectables } from "../../../js/utils/storeHelpers.js";

const store = useStore();
const userName = computed(() => store.getters.user?.name);
const user = computed(() => store.getters.user);

const specialities = ref([]);

const buttonText = computed(() => {
    return user.value ? "VISIT YOUR" : "CREATE YOUR";
});

onMounted(async () => {
    try {
        specialities.value = await getSelectables('specialities');
    } catch (error) {
        console.error('Failed to fetch select options:', error);
    }
});
</script>

<style>
.bg-lines {
    background-image: url('../../../../public/svgs/lines.svg');
}
</style>
