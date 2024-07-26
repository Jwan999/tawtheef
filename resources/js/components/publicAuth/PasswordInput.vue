<template>
    <div>
        <div class="relative mb-4">
            <input
                :type="showPassword ? 'text' : 'password'"
                v-model="password"
                class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                placeholder="Password"
            >
            <button
                @click="togglePasswordVisibility"
                class="absolute inset-y-0 right-0 flex items-center pr-3"
                type="button"
            >
                <svg
                    class="h-5 w-5 text-gray-400"
                    :class="{ 'text-orange': showPassword }"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        v-if="showPassword"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                        v-if="showPassword"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                    <path
                        v-if="!showPassword"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                    />
                </svg>
            </button>
        </div>
        <div class="relative mb-4">
            <input
                :type="showPassword ? 'text' : 'password'"
                v-model="confirmPassword"
                class="block w-full p-2.5 bg-zinc-50 rounded-md text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                placeholder="Confirm Password"
            >
        </div>
        <p v-if="password && confirmPassword && !passwordsMatch" class="text-red-500 text-sm mt-1">
            Passwords do not match
        </p>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const password = ref('');
const confirmPassword = ref('');
const showPassword = ref(false);

const passwordsMatch = computed(() => {
    return password.value === confirmPassword.value;
});

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

// Expose password and whether it's valid to parent component
const isValid = computed(() => password.value && passwordsMatch.value);

defineExpose({
    password,
    isValid
});
</script>
