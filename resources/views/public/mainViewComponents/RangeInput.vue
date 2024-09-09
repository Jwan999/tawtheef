<template>
    <div class="mb-6 text-zinc-700">
        <div class="flex w-full justify-between items-center space-x-4">
            <div class="flex w-5/12">
                <button
                    @click="handleMinChange(localMin - 1)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-l-md px-2 group"
                >
                    <svg class="w-5 fill-zinc-700 group-hover:fill-orange" id="Livello_1" enable-background="new 0 0 300 300" viewBox="0 0 300 300"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path
                                d="m240.3 171.5h-180.6c-11.9 0-21.5-9.6-21.5-21.5s9.6-21.5 21.5-21.5h180.6c11.9 0 21.5 9.6 21.5 21.5s-9.6 21.5-21.5 21.5z"/>
                        </g>
                    </svg>
                </button>

                <input
                    :value="localMin"
                    @input="handleMinInput($event.target.value)"
                    @blur="handleMinBlur"
                    class="w-full text-center border-y border-gray-200 py-1 focus:outline-none focus:border-orange-500"
                    :min="minRange"
                    :max="localMax"
                />
                <button
                    @click="handleMinChange(localMin + 1)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-r-md px-2 group">
                    <svg id="Livello_1" enable-background="new 0 0 300 300" class="w-5 fill-zinc-700 group-hover:fill-orange" viewBox="0 0 300 300"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="m240.3 128.5h-68.8v-68.8c0-11.9-9.6-21.5-21.5-21.5s-21.5 9.6-21.5 21.5v68.8h-68.8c-11.9 0-21.5 9.6-21.5 21.5s9.6 21.5 21.5 21.5h68.8v68.8c0 11.9 9.6 21.5 21.5 21.5s21.5-9.6 21.5-21.5v-68.8h68.8c11.9 0 21.5-9.6 21.5-21.5s-9.6-21.5-21.5-21.5z"/>
                    </svg>
                </button>
            </div>
            <span class="text-gray-200">to</span>
            <div class="flex w-5/12">
                <button
                    @click="handleMaxChange(localMax - 1)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-l-md px-2 group">
                    <svg class="w-5 fill-zinc-700 group-hover:fill-orange" id="Livello_1" enable-background="new 0 0 300 300" viewBox="0 0 300 300"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path
                                d="m240.3 171.5h-180.6c-11.9 0-21.5-9.6-21.5-21.5s9.6-21.5 21.5-21.5h180.6c11.9 0 21.5 9.6 21.5 21.5s-9.6 21.5-21.5 21.5z"/>
                        </g>
                    </svg>
                </button>

                <input
                    :value="localMax"
                    @input="handleMaxInput($event.target.value)"
                    @blur="handleMaxBlur"
                    class="w-full text-center border-y border-gray-200 py-1 focus:outline-none focus:border-orange-500"
                    :min="localMin"
                    :max="maxRange"
                />
                <button
                    @click="handleMaxChange(localMax + 1)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-r-md px-2 group">
                    <svg id="Livello_1" enable-background="new 0 0 300 300" class="w-5 fill-zinc-700 group-hover:fill-orange" viewBox="0 0 300 300"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="m240.3 128.5h-68.8v-68.8c0-11.9-9.6-21.5-21.5-21.5s-21.5 9.6-21.5 21.5v68.8h-68.8c-11.9 0-21.5 9.6-21.5 21.5s9.6 21.5 21.5 21.5h68.8v68.8c0 11.9 9.6 21.5 21.5 21.5s21.5-9.6 21.5-21.5v-68.8h68.8c11.9 0 21.5-9.6 21.5-21.5s-9.6-21.5-21.5-21.5z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    label: String,
    minRange: { type: Number, default: 0 },
    maxRange: { type: Number, default: 100 },
    modelValue: {
        type: Object,
        default: () => ({ min: 0, max: 100 })
    }
});

const emit = defineEmits(['update:modelValue']);

const localMin = ref(props.modelValue.min);
const localMax = ref(props.modelValue.max);

const handleMinChange = (value) => {
    const newMin = Math.max(props.minRange, Math.min(value, localMax.value));
    localMin.value = newMin;
    emitChange();
};

const handleMaxChange = (value) => {
    const newMax = Math.max(localMin.value, Math.min(value, props.maxRange));
    localMax.value = newMax;
    emitChange();
};

const handleMinInput = (value) => {
    localMin.value = value === '' ? '' : parseInt(value);
};

const handleMaxInput = (value) => {
    localMax.value = value === '' ? '' : parseInt(value);
};

const handleMinBlur = () => {
    if (localMin.value === '' || isNaN(localMin.value)) {
        localMin.value = props.minRange;
    } else {
        handleMinChange(localMin.value);
    }
};

const handleMaxBlur = () => {
    if (localMax.value === '' || isNaN(localMax.value)) {
        localMax.value = props.maxRange;
    } else {
        handleMaxChange(localMax.value);
    }
};

const emitChange = () => {
    emit('update:modelValue', { min: localMin.value, max: localMax.value });
};

watch(() => props.modelValue, (newValue) => {
    localMin.value = newValue.min;
    localMax.value = newValue.max;
}, { deep: true });
</script>
