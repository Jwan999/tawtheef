<template>

    <div class="flex flex-col tracking-wider space-y-10 my-32 mb-44">
        <div class="w-full px-32 flex">
            <div class="w-8/12">
                <h1 class="text-5xl font-bold">Build your <span class="text-orange">Job-Ready</span> resume,</h1>
                <h1 class="text-5xl font-bold">in minutes.</h1>
            </div>
            <div class="w-6/12 -ml-32 mt-32">
                <p class="text-xl mb-8">Join thousands of professionals who have landed their dream jobs with our
                    easy-to-use resume builder.</p>
            </div>
        </div>

        <div class="flex justify-end w-full">
            <button
                class="bg-orange hover:bg-dark py-3 px-16 text-start w-8/12 font-semibold tracking-wider text-white text-2xl rounded-l-full outline-none focus:outline-none">
                Go to your resume now
            </button>
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
