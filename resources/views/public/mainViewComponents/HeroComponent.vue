<template>
    <div class="w-10/12 flex justify-start">
        <div class="border-[1px] border-orange pt-24 pb-40 mt-6 w-full rounded-r-full">
            <div class="flex justify-center items-center">
                <div class="w-10/12">
                    <div class="w-full">
                        <h1 class="text-5xl font-bold">Build Your <span class="text-orange">Job-Ready</span> Resume,</h1>
                        <h1 class="text-5xl font-bold">In Minutes.</h1>
                    </div>
                    <div class="flex justify-end -mt-2">
                        <div class="w-4/12">
                            <button
                                class="w-full bg-orange hover:bg-dark py-3 text-center font-semibold tracking-wider text-white text-2xl rounded-full outline-none focus:outline-none">
                                VISIT YOUR RESUME
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</template>

<script setup>
import {onMounted, ref} from "vue";
import {getAuthUser, getSelectables} from "../../../js/utils/storeHelpers.js";

const userName = ref(null)
onMounted(() => {
    getAuthUser().then(response => {
        userName.value = response.name;
    }).catch(error => {
        console.error('Error fetching user data:', error);
    })
});

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
