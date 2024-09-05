<template>
    <div class="flex flex-col items-start md:mt-0 mt-2">
        <div class="flex items-center">
            <div
                class="p-1 rounded-full mr-2"
                :class="[
          isPublished ? 'bg-orange-100' : 'bg-zinc-200'
        ]"
            >
                <div
                    class="w-3 h-3 rounded-full"
                    :class="[
            isPublished ? 'bg-orange' : 'bg-zinc-700'
          ]"
                ></div>
            </div>
            <span
                class="font-semibold text-sm"
                :class="[
          isPublished ? 'text-orange' : 'text-zinc-700'
        ]"
            >
        {{ isPublished ? 'Resume is Published' : 'Resume is not Published' }}
      </span>
        </div>
        <p v-if="isPublished" class="mt-1 text-xs text-zinc-500">
            Note: Everyone can see your published resume.
        </p>
    </div>
</template>

<script>
export default {
    props: {
        isPublished: {
            type: Boolean,
            required: true
        }
    }
}
</script>
