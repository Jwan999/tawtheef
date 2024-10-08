<template>
    <div class="flex flex-wrap">
        <div :class="editMode ? 'md:w-3/12 w-full' : 'w-full'" class="flex space-x-3 items-center relative">
            <div class="flex justify-center w-full items-center rounded-md dark:bg-zinc-600">
                <div class="rounded-md w-full " v-if="displayImage">
                    <img class="h-56 w-full object-cover rounded-lg"
                         :src="displayImage" alt="Uploaded image">
                </div>
                <svg v-else class="h-32 fill-dark" viewBox="-42 0 512 512.002" xmlns="http://www.w3.org/2000/svg">
                    <path d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/>
                    <path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/>
                </svg>
            </div>
            <div v-if="editMode" class="absolute top-0 right-0 mt-[4px]">
                <label for="fileInput"
                       class="appearance-none relative text-sm rounded-bl-md rounded-tr-md px-3 py-2 font-semibold text-white bg-orange hover:bg-dark cursor-pointer">
                    Upload photo
                </label>
                <input id="fileInput" type="file" @change="handleFileUpload" class="absolute left-0 hidden"
                       accept="image/*">
            </div>
        </div>
        <div v-if="editMode" class="md:w-9/12 w-full mb-4 p-4 md:pt-0 rounded-md">
            <h2 class="text-lg font-semibold mb-2">Photo Upload Instructions:</h2>
            <ul class="list-disc list-inside text-sm">
                <li>Ensure your face is centered.</li>
                <li>Upload a clear headshot photo with proper light.</li>
                <li>The photo should be a square to 6 within the square box.</li>
                <li>Avoid using group photos or images with multiple people.</li>
            </ul>
        </div>
    </div>
</template>

<script>
import { computed, onMounted, ref, watch } from "vue";
import { useStore } from 'vuex';

export default {
    props: {
        modelValue: {
            type: [String, File, null],
            default: null
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const store = useStore();
        const previewURL = ref('');

        const displayImage = computed(() => {
            if (previewURL.value) {
                return previewURL.value;
            } else if (props.modelValue) {
                if (typeof props.modelValue === 'string') {
                    return props.modelValue.startsWith('data:') ? props.modelValue : `/storage/${props.modelValue}`;
                } else if (props.modelValue instanceof File) {
                    return URL.createObjectURL(props.modelValue);
                }
            }
            return '';
        });

        const handleFileUpload = (event) => {
            const file = event.target.files[0];
            if (file) {
                previewURL.value = URL.createObjectURL(file);
                emit('update:modelValue', file);
            }
        };

        const editMode = computed(() => store.getters.editMode);

        const checkEditMode = (url) => {
            store.dispatch('checkEditMode', url);
        };

        watch(() => props.modelValue, (newValue) => {
            if (!newValue) {
                previewURL.value = '';
            } else if (typeof newValue === 'string' && !newValue.startsWith('data:')) {
                previewURL.value = '';
            } else if (newValue instanceof File) {
                previewURL.value = URL.createObjectURL(newValue);
            }
        });

        onMounted(() => {
            const currentPath = window.location.pathname;
            checkEditMode(currentPath);
        });

        return {
            displayImage,
            handleFileUpload,
            editMode,
            checkEditMode,
        };
    }
};
</script>

<style scoped>
/* You can add any additional styles here if needed */
</style>
