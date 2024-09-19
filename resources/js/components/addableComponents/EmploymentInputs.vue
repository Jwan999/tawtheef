<template>
    <div class="py-8 w-full">
        <div class="flex md:flex-nowrap flex-wrap md:space-x-3 space-x-0 md:space-y-0 space-y-6 items-center">
            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.title" placeholder="Job title"
                       class="w-full capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-4/12 rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
            </div>

            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input v-model="modelValue.employer" placeholder="Employer"
                       class="w-full capitalize focus:border-orange focus:ring-0 bg-zinc-50 w-4/12 rounded-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none"
                       type="text">
            </div>

            <div class="relative w-full">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <div class="flex">
                    <div class="w-full">
                        <select v-model="modelValue.duration[0]" @change="updateDuration" name="startYear"
                                class="w-full focus:border-orange focus:ring-0 bg-zinc-50 rounded-l-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none appearance-none">
                            <option selected>{{ modelValue.duration[0] }}</option>
                            <template v-for="year in years">
                                <option :value="year">{{ year }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="w-full">
                        <select v-model="modelValue.duration[1]" @change="updateDuration" name="endYear"
                                class="w-full focus:border-orange focus:ring-0 bg-zinc-50 rounded-r-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none appearance-none">
                            <option selected>{{ modelValue.duration[1] }}</option>
                            <option value="Present">Present</option>
                            <template v-for="year in years">
                                <option :value="year">{{ year }}</option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full mt-6">
            <label for="message" class="block mb-3 text-zinc-500 mt-2">* List all of your responsibilities
                within this period of employment.</label>

            <div class="relative flex mt-2">
                <input
                    type="text"
                    v-model="responsibility"
                    placeholder="Add a responsibility"
                    @input="validateResponsibility"
                    class="flex-grow focus:border-orange focus:ring-0 bg-zinc-50 rounded-l-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none p-2"
                />
                <button
                    @click="addResponsibility"
                    :disabled="!isValidResponsibility"
                    :class="[
                        'px-4 py-2 text-sm font-semibold text-white rounded-r-md transition-colors duration-200 ease-in-out focus:outline-none',
                        isValidResponsibility ? 'bg-orange hover:bg-dark' : 'bg-zinc-300 cursor-not-allowed'
                    ]"
                >
                    ADD MORE
                </button>
            </div>

            <transition-group name="list" tag="ul" class="space-y-2 mt-4">
                <li
                    v-for="(resp, index) in modelValue.responsibilities"
                    :key="resp"
                    class="flex bg-zinc-100 p-2 justify-between items-center text-zinc-700 font-medium rounded"
                >
                    <span class="truncate mr-2">{{ resp }}</span>
                    <button
                        @click="removeResponsibility(index)"
                        class="focus:outline-none appearance-none hover:text-red-500 transition-colors duration-200 ease-in-out"
                        aria-label="Delete responsibility"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </li>
            </transition-group>
        </div>
    </div>
</template>


<script setup>
import {ref, watch, computed, onMounted} from 'vue';

const props = defineProps(["modelValue"]);
const emit = defineEmits(["update:modelValue"]);

const responsibility = ref('');
const isValidResponsibility = ref(false);

const validateResponsibility = () => {
    isValidResponsibility.value = responsibility.value.trim() !== '';
};

const addResponsibility = () => {
    if (isValidResponsibility.value) {
        const updatedResponsibilities = [...props.modelValue.responsibilities, responsibility.value.trim()];
        emit("update:modelValue", {...props.modelValue, responsibilities: updatedResponsibilities});
        responsibility.value = '';
        isValidResponsibility.value = false;
    }
};

const currentYear = new Date().getFullYear();
const years = computed(() => {
    const yearArray = [];
    for (let year = currentYear; year >= 1950; year--) {
        yearArray.push(year);
    }
    return yearArray;
});

const removeResponsibility = (index) => {
    const updatedResponsibilities = props.modelValue.responsibilities.filter((_, i) => i !== index);
    emit("update:modelValue", {...props.modelValue, responsibilities: updatedResponsibilities});
};

const updateDuration = () => {
    emit("update:modelValue", {
        ...props.modelValue,
        duration: [
            props.modelValue.duration[0] || null,
            props.modelValue.duration[1] || null
        ]
    });
};

onMounted(() => {
    updateDuration();
});

watch(() => props.modelValue.duration, (newDuration) => {
    if (!newDuration[0] && !newDuration[1]) {
        emit("update:modelValue", {
            ...props.modelValue,
            duration: [null, null]
        });
    }
}, {immediate: true, deep: true});
</script>


<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>
