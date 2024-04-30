
<script setup>
import RatingComponent from './RatingComponent.vue';
import {computed, ref, watch} from 'vue';
import store from "../../store/index.js";

const hoveredElement = ref(null)
const borderColor = ref('border-slate-100')

const {modelValue} = defineProps(["modelValue"]);

const changeBorderColor = (index, color) => {
    hoveredElement.value = index
    borderColor.value = color;
}

const list = computed({
    get() {
        return modelValue?.length ? modelValue : [{item: "", rating: ""}];
    },
    set(val) {
        emit('update:modelValue', val)
    }
});

const componentAction = (index) => {
    if (index === 0) {
        list.value.push({
            item: "",
            rating: "",
        });
    } else if (index > 0) {
        list.value.splice(index, 1)

    }
}

const emit = defineEmits(["update:modelValue"])

const editMode = computed({
    get() {
        return store.getters.editMode;
    },
    set() {
        store.dispatch('setEditMode', true)
    }
})
</script>

<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Languages</h1>
            <div v-for="(language,index) in list" :key="index" class="flex items-center justify-between">
                <h1 class="font-semibold">{{ language.item }}</h1>


                <div class="flex items-center space-x-2">
                    <div
                        v-for="(value,index) in 5"
                        :key="index"
                        :class="language.rating >= value ? 'bg-orange':'bg-zinc-100'"
                        class="rounded-full w-8 h-3">
                        </div>

                </div>
            </div>
        </div>

    </div>

    <div v-else class="rounded-md py-4 bg-white text-sm md:text-xs">

        <div class="space-y-3 px-4 text-sm md:text-xs">
            <h1 class="text-lg font-semibold text-dark pb-3">Languages</h1>

            <div class="relative w-full">
                <div class="space-y-3">
                    <div v-for="(item,index) in list"
                         :key="index"
                         class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                         :class="hoveredElement===index ? borderColor : 'border-slate-100'">
                        <button @mouseover="changeBorderColor(index,'border-dark')"
                                @mouseleave="changeBorderColor(index,'border-slate-100')"
                                @click="componentAction(index)"
                                class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                            {{ index === 0 ? 'Add component' : 'Remove component' }}
                        </button>

                        <ratingComponent v-model="list[index]"></ratingComponent>
                    </div>
                </div>

            </div>


        </div>


    </div>


</template>

<style scoped>

</style>
