<script setup>
import {onMounted, ref, watchEffect} from 'vue';
import {getSelectables} from "../../utils/storeHelpers.js";

const {modelValue, placeholderLabel} = defineProps({
    modelValue: {
        type: Object,
    },
    placeholderLabel: {
        type: String,
        default: "Language"
    }
});
const emit = defineEmits(["update:modelValue"]);

const item = ref(modelValue.item);
const rating = ref(modelValue.rating);

watchEffect(() => {
    emit('update:modelValue', {
        item: item.value,
        rating: rating.value,
    });
});


const languages = ref([])
onMounted(async () => {
    axios.get('/api/selectables/languages').then(async res => {
        languages.value = await getSelectables('languages');

    }).catch(error => {
        console.error('Failed to fetch select options:', error);

    });

});

const setRatingValue = (value) => {
    rating.value = value;
};
</script>

<template>

    <div class="p-2 my-2">

        <select v-if="placeholderLabel == 'Language'" v-model="item" :value="item"
                class="text-xs focus:border-orange rounded focus:ring-0 bg-zinc-50 w-full border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none">

            <option value="" class="hidden" selected>Languages</option>
            <option v-for="language in languages">{{ language }}</option>

        </select>

        <div v-if="placeholderLabel !== 'Language'" class="relative">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>
            <input
                type="text"
                v-model="item"
                :placeholder="placeholderLabel"
                class="capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-full rounded-md text-xs border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
            />
            <!--            <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

        </div>
        <div class="flex items-center justify-between mt-6">
            <h1 class="flex-none font-semibold text-zinc-500">Competency Level</h1>

            <button
                v-for="(value,index) in 5"
                :key="index"
                @mouseover="setRatingValue(value)"
                @click="setRatingValue(value)"
                :class="rating >= value ? 'bg-orange':'bg-zinc-100'"
                class="rounded-full w-8 h-3 hover:bg-orange">

            </button>

        </div>
        <!--        <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

    </div>

</template>


<style scoped>

</style>
