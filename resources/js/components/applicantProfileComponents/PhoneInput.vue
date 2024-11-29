<template>
    <div className="w-full relative">
        <input
            type="text"
            v-model="displayPhone"
            @input="handlePhoneInput"
            class="text-sm block w-full p-2.5 focus:border-orange focus:ring-0 bg-zinc-50 rounded-md border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
            :class="{ 'border-red-500': phoneError }"
            placeholder="0964-000-0000-000"
        />
        <div v-if="phoneError" className="text-red-500 text-xs mt-1">{{ phoneError }}</div>
        <div v-else-if="phoneCarrier" className="text-green-500 text-xs mt-1">{{ phoneCarrier }}</div>
        <div className="mt-2">
            <input
                id="phone-visibility"
                type="checkbox"
                v-model="localShowPhone"
                :disabled="!isPhoneValid"
                class="w-4 h-4 mr-2 mb-1 text-orange bg-zinc-100 border-zinc-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-zinc-800 focus:ring-1 dark:bg-zinc-700 dark:border-zinc-600"
            >
            <label htmlFor="phone-visibility" className="md:text-sm text-xs text-zinc-500">Make phone number
                public.</label>
        </div>
    </div>
</template>

<script setup>
import {ref, watch, onMounted} from 'vue';

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
        default: () => ({
            phone: '',
            showPhone: false
        })
    }
});

const emit = defineEmits(['update:modelValue']);

const displayPhone = ref('');
const localShowPhone = ref(false);
const phoneError = ref('');
const phoneCarrier = ref('');
const isPhoneValid = ref(false);

const carriers = {
    '75': 'Korek Telecom',
    '76': 'Alkafeel Omnnea',
    '77': 'Asiacell',
    '78': 'Zain',
    '79': 'Zain'
};

const formatPhoneNumber = (number) => {
    if (!number) return '';
    let cleaned = number.replace(/\D/g, '');

    if (cleaned.startsWith('00964')) {
        cleaned = cleaned.slice(5);
    } else if (cleaned.startsWith('964')) {
        cleaned = cleaned.slice(3);
    } else if (cleaned.startsWith('0')) {
        cleaned = cleaned.slice(1);
    }

    if (cleaned.length >= 10) {
        return `+964 ${cleaned.slice(0, 3)} ${cleaned.slice(3, 6)} ${cleaned.slice(6)}`;
    }
    return cleaned;
};

const validatePhone = (phoneNumber) => {
    if (!phoneNumber) {
        phoneError.value = '';
        phoneCarrier.value = '';
        isPhoneValid.value = false;
        return false;
    }

    const cleaned = phoneNumber.replace(/\D/g, '');
    let numberToCheck = cleaned;

    if (cleaned.startsWith('00964')) {
        numberToCheck = cleaned.slice(5);
    } else if (cleaned.startsWith('964')) {
        numberToCheck = cleaned.slice(3);
    } else if (cleaned.startsWith('0')) {
        numberToCheck = cleaned.slice(1);
    }

    if (numberToCheck.length !== 10) {
        phoneError.value = 'Phone number must be 10 digits';
        phoneCarrier.value = '';
        isPhoneValid.value = false;
        return false;
    }

    const prefix = numberToCheck.slice(0, 2);
    if (!carriers[prefix]) {
        phoneError.value = 'Invalid carrier prefix';
        phoneCarrier.value = '';
        isPhoneValid.value = false;
        return false;
    }

    phoneError.value = '';
    phoneCarrier.value = `Carrier: ${carriers[prefix]}`;
    isPhoneValid.value = true;
    return true;
};

const handlePhoneInput = (event) => {
    const inputValue = event.target.value;
    displayPhone.value = inputValue;

    const isValid = validatePhone(inputValue);
    const formattedNumber = isValid ? formatPhoneNumber(inputValue) : inputValue;

    emit('update:modelValue', {
        phone: formattedNumber,
        showPhone: localShowPhone.value
    });
};

watch(() => props.modelValue, (newVal) => {
    if (newVal) {
        displayPhone.value = newVal.phone || '';
        localShowPhone.value = newVal.showPhone || false;
        if (newVal.phone) {
            validatePhone(newVal.phone);
        }
    }
}, {deep: true});

watch(localShowPhone, (newVal) => {
    emit('update:modelValue', {
        phone: displayPhone.value,
        showPhone: newVal
    });
});

onMounted(() => {
    if (props.modelValue) {
        displayPhone.value = props.modelValue.phone || '';
        localShowPhone.value = props.modelValue.showPhone || false;
        if (props.modelValue.phone) {
            validatePhone(props.modelValue.phone);
        }
    }
});
</script>
