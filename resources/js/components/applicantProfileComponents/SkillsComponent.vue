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
                    <input type="text" v-model="skill" name="skill" placeholder="Skills"
                           class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>
                    <button type="submit" @click="addSkill" @keyup:enter="addSkill"
                            class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-orange rounded-e-lg  hover:bg-dark focus:outline-none">
                        +
                    </button>
                </div>

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
            }
        },
        removeSkill(index) {
            this.skills.splice(index, 1)
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
