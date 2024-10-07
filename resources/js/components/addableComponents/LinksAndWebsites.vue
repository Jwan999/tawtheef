<template>
    <div class="mt-8 space-y-4">
        <div v-if="!editMode" class="w-full md:w-6/12 mt-6">
            <h2 class="text-lg font-semibold text-zinc-700 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Links & Websites
            </h2>

            <div class="space-y-3">
                <div v-for="(linkItem, index) in localLinks" :key="index"
                     class="flex items-center p-2 rounded-md transition-colors duration-200 hover:bg-orange hover:bg-opacity-10">
                    <div
                        class="flex-shrink-0 w-8 h-8 rounded-full bg-orange bg-opacity-20 flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </div>
                    <a :href="linkItem.link" target="_blank" rel="noopener noreferrer"
                       class="text-zinc-700 hover:text-orange transition-colors duration-200 text-sm font-medium">
                        {{ linkItem.label }}
                    </a>
                </div>
            </div>

            <div v-if="localLinks.length === 0" class="text-center py-4 text-sm text-zinc-700">
                No links added yet.
            </div>
        </div>

        <div v-else>
            <label for="link" class="mt-2 text-base">
                Add links to websites you want hiring managers to see (e.g., LinkedIn profile, portfolio, personal
                website).
            </label>

            <div class="relative flex mt-2">
                <input
                    type="text"
                    id="link"
                    v-model="link"
                    placeholder="www.linkedin.com/in/yourprofile"
                    @input="validateInputs"
                    class="flex-grow focus:border-orange focus:ring-0 bg-zinc-50 rounded-l-md md:text-sm text-sm border-0 border-b-[1px] border-zinc-300 hover:border-orange focus:outline-none p-2"
                />
                <input
                    type="text"
                    v-model="label"
                    placeholder="LinkedIn"
                    @input="validateInputs"
                    class="w-1/3 focus:border-orange focus:ring-0 bg-zinc-50 md:text-sm text-sm border-0 border-b-[1px] border-l-[1px] border-zinc-300 hover:border-orange focus:outline-none p-2"
                />
                <button
                    @click="addLink"
                    :disabled="!isValid"
                    :class="['px-4 py-2 text-sm font-semibold text-white rounded-r-md transition-colors duration-200 ease-in-out focus:outline-none',
                        isValid ? 'bg-orange hover:bg-dark' : 'bg-zinc-300 cursor-not-allowed']">
                    ADD MORE
                </button>
            </div>

            <transition-group name="list" tag="ul" class="space-y-2 mt-4">
                <li
                    v-for="(linkItem, index) in localLinks"
                    :key="linkItem.link"
                    class="flex bg-zinc-100 p-2 justify-between items-center text-orange font-semibold rounded">
                    <a
                        :href="linkItem.link"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="hover:underline truncate mr-2">
                        {{ linkItem.label }}
                    </a>
                    <button
                        @click="deleteLink(index)"
                        class="focus:outline-none appearance-none hover:text-red-500 transition-colors duration-200 ease-in-out"
                        aria-label="Delete link"
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
import {ref, watch, computed} from 'vue';
import {useStore} from 'vuex';

const store = useStore();
const editMode = computed(() => store.getters.editMode);

const props = defineProps(['links']);
const emit = defineEmits(['update:links']);

const localLinks = ref(props.links || []);
const link = ref('');
const label = ref('');
const isValid = ref(false);

const validateInputs = () => {
    isValid.value = link.value.trim() !== '' &&
        label.value.trim() !== '' &&
        isValidUrl(link.value);
};

const isValidUrl = (url) => {
    // Remove leading/trailing whitespace
    url = url.trim();

    // If the URL doesn't start with a protocol, add 'https://'
    if (!/^https?:\/\//i.test(url)) {
        url = 'https://' + url;
    }

    try {
        new URL(url);
        return true;
    } catch (e) {
        return false;
    }
};

const addLink = () => {
    if (isValid.value) {
        let finalLink = link.value.trim();
        if (!/^https?:\/\//i.test(finalLink)) {
            finalLink = 'https://' + finalLink;
        }
        localLinks.value.push({link: finalLink, label: label.value.trim()});
        emit('update:links', localLinks.value);
        link.value = '';
        label.value = '';
        isValid.value = false;
    }
};

const deleteLink = (index) => {
    localLinks.value.splice(index, 1);
    emit('update:links', localLinks.value);
};

watch(() => props.links, (newLinks) => {
    localLinks.value = newLinks || [];
}, {deep: true});
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
