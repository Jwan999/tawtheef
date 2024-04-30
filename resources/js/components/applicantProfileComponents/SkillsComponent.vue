<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">
        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Skills</h1>

            <div class="flex flex-wrap items-center space-y-2">
                <div class="flex flex-wrap gap-2">
                    <span v-for="skill in skills"
                          class="inline-block bg-dark text-white px-3 py-1 rounded-full uppercase font-semibold text-xs">
                        {{ skill }}
                    </span>
                </div>
            </div>

        </div>
    </div>
    <div v-else class="rounded-md p-4 bg-white text-sm md:text-xs">
        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Skills</h1>
            <div class="space-y-3">


                <div class="relative w-full">

                    <div class="relative w-full">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <input type="text" v-model="skill" name="skill" placeholder="Skills"
                               class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>

                    </div>

                    <button type="submit" @click="addSkill" @keyup:enter="addSkill"
                            class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-orange rounded-e-md  hover:bg-dark focus:outline-none">
                        <svg class="w-2 h-2 fill-white" viewBox="0 0 512 512">
                            <g>
                                <path
                                    d="m467 211h-166v-166c0-24.853-20.147-45-45-45s-45 20.147-45 45v166h-166c-24.853 0-45 20.147-45 45s20.147 45 45 45h166v166c0 24.853 20.147 45 45 45s45-20.147 45-45v-166h166c24.853 0 45-20.147 45-45s-20.147-45-45-45z"/>
                            </g>
                        </svg>
                    </button>
                </div>
<!--                <h1 class="text-red-500 text-xs font-semibold">This field is required.</h1>-->


                <div class="flex flex-wrap gap-2">
                    <span v-for="(skill,index) in skills" :key="index"
                          class="inline-block bg-dark text-white px-3 py-1 rounded-full uppercase font-semibold text-xs">
                        <button @click="removeSkill(index)" class="appearance-none mr-2">
                            x
                        </button>
                        {{ skill }}
                    </span>
                </div>
            </div>


        </div>
    </div>

</template>

<script>
export default {
    name: "SkillsComponent",
    data() {
        return {
            skill: '',
            skills: [],
        }
    },
    methods: {
        addSkill() {
            if (this.skill != '') {
                this.skills.push(this.skill)
                this.skill = ''
                this.emitInputData()

            }
        },
        removeSkill(index) {
            this.skills.splice(index, 1)
        },
        emitInputData() {
            this.$emit('skillsUpdated', {skill: this.skill})
        }
    },
    computed: {
        editMode() {
            return this.$store.getters.editMode;
        }
    }
}
</script>

<style scoped>

</style>
