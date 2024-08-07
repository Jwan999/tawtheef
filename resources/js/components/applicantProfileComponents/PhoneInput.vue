<template>
    <div class="w-full relative">
        <input
            type="text"
            v-model="phoneInput"
            @input="validatePhone"
            class="text-sm block w-full p-2.5 focus:border-orange focus:ring-0 bg-zinc-50 rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
            :class="{ 'border-red-500': phoneError }"
            placeholder="0964-000-0000-000"
            required
        />
        <div v-if="phoneError" class="text-red-500 text-xs mt-1">{{ phoneError }}</div>
        <div v-else-if="phoneCarrier" class="text-green-500 text-xs mt-1">{{ phoneCarrier }}</div>
        <div class="mt-2">
            <input
                id="orange-checkbox"
                type="checkbox"
                v-model="isChecked"
                :disabled="!isPhoneValid"
                class="w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600"
            >
            <span class="md:text-sm text-xs text-zinc-500">Make phone number public.</span>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps(['modelValue']);
const emit = defineEmits(['update:modelValue']);

const phoneInput = ref(props.modelValue);
const phoneError = ref('');
const phoneCarrier = ref('');
const isPhoneValid = ref(false);
const isChecked = ref(false);

const carriers = {
    '75': 'Korek Telecom',
    '76': 'Alkafeel Omnnea',
    '77': 'Asiacell',
    '78': 'Zain',
    '79': 'Zain'
};

const validatePhone = () => {
    const phoneNumber = phoneInput.value.replace(/\D/g, '');
    let formattedNumber = phoneNumber;

    if (phoneNumber.startsWith('00964')) {
        formattedNumber = phoneNumber.slice(5);
    } else if (phoneNumber.startsWith('964')) {
        formattedNumber = phoneNumber.slice(3);
    } else if (phoneNumber.startsWith('0')) {
        formattedNumber = phoneNumber.slice(1);
    }

    if (formattedNumber.length !== 10) {
        phoneError.value = 'Invalid phone number length';
        phoneCarrier.value = '';
        isPhoneValid.value = false;
        return;
    }

    const prefix = formattedNumber.slice(0, 2);
    if (!carriers[prefix]) {
        phoneError.value = 'Invalid carrier prefix';
        phoneCarrier.value = '';
        isPhoneValid.value = false;
        return;
    }

    phoneError.value = '';
    phoneCarrier.value = `Carrier: ${carriers[prefix]}`;
    isPhoneValid.value = true;

    // Format the phone number for display
    const formattedDisplay = `+964 ${formattedNumber.slice(0, 3)} ${formattedNumber.slice(3, 6)} ${formattedNumber.slice(6)}`;
    phoneInput.value = formattedDisplay;

    emit('update:modelValue', formattedDisplay);
};

watch(() => props.modelValue, (newValue) => {
    phoneInput.value = newValue;
    validatePhone();
});

watch(isChecked, (newValue) => {
    emit('update:isChecked', newValue);
});
</script>
