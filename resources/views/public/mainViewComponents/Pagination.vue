<template>
    <div class="flex items-center justify-between border-t border-b border-zinc-200 bg-white px-4 py-3 md:px-16">
        <div class="flex flex-1 justify-between md:hidden">
            <button @click="$emit('changePage', currentPage - 1)" :disabled="currentPage === 1"
                    class="relative inline-flex items-center rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">
                Previous
            </button>
            <button @click="$emit('changePage', currentPage + 1)" :disabled="currentPage === lastPage"
                    class="relative ml-3 inline-flex items-center rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">
                Next
            </button>
        </div>
        <div class="hidden md:flex md:flex-1 md:items-center md:justify-between">
            <div>
                <p class="text-sm text-zinc-700">
                    <!--                    Showing-->
                    <!--                    <span class="font-medium">{{ (currentPage - 1) * perPage + 1 }}</span>-->
                    <!--                    to-->
                    <!--                    <span class="font-medium">{{ Math.min(currentPage * perPage, totalItems) }}</span>-->
                    All results:
                    <span class="font-medium">{{ totalItems }}</span>

                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <button @click="$emit('changePage', currentPage - 1)" :disabled="currentPage === 1"
                            class="cursor-pointer relative inline-flex items-center rounded-l-md px-2 py-1 text-zinc-400 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <button v-for="page in visiblePages" :key="page" @click="$emit('changePage', page)"
                            :class="[
            page === currentPage ? 'relative z-10 inline-flex items-center bg-orange px-4 py-1 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange' : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-zinc-900 ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0',
          ]">
                        {{ page }}
                    </button>
                    <button @click="$emit('changePage', currentPage + 1)" :disabled="currentPage === lastPage"
                            class="cursor-pointer relative inline-flex items-center rounded-r-md px-2 py-1 text-zinc-400 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';

const props = defineProps({
    currentPage: Number,
    lastPage: Number,
    perPage: Number,
    totalItems: Number,
});

const emit = defineEmits(['changePage']);

const visiblePages = computed(() => {
    const delta = 2;
    const range = [];
    for (let i = Math.max(2, props.currentPage - delta); i <= Math.min(props.lastPage - 1, props.currentPage + delta); i++) {
        range.push(i);
    }

    if (props.currentPage - delta > 2) {
        range.unshift("...");
    }
    if (props.currentPage + delta < props.lastPage - 1) {
        range.push("...");
    }

    range.unshift(1);
    if (props.lastPage !== 1) {
        range.push(props.lastPage);
    }

    return range;
});
</script>
