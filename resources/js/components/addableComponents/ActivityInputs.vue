<template>

    <div class="w-full py-6 pr-0">

        <div class="md:flex flex-none md:space-x-3 space-x-0 space-y-5 md:space-y-0 items-center w-full mt-4">

            <div class="relative w-full">
                <input v-model="modelValue.title"
                       placeholder="Activity or event title"
                       class="w-full capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-6/12 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
                <!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative w-full">

                <select v-model="modelValue.participatedAs"
                        class="rounded text-sm focus:border-orange focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option value="Participated as" class="notranslate hidden" selected>Participated as</option>
                    <option v-for="item in participation">{{ item }}</option>

                </select>
            </div>
            <div class="relative w-full">
                <select v-model="modelValue.year"
                        class="w-full focus:border-orange focus:ring-0 bg-zinc-50 w-6/12 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">
                    <option selected>{{ modelValue.year }}
                    </option>
                    <template v-for="year in years">
                        <option :value="year">{{ year }}</option>
                    </template>
                </select>
                <!--                <h1 class="text-red-500 text-sm mt-1 font-semibold">This field is required.</h1>-->

            </div>


        </div>
    </div>


</template>

<script setup>
import {onMounted, ref, watchEffect} from 'vue';
import {getSelectables} from "../../utils/storeHelpers.js";

const participation = ref([])

onMounted(async () => {
    axios.get('').then(async res => {
        participation.value = await getSelectables('participation');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });

});


const {modelValue} = defineProps(["modelValue"]);

const title = ref(modelValue.title)
const participatedAs = ref(modelValue.participatedAs)
const year = ref(modelValue.year)

const years = ref([]);
const currentYear = new Date().getFullYear();
for (let year = 1950; year <= currentYear; year++) {
    years.value.push(year);
}

const emit = defineEmits(["update:modelValue"]);

watchEffect(() => {
    emit("update:modelValue", {
        title: title.value,
        participatedAs: participatedAs.value,
        year: year.value
    });
});
</script>

<style scoped>

</style>
