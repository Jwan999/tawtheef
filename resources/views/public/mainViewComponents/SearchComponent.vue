<template>
    <div class="flex justify-between items-center px-20 py-24 mt-16">
        <div class=" w-6/12">
            <h1 class="font-bold text-dark text-3xl mb-3">Hello there</h1>
            <p class="text-sm mt-6">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores nulla numquam
                ratione saepe ut! Ab</p>

            <input type="text"
                   class="mt-4 text-lg  block w-full p-2.5 bg-slate-50 w-full rounded-md md:text-sm text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                   placeholder="Looking for...">
        </div>

        <div class="bg-lines bg-cover w-6/12 grid grid-flow-row-dense justify-items-end gap-3 grid-cols-2 grid-rows-3">
<!--            @click="getApplicantsSpecilities(field.title)"-->
            <button
                    :class="((index+1)*2 /2) % 2 == 0 ? 'place-self-start -mt-[52px] rounded-r-lg rounded-bl-lg':'place-self-end rounded-b-lg rounded-tl-lg'"
                    v-for="(field,index) in specialities"
                    class="cursor-pointer hover:bg-orange  bg-dark text-white flex-none py-2 px-3 inline-block font-semibold">
                {{ field.title }}
            </button>
        </div>


    </div>

</template>

<script setup>
import {onMounted, ref} from "vue";
import {getSelectables} from "../../../js/utils/storeHelpers.js";

const specialities = ref([])
// const getApplicantsSpecilities = (speciality) => async () => {
//     axios.get(`/api/filter/${speciality}`).then(async res => {
//         specialities.value = await getSelectables('specialities');
//
//     }).catch(error => {
//         console.error('Failed to fetch select options:', error);
//
//     });
//
// }
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
