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

        <div class="space-y-5 text-sm md:text-xs ">
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input @input="emitInputData" v-model="university" name="university" placeholder="University"
                       class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input @input="emitInputData" v-model="degree" name="degree" placeholder="Degree"
                       class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input @input="emitInputData" v-model="college" name="college" placeholder="College"
                       class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
            <div class="relative">
                <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                <input @input="emitInputData" v-model="year" name="date" placeholder="Year"
                       class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
<!--                <h1 class="text-red-500 text-xs mt-1 font-semibold">This field is required.</h1>-->

            </div>
        </div>


    </div>

</template>

<script>
export default {
    name: "EducationComponent",
    data() {
        return {
            university: '',
            degree: '',
            college: '',
            year: '',
        }
    },
    computed: {
        editMode() {
            return this.$store.getters.editMode;
        }
    },
    methods: {
        emitInputData() {
            this.$emit('educationUpdated', {
                university: this.university,
                degree: this.degree,
                college: this.college,
                year: this.year
            })
        }
    }
}
</script>

<style scoped>

</style>
