<template>
    <div class="flex flex-wrap">
        <div :class="editMode ? 'md:w-3/12 w-full' : 'w-full'" class="flex space-x-3 items-center relative">
            <!-- Main upload container -->
            <div class="flex flex-col justify-center w-full items-center rounded-lg border-2 border-dashed transition-all duration-200"
                 :class="[
                     isDragging ? 'border-orange bg-orange' : 'border-zinc-300 hover:border-orange',
                     !editMode ? 'min-h-[20rem]' : 'md:min-h-[14rem] min-h-[24rem]'
                 ]"
                 @dragenter.prevent="isDragging = true"
                 @dragleave.prevent="isDragging = false"
                 @dragover.prevent="isDragging = true"
                 @drop.prevent="handleDrop">

                <!-- Image Preview -->
                <div v-if="displayImage" class="relative w-full h-full">
                    <img class="object-cover rounded-lg w-full"
                         :class="!editMode ? 'h-[20rem]' : 'md:h-[14rem] h-[24rem]'"
                         :src="displayImage"
                         alt="Profile photo">

                    <!-- Remove button -->
                    <button v-if="editMode"
                            @click="removeImage"
                            class="absolute top-2 right-2 p-1.5 bg-orange text-white rounded-full hover:bg-orange-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Upload Interface -->
                <div v-else class="text-center p-6">
                    <!-- Default user icon -->
                    <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0 2a8 8 0 0 1 8 8H4a8 8 0 0 1 8-8z"/>
                    </svg>

                    <!-- Upload prompt -->
                    <div class="mt-4" v-if="editMode">
                        <label class="cursor-pointer">
                            <span class="mt-2 block text-sm text-zinc-900">
                                Drag and drop your photo here, or
                                <span class="text-orange hover:text-orange font-medium">browse files</span>
                            </span>
                            <input type="file"
                                   ref="fileInput"
                                   class="hidden"
                                   @change="handleFileUpload"
                                   accept="image/*">
                        </label>
                        <p class="mt-1 text-xs text-zinc-500">PNG, JPG up to 5MB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions Panel -->
        <div v-if="editMode" class="md:w-9/12 w-full p-4 md:pt-0 space-y-3">
            <h2 class="text-lg font-semibold text-zinc-900">Photo Requirements:</h2>
            <ul class="space-y-2">
                <li v-for="(instruction, index) in instructions"
                    :key="index"
                    class="flex items-center text-sm text-zinc-600">
                    <div class="w-1.5 h-1.5 rounded-full bg-orange mr-2"></div>
                    {{ instruction }}
                </li>
            </ul>

            <!-- Error Alert -->
            <div v-if="error"
                 class="mt-2 p-3 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
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
        const fileInput = ref(null);
        const isDragging = ref(false);
        const error = ref('');
        const previewURL = ref('');

        const instructions = [
            'Center your face in the frame',
            'Use good lighting for clear visibility',
            'Individual photo only (no group photos)',
            'Professional appearance recommended'
        ];

        const editMode = computed(() => store.getters.editMode);

        const displayImage = computed(() => {
            if (previewURL.value) {
                return previewURL.value;
            } else if (props.modelValue) {
                if (typeof props.modelValue === 'string') {
                    return props.modelValue.startsWith('data:')
                        ? props.modelValue
                        : `/storage/${props.modelValue}`;
                } else if (props.modelValue instanceof File) {
                    return URL.createObjectURL(props.modelValue);
                }
            }
            return '';
        });

        const validateFile = (file) => {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!validTypes.includes(file.type)) {
                error.value = 'Please upload a valid image file (JPG or PNG)';
                return false;
            }

            if (file.size > maxSize) {
                error.value = 'File size should be less than 5MB';
                return false;
            }

            error.value = '';
            return true;
        };

        const handleFile = (file) => {
            if (validateFile(file)) {
                previewURL.value = URL.createObjectURL(file);
                emit('update:modelValue', file);
            }
        };

        const handleFileUpload = (event) => {
            const file = event.target.files[0];
            if (file) {
                handleFile(file);
            }
        };

        const handleDrop = (event) => {
            isDragging.value = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                handleFile(file);
            }
        };

        const removeImage = () => {
            previewURL.value = '';
            emit('update:modelValue', null);
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        };

        return {
            fileInput,
            isDragging,
            error,
            displayImage,
            editMode,
            instructions,
            handleFileUpload,
            handleDrop,
            removeImage
        };
    }
};
</script>
