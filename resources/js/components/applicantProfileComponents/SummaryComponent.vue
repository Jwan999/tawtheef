<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Summary</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Aliquam
                asperiores dicta dolore fuga harum id
                incidunt, inventore iusto nemo pariatur provident, tempore temporibus voluptatibus? Excepturi
                nulla
                odit
                repellat? Aspernatur, facere!</p>

        </div>
        <div v-else class="rounded-md p-4 bg-white">

            <label for="message" class="block mb-2 text-xs font-medium text-dark">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed voluptatibus?</label>
            <textarea id="message" rows="4" class="focus:border-orange focus:ring-0 block p-2.5 w-full text-sm bg-slate-50 rounded-md md:text-xs border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none" placeholder="Write your thoughts here..."></textarea>


        </div>
    </div>

</template>

<script>
export default {
    name: "SummaryComponent",
    computed: {
        editMode() {
            return this.$store.getters.editMode;
        }
    }
}
</script>

<style scoped>

</style>
