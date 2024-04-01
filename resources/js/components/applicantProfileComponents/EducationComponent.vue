<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Education</h1>

            <h1 class="font-semibold">University of Baghdad</h1>
            <h1 class="text-dark font-semibold">Bachelor's degree,
                <span class="">College of Languages</span>
            </h1>
            <h1 class="text-orange font-semibold">2016 - 2020</h1>
        </div>

    </div>

    <div v-else class="rounded-md p-4 bg-white text-sm md:text-xs">
        <h1 class="text-lg font-semibold text-dark pb-4">Education</h1>

        <div class="space-y-3 text-sm md:text-xs ">

            <input name="email" placeholder="University"
                   class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                   type="text">
            <input name="phone" placeholder="Degree"
                   class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                   type="text">
            <input name="links" placeholder="College"
                   class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                   type="text">
            <input name="links" placeholder="Year"
                   class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                   type="text">
        </div>


    </div>

</template>

<script>
export default {
    name: "EducationComponent",
    computed: {
        editMode() {
            return this.$store.getters.editMode;
        }
    }
}
</script>

<style scoped>

</style>
