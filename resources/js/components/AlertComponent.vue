<!-- Alert.vue -->
<template>
    <transition name="fade">
        <div v-if="show" class="fixed top-4 right-4 max-w-sm w-full bg-white border-l-4 rounded-md shadow-md"
             :class="typeClasses">
            <div class="flex items-center justify-between p-3">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" :class="iconColor" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path v-if="type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path v-else-if="type === 'error'" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-base font-medium" :class="textColor">{{ message }}</p>
                </div>
                <button @click="closeAlert" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </transition>
</template>

<script setup>
import {ref, watch, onMounted} from 'vue';

const props = defineProps({
    message: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['info', 'success', 'error'].includes(value)
    },
    duration: {
        type: Number,
        default: 10000
    }
});

const show = ref(false);
const timer = ref(null);

const typeClasses = {
    'border-orange': props.type === 'info',
    'border-zinc-500': props.type === 'success',
    'border-red-500': props.type === 'error'
};

const textColor = {
    'text-orange': props.type === 'info',
    'text-zinc-800': props.type === 'success',
    'text-red-800': props.type === 'error'
};

const iconColor = {
    'text-orange': props.type === 'info',
    'text-zinc-500': props.type === 'success',
    'text-red-500': props.type === 'error'
};

const closeAlert = () => {
    show.value = false;
    if (timer.value) {
        clearTimeout(timer.value);
    }
};

watch(() => props.message, (newVal) => {
    if (newVal) {
        show.value = true;
        if (timer.value) {
            clearTimeout(timer.value);
        }
        timer.value = setTimeout(() => {
            show.value = false;
        }, props.duration);
    }
});

onMounted(() => {
    if (props.message) {
        show.value = true;
        timer.value = setTimeout(() => {
            show.value = false;
        }, props.duration);
    }
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
