<template>
    <div>
        <div class="flex items-center space-x-3 mb-4">
            <h1 class="flex-none text-xl font-semibold text-dark">Specializations</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="bg-white rounded-md shadow-sm">
            <div :class="specializations.length ? 'rounded-t-md' : 'rounded-b-md rounded-t-md'"
                class="bg-orange text-lg text-white py-3 px-4 font-semibold rounded-t-md">
                <h2 v-if="specializations.length">Selected Specializations:</h2>
                <p v-else class="text-sm">No specializations selected yet.</p>
            </div>
            <div class="">
                <ul v-if="specializations.length" class="list-disc list-inside space-y-4 p-4">
                    <li v-for="spec in specializations" :key="spec" class="text-dark">
                        {{ spec }}
                        <template v-if="getSubSpecializations(spec).length">
                            <p class="text-orange text-sm font-semibold mt-2 ml-6">Experienced with the following:</p>
                            <ul class="list-circle list-inside ml-8 mt-1 space-y-1">
                                <li v-for="subSpec in getSubSpecializations(spec)" :key="subSpec" class="text-zinc-600 text-sm">
                                    {{ subSpec }}
                                </li>
                            </ul>
                        </template>
                    </li>
                </ul>
            </div>
        </div>

        <div v-else class="bg-white rounded-md shadow-sm">
            <div class="bg-orange text-lg text-white py-3 px-4 font-semibold rounded-t-md">
                <h2>Select Your Specializations (Max 2)</h2>
            </div>
            <div class="p-4">
                <p class="text-zinc-500 mb-4">* You can pick two main specializations only.</p>
                <div class="space-y-4">
                    <div v-for="(item, index) in specialities" :key="index" class="border-b border-orange-200 pb-4 last:border-b-0">
                        <div class="flex items-center space-x-2 mb-2">
                            <input
                                :id="`specialization-${index}`"
                                type="checkbox"
                                :value="item.title"
                                :checked="specializations.includes(item.title)"
                                @change="toggleSpecialization(item.title)"
                                class="w-5 h-5 text-orange border-zinc-300 rounded focus:ring-orange"
                                :disabled="specializations.length >= 2 && !specializations.includes(item.title)"
                            >
                            <label :for="`specialization-${index}`" class="text-base font-semibold text-dark cursor-pointer">
                                {{ item.title }}
                            </label>
                        </div>
                        <div v-if="specializations.includes(item.title)" class="ml-7 mt-2 space-y-2">
                            <div v-for="(subSpec, subIndex) in item.children" :key="subIndex" class="flex items-center space-x-2">
                                <input
                                    :id="`subSpec-${index}-${subIndex}`"
                                    type="checkbox"
                                    :value="subSpec"
                                    :checked="children.includes(subSpec)"
                                    @change="toggleSubSpeciality(subSpec)"
                                    class="w-4 h-4 text-orange border-zinc-300 rounded focus:ring-orange"
                                >
                                <label :for="`subSpec-${index}-${subIndex}`" class="text-base text-zinc-600 cursor-pointer">
                                    {{ subSpec }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { editMode, getSelectables } from "../../utils/storeHelpers.js";

const props = defineProps(["modelValue"]);
const emit = defineEmits(["update:modelValue"]);

const specializations = ref([]);
const children = ref([]);
const specialities = ref([]);

watch(() => props.modelValue, (newValue) => {
    specializations.value = newValue?.specializations || [];
    children.value = newValue?.children || [];
}, { immediate: true, deep: true });

const toggleSpecialization = (name) => {
    const index = specializations.value.indexOf(name);
    if (index === -1 && specializations.value.length < 2) {
        specializations.value.push(name);
    } else if (index !== -1) {
        specializations.value.splice(index, 1);
        // Remove related sub-specialities
        children.value = children.value.filter(child =>
            !specialities.value.find(s => s.title === name)?.children.includes(child)
        );
    }
    updateModelValue();
};

const toggleSubSpeciality = (subSpeciality) => {
    const index = children.value.indexOf(subSpeciality);
    if (index === -1) {
        children.value.push(subSpeciality);
    } else {
        children.value.splice(index, 1);
    }
    updateModelValue();
};

const updateModelValue = () => {
    emit('update:modelValue', {
        specializations: specializations.value,
        children: children.value
    });
};

const getSubSpecializations = (specialization) => {
    const specItem = specialities.value.find(item => item.title === specialization);
    return specItem ? children.value.filter(child => specItem.children.includes(child)) : [];
};

(async () => {
    specialities.value = await getSelectables('specialities');
})();
</script>

<style scoped>
.list-circle {
    list-style-type: circle;
}
</style>
