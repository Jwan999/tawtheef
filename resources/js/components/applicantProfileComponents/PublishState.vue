<template>
    <div class="flex justify-start md:mt-0 mt-2">
        <div class="">
            <div class="flex items-center">
        <span class="font-semibold md:text-base"
            :class="[
            'inline-flex items-center rounded-full px-2.5 py-0.5 text-md font-medium',
            isPublished
              ? 'bg-orange-50 text-orange'
              : 'bg-zinc-200 text-zinc-800'
          ]"
        >
          {{ isPublished ? 'Published' : 'Not Published' }}
        </span>

            </div>
            <p v-if="isPublished" class="mt-1 text-xs text-zinc-500">
                Note: Everyone can see your published resume.
            </p>
        </div>
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
