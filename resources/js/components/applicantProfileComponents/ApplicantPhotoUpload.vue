<template>
    <div class="relative flex flex-col items-center">
        <div class="flex justify-center w-full items-center bg-white h-56 rounded-md dark:bg-zinc-600">
            <div class="rounded-md w-full" v-if="displayImage">
                <img class="h-56 object-cover w-full rounded-md"
                     :src="displayImage" alt="Uploaded image">
            </div>
            <svg v-else class="h-32 fill-dark" viewBox="-42 0 512 512.002" xmlns="http://www.w3.org/2000/svg">
                <!-- SVG path data remains unchanged -->
            </svg>
        </div>

        <div v-if="editMode" class="absolute top-0 right-0 mt-[2.5px]">
            <label for="fileInput"
                   class="appearance-none relative text-sm rounded-bl-md rounded-tr-md px-3 py-2 font-semibold text-white bg-orange hover:bg-dark cursor-pointer">
                Upload photo
            </label>
            <input id="fileInput" type="file" @change="previewImage" class="absolute left-0 hidden" accept="image/*">
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import store from "../../store/index.js";

const props = defineProps(["modelValue"]);
const emit = defineEmits(["update:modelValue"]);

const previewURL = ref('');

const image = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const displayImage = computed(() => {
    return previewURL.value || (image.value ? `/storage/${image.value}` : '');
});

const previewImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewURL.value = e.target.result;
            image.value = file;
        };
        reader.readAsDataURL(file);
    }
};

const editMode = computed(() => store.getters.editMode);

watch(() => props.modelValue, (newValue) => {
    if (!newValue) {
        previewURL.value = '';
    }
});
</script>

<style scoped>
/* Your scoped styles here */
</style>
