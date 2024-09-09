<template>
    <div class="specialization-selector">
        <h1 class="text-xl text-white mb-4 tracking-wider font-bold">Specializations</h1>

        <!-- Main Specializations -->
        <div class="mb-6">
            <label class="text-sm text-zinc-300 mb-2 block">Main Specializations</label>
            <div class="relative" ref="mainDropdownRef">
                <button @click="toggleMainDropdown"
                        class="w-full text-left text-zinc-700 bg-zinc-100 py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-orange">
                    {{ selectedMainSpecializations.length ? `${selectedMainSpecializations.length} selected` : 'Select main specializations' }}
                </button>
                <div v-if="isMainDropdownOpen"
                     class="absolute z-10 mt-1 w-full bg-zinc-100 border border-zinc-600 rounded-md shadow-lg max-h-60 overflow-auto">
                    <div v-for="speci in specializations" :key="speci.title"
                         class="flex items-center px-4 py-2 text-zinc-800 hover:bg-zinc-700 hover:text-zinc-100 cursor-pointer">
                        <input type="checkbox"
                               :id="speci.title"
                               @click.stop="toggleMainSpecialization(speci.title)"
                               :checked="selectedMainSpecializations.includes(speci.title)"
                               class="form-checkbox h-5 w-5 text-orange-500 rounded focus:ring-orange-500 focus:ring-offset-0 transition duration-150 ease-in-out cursor-pointer">
                        <label :for="speci.title" class="ml-2 cursor-pointer">{{ speci.title }}</label>
                    </div>
                </div>
            </div>
            <!-- Display selected main specializations -->
            <div class="mt-2 flex flex-wrap gap-2">
                <div v-for="speci in selectedMainSpecializations" :key="speci"
                     class="bg-orange-500 text-white text-sm py-1 px-2 rounded-full flex items-center">
                    {{ speci }}
                    <button @click="toggleMainSpecialization(speci)" class="ml-2 focus:outline-none">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sub Specialties -->
        <div v-if="selectedMainSpecializations.length > 0">
            <label class="text-sm text-zinc-300 mb-2 block">Sub Specialties</label>
            <div class="relative" ref="subDropdownRef">
                <button @click="toggleSubDropdown"
                        class="w-full text-left bg-zinc-100 text-zinc-700 py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-orange">
                    {{ selectedSubSpecialities.length ? `${selectedSubSpecialities.length} selected` : 'Select sub specialties' }}
                </button>
                <div v-if="isSubDropdownOpen"
                     class="absolute z-10 mt-1 w-full bg-zinc-100 border border-zinc-600 rounded-md shadow-lg max-h-60 overflow-auto">
                    <div v-for="subSpec in availableSubSpecialties" :key="subSpec"
                         class="flex items-center px-4 py-2 hover:bg-zinc-700 hover:text-zinc-100 cursor-pointer"
                         @click.stop="toggleSubSpecialty(subSpec)">
                        <input type="checkbox"
                               :id="subSpec"
                               :checked="selectedSubSpecialities.includes(subSpec)"
                               class="form-checkbox h-5 w-5 text-orange-500 rounded focus:ring-orange-500 focus:ring-offset-0 transition duration-150 ease-in-out cursor-pointer"
                               @click.stop>
                        <label :for="subSpec" class="ml-2 cursor-pointer">{{ subSpec }}</label>
                    </div>
                </div>
            </div>
            <!-- Display selected sub specialties -->
            <div class="mt-2 flex flex-wrap gap-2">
                <div v-for="subSpec in selectedSubSpecialities" :key="subSpec"
                     class="bg-orange-500 text-white text-sm py-1 px-2 rounded-full flex items-center">
                    {{ subSpec }}
                    <button @click="toggleSubSpecialty(subSpec)" class="ml-2 focus:outline-none">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useStore } from 'vuex';
import { getSelectables } from "../../../js/utils/storeHelpers.js";

const store = useStore();

const isMainDropdownOpen = ref(false);
const isSubDropdownOpen = ref(false);
const specializations = ref([]);
const mainDropdownRef = ref(null);
const subDropdownRef = ref(null);

const selectedMainSpecializations = computed(() => store.state.filters.mainSpecializations);
const selectedSubSpecialities = computed(() => store.state.filters.subSpecialities);

const availableSubSpecialties = computed(() => {
    return selectedMainSpecializations.value.flatMap(mainSpec =>
        specializations.value.find(s => s.title === mainSpec)?.children || []
    );
});

const toggleMainDropdown = () => {
    isMainDropdownOpen.value = !isMainDropdownOpen.value;
    if (isMainDropdownOpen.value) isSubDropdownOpen.value = false;
};

const toggleSubDropdown = () => {
    isSubDropdownOpen.value = !isSubDropdownOpen.value;
    if (isSubDropdownOpen.value) isMainDropdownOpen.value = false;
};

const closeMainDropdown = () => {
    isMainDropdownOpen.value = false;
};

const closeSubDropdown = () => {
    isSubDropdownOpen.value = false;
};

const toggleMainSpecialization = (speci) => {
    let updatedMainSpecs = [...selectedMainSpecializations.value];
    if (updatedMainSpecs.includes(speci)) {
        updatedMainSpecs = updatedMainSpecs.filter(s => s !== speci);
        // Remove related sub-specialties
        store.commit('updateFilter', {
            key: 'subSpecialities',
            value: selectedSubSpecialities.value.filter(sub =>
                availableSubSpecialties.value.includes(sub)
            )
        });
    } else {
        updatedMainSpecs.push(speci);
    }
    store.commit('updateFilter', { key: 'mainSpecializations', value: updatedMainSpecs });
};

const toggleSubSpecialty = (subSpec) => {
    let updatedSubSpecs = [...selectedSubSpecialities.value];
    if (updatedSubSpecs.includes(subSpec)) {
        updatedSubSpecs = updatedSubSpecs.filter(s => s !== subSpec);
    } else {
        updatedSubSpecs.push(subSpec);
    }
    store.commit('updateFilter', { key: 'subSpecialities', value: updatedSubSpecs });
};

const handleClickOutside = (event) => {
    if (mainDropdownRef.value && !mainDropdownRef.value.contains(event.target)) {
        closeMainDropdown();
    }
    if (subDropdownRef.value && !subDropdownRef.value.contains(event.target)) {
        closeSubDropdown();
    }
};

onMounted(async () => {
    specializations.value = await getSelectables('specialities');
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.specialization-selector {
    @apply space-y-4;
}
</style>
