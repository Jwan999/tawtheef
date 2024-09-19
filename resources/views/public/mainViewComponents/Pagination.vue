<template>
    <div class="flex items-center justify-between border-t border-b border-zinc-200 bg-white px-4 py-3 md:px-16">
        <!-- Mobile view pagination -->
        <div class="flex flex-1 justify-between md:hidden">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                    class="relative inline-flex items-center rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Previous
            </button>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === lastPage"
                    class="relative ml-3 inline-flex items-center rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
            </button>
        </div>
        <!-- Desktop view pagination -->
        <div class="hidden md:flex md:items-center md:justify-between"
             :class="isAdvanceSearchInUse ? 'md:w-8/12' : 'w-full'">
            <div>
                <p class="text-sm text-zinc-700">
                    All results:
                    <span class="font-medium">{{ totalItems }}</span>
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <!-- Previous page button -->
                    <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                            class="cursor-pointer relative inline-flex items-center rounded-l-md px-2 py-1 text-zinc-400 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <!-- Page numbers -->
                    <button v-for="page in visiblePages" :key="page" @click="changePage(page)"
                            :class="[
                                page === currentPage
                                    ? 'relative z-10 inline-flex items-center bg-orange px-4 py-1 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange'
                                    : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-zinc-900 ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0',
                            ]">
                        {{ page }}
                    </button>
                    <!-- Next page button -->
                    <button @click="changePage(currentPage + 1)" :disabled="currentPage === lastPage"
                            class="cursor-pointer relative inline-flex items-center rounded-r-md px-2 py-1 text-zinc-400 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
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
import store from '../../../js/store/index'

const isAdvanceSearchInUse = computed(() => store.state.advanceSearchInUse);
const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    lastPage: {
        type: Number,
        required: true
    },
    perPage: {
        type: Number,
        required: true
    },
    totalItems: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['changePage']);

// Calculate visible page numbers
const visiblePages = computed(() => {
    const delta = 2;
    const range = [];
    const rangeWithDots = [];
    let l;

    range.push(1);

    for (let i = props.currentPage - delta; i <= props.currentPage + delta; i++) {
        if (i < props.lastPage && i > 1) {
            range.push(i);
        }
    }

    range.push(props.lastPage);

    for (let i of range) {
        if (l) {
            if (i - l === 2) {
                rangeWithDots.push(l + 1);
            } else if (i - l !== 1) {
                rangeWithDots.push('...');
            }
        }
        rangeWithDots.push(i);
        l = i;
    }

    return rangeWithDots;
});

// Handle page change
const changePage = (page) => {
    if (page >= 1 && page <= props.lastPage && page !== props.currentPage) {
        emit('changePage', page);
    }
};
</script>
