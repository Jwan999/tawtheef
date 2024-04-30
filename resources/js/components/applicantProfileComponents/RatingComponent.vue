<script setup>
import {ref, watchEffect} from 'vue';

const {modelValue} = defineProps(["modelValue"]);
const emit = defineEmits(["update:modelValue"]);

const item = ref(modelValue.item);
const rating = ref(modelValue.rating);

watchEffect(() => {
    emit('update:modelValue', {
        item: item.value,
        rating: rating.value,
    });
});

const setRatingValue = (value) => {
    rating.value = value;
};
</script>

<template>

    <div class="p-4">
        <div class="relative mt-2">
            <span class="text-orange absolute top-0 right-0 ml-24 -mt-4">*</span>
            <input
                type="text"
                v-model="item"
                placeholder="item"
                class="capitalize font-semibold focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
            />
            <!--            <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

        </div>
        <div class="flex justify-between mt-6">
            <button
                v-for="(value,index) in 5"
                :key="index"
                @mouseover="setRatingValue(value)"
                @click="setRatingValue(value)"
                :class="rating >= value ? 'bg-orange':'bg-zinc-100'"
                class="rounded-full w-10 h-4 hover:bg-orange">

            </button>

        </div>
        <!--        <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

    </div>

</template>


<style scoped>

</style>
