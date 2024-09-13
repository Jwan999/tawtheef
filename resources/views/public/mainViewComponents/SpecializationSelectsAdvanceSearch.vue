<template>
    <div class="specialization-selector">
        <h1 class="text-xl text-white mb-4 tracking-wider font-bold">Specializations</h1>

        <!-- Main Specializations -->
        <div class="space-y-4">
            <div v-for="spec in specializations" :key="spec.title" class="specialization-item">
                <label class="flex items-center space-x-3 text-zinc-100">
                    <input
                        type="checkbox"
                        :value="spec.title"
                        v-model="selectedMainSpecializations"
                        class="form-checkbox h-5 w-5 text-orange rounded border-gray-300 focus:ring-orange"
                        @change="handleMainSpecializationChange"
                    />
                    <span>{{ spec.title }}</span>
                </label>

                <!-- Sub Specialties -->
                <div v-if="selectedMainSpecializations.includes(spec.title)" class="ml-6 mt-2 space-y-2">
                    <label v-for="subSpec in spec.children" :key="subSpec"
                           class="flex items-center space-x-3 text-zinc-300">
                        <input
                            type="checkbox"
                            :value="subSpec"
                            v-model="selectedSubSpecialities"
                            class="form-checkbox h-4 w-4 text-orange rounded border-gray-400 focus:ring-orange"
                        />
                        <span class="text-sm">{{ subSpec }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, computed, onMounted, watch} from 'vue';
import {useStore} from 'vuex';
import {getSelectables} from "../../../js/utils/storeHelpers.js";

const store = useStore();

const specializations = ref([]);
const selectedMainSpecializations = ref([]);
const selectedSubSpecialities = ref([]);

const handleMainSpecializationChange = () => {
    // Remove sub-specialties that are no longer available
    selectedSubSpecialities.value = selectedSubSpecialities.value.filter(sub =>
        selectedMainSpecializations.value.some(main =>
            specializations.value.find(s => s.title === main)?.children.includes(sub)
        )
    );

    // Update store
    store.commit('updateFilter', {key: 'mainSpecializations', value: selectedMainSpecializations.value});
    store.commit('updateFilter', {key: 'subSpecialities', value: selectedSubSpecialities.value});
};

onMounted(async () => {
    try {
        specializations.value = await getSelectables('specialities');

        // Initialize selections from store
        const storeMainSpecializations = store.state.filters.mainSpecializations;
        selectedMainSpecializations.value = Array.isArray(storeMainSpecializations) ? storeMainSpecializations : [];

        const storeSubSpecialities = store.state.filters.subSpecialities;
        selectedSubSpecialities.value = Array.isArray(storeSubSpecialities) ? storeSubSpecialities : [];
    } catch (error) {
        console.error('Error fetching specializations:', error);
    }
});

// Watch for changes in sub-specialties and update store
watch(selectedSubSpecialities, (newValue) => {
    store.commit('updateFilter', {key: 'subSpecialities', value: newValue});
});

// Watch for changes in main specializations and update store
watch(selectedMainSpecializations, (newValue) => {
    store.commit('updateFilter', {key: 'mainSpecializations', value: newValue});
});
</script>

<style scoped>
.specialization-selector {
    @apply space-y-4;
}

.specialization-item {
    @apply mb-4;
}

/* Custom checkbox styles */
.form-checkbox {
    @apply rounded border-gray-300 text-orange shadow-sm focus:border-orange focus:ring focus:ring-orange focus:ring-opacity-50;
}

.form-checkbox:checked {
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
    border-color: transparent;
    background-color: currentColor;
    background-size: 100% 100%;
    background-position: center;
    background-repeat: no-repeat;
}
</style>
