<template>
    <div>
        <div class="flex justify-end">

            <template v-if="isEditable">
                <button
                    @click="!editMode ? editResume() :emit('saveResume')"
                    class="appearance-none text-sm rounded-bl-md px-3 py-2 font-semibold text-white bg-orange hover:bg-dark">
                    <svg v-if="!editMode" class="fill-white h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="m21.783 3.766-1.548-1.548c-1.088-1.088-2.858-1.088-3.946 0l-13.939 13.937c-.092.092-.151.211-.169.339l-.774 5.42c-.027.187.036.375.169.509.113.113.266.176.424.176.028 0 .057-.002.085-.006l5.42-.774c.128-.018.248-.078.339-.169l13.938-13.938c1.088-1.088 1.088-2.858 0-3.946zm-14.645 16.894-4.431.633.633-4.431 11.364-11.363 3.797 3.797-11.364 11.364zm13.796-13.796-1.584 1.584-3.797-3.797 1.584-1.584c.62-.62 1.629-.62 2.249 0l1.548 1.548c.62.62.62 1.629 0 2.249z"/>
                    </svg>
                    <span v-else>Save</span>
                </button>

                <button @click="publishResume"
                        class="appearance-none text-sm px-3 py-2 font-semibold text-zinc-500 bg-zinc-200 hover:bg-dark hover:text-white">
                    <span v-if="!published">Publish</span>
                    <span v-else>Un publish</span>
                </button>
            </template>
            <!--todo show that it works only when you signed in-->
            <button @click="downloadPDF"
                    :class="!routeName == 'resume-view' ? '':'rounded-bl-md'"
                    class="appearance-none text-sm px-3 py-2 font-semibold hover:bg-dark hover:text-white text-zinc-500 bg-zinc-200">
                <span>Download PDF</span>
            </button>
        </div>

        <div v-if="editMode" class="flex justify-end mt-2">
            <p class="w-7/12 text-sm font-medium text-zinc-600">When you publish your resume it will be available for
                the public
                to see.
            </p>
        </div>
    </div>


</template>

<script setup>

import {computed, onMounted, onUpdated, ref, watch} from "vue";
import store from '../../store/index.js';
import {useRoute, useRouter} from "vue-router";
import {getSelectables} from "../../utils/storeHelpers.js";

const isEditable = computed(() => {
    // different id data type
    return route.params.id == store.getters.user?.applicant.id;
})
const editMode = computed(() => store.getters.editMode)

const emit = defineEmits(["publishResume", "saveResume"])
const props = defineProps(["published"])
const published = ref(false)
const userId = computed(() => {
    return store.getters.user?.id;
});
const router = useRouter();

const downloadPDF = () => {
    if (userId.value) {
        window.print()
    } else {
        window.location.href = '/login';
    }
}
const saveResume = () => {
    console.log('testing save')
    emit('saveResume')
    toggleEditMode()
};
const publishResume = () => {
    published.value = !published.value
    emit('publishResume', published)
}
const editResume = () => {
    router.push({name: 'profile-edit'});
    toggleEditMode()
}
const toggleEditMode = () => {
    store.dispatch('setEditMode', !editMode.value);
};

const routeName = computed(() => {
    return route.name;
})

const route = useRoute();

onMounted(async () => {
    published.value = props?.published;
});

onUpdated(() => {
    published.value = props?.published;
});

</script>

<style scoped>

</style>
