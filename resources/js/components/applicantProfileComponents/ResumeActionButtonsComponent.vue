<template>
    <div
        class="md:flex-row-reverse flex-wrap md:items-center md:space-y-0 space-y-3 w-full md:justify-between justify-end">
        <div class="space-y-3">
            <div class="w-full" v-if="isEditable">
                <button
                    v-if="editMode"
                    @click="saveResume"
                    class="appearance-none w-full mt-3 text-sm font-semibold tracking-wide text-orange hover:text-white py-3 hover:bg-dark border-[1px] border-orange hover:border-zinc-700">
                    Save
                </button>


                <button v-if="!editMode"
                        @click="togglePreviewMode"
                        class="appearance-none mt-3 w-full text-sm font-semibold tracking-wide text-zinc-700 hover:text-white py-3 hover:bg-dark border-[1px] border-zinc-700">
                    Edit
                </button>
                <a v-else :href="`/preview/${currentApplicantId}`">
                    <button
                        class="appearance-none mt-3 w-full text-sm font-semibold tracking-wide text-zinc-700 hover:text-white py-3 hover:bg-dark border-[1px] border-zinc-700">
                        Preview
                    </button>
                </a>

                <button v-if="editMode" @click="handlePublishResume"
                        class="appearance-none mt-3 w-full text-sm font-semibold tracking-wide text-zinc-700 hover:text-white py-3 hover:bg-dark border-[1px] border-zinc-700">
                    <span v-if="!published">Publish</span>
                    <span v-else>Unpublish</span>
                </button>

            </div>
            <div class="space-y-3" v-if="!editMode">
                <GeneratePDFButton/>
            </div>
        </div>

    </div>
</template>

<script setup>
import {computed, ref, onMounted, onUpdated} from "vue";
import store from '../../store/index.js';
import {useRoute, useRouter} from "vue-router";
import PublishState from "./PublishState.vue";
import GeneratePDFButton from "./GeneratePDFButton.vue";

const props = defineProps(["published"]);
const emit = defineEmits(["publishResume", "saveResume", "validateAndPublish"]);

const currentApplicantId = computed(() => store.getters.getCurrentApplicantId);

const editMode = computed(() => store.getters.editMode);
const isEditable = computed(() => {
    // Make sure this is comparing applicant IDs, not user IDs
    return route.params.id == store.getters.getCurrentApplicantId;
});

const published = ref(false);

const route = useRoute();
const router = useRouter();

const saveResume = () => {
    emit('saveResume');
};

const handlePublishResume = () => {
    emit('validateAndPublish');
};

const togglePreviewMode = () => {
    if (editMode.value) {
        store.dispatch('setEditMode', false);
    } else {
        editResume();
    }
};

const editResume = () => {
    router.push({name: 'profile-view'});
    store.dispatch('setEditMode', true);
};

onMounted(() => {
    published.value = props?.published;
});

onUpdated(() => {
    published.value = props?.published;
});
</script>
