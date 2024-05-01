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
                          class="inline-block flex items-center bg-dark text-white px-3 py-1 rounded-full uppercase font-semibold text-xs">
                        <button @click="removeSkill(index)" class="appearance-none mr-2">
                             <svg class="fill-white w-4 h-4" viewBox="0 0 1024 1024"
                                  xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                        </svg>
                        </button>
                        <span>{{ skill }}</span>
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
