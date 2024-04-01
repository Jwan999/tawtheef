<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Employment</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div>
                <!--period of employment-->
                <div class="flex text-dark space-x-1 text-sm font-semibold">
                    <h1 class="text-orange">2019</h1>
                    <h1>-</h1>
                    <h1 class="text-orange">2022</h1>
                </div>
                <!--position-->
                <div class="flex items-center space-x-2 mb-3">
                    <h1 class="font-semibold text-lg">Graphic Designer</h1>
                    <h1>at</h1>
                    <h1 class="font-semibold italic text-orange text-sm">Employer</h1>
                </div>
                <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                    Aliquam
                    asperiores dicta dolore fuga harum id
                    incidunt, inventore iusto nemo pariatur provident, tempore temporibus voluptatibus?
                    Excepturi
                    nulla
                    odit
                    repellat? Aspernatur, facere!</p>
            </div>
            <div>
                <!--period of employment-->
                <div class="flex text-dark space-x-1 text-sm font-semibold">
                    <h1 class="text-orange">2019</h1>
                    <h1>-</h1>
                    <h1 class="text-orange">2022</h1>
                </div>
                <!--position-->
                <div class="flex items-center space-x-2 mb-3">
                    <h1 class="font-semibold text-lg">Graphic Designer</h1>
                    <h1>at</h1>
                    <h1 class="font-semibold italic text-orange text-sm">Employer</h1>
                </div>
                <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                    Aliquam
                    asperiores dicta dolore fuga harum id
                    incidunt, inventore iusto nemo pariatur provident, tempore temporibus voluptatibus?
                    Excepturi
                    nulla
                    odit
                    repellat? Aspernatur, facere!</p>
            </div>
        </div>

        <div v-else class="rounded-md bg-white p-4 space-y-8">

            <div v-for="index in employmentCount" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement==index ? borderColor : 'border-slate-100'">
                <div class="w-full">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-slate-100')"
                            @click="sectionEdit(index == 1 ? 'add':'remove')"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        {{ index === 1 ? 'Add component' : 'Remove component' }}

                    </button>
                    <div class="space-y-4 w-full p-6 pr-0">
                        <div class="flex space-x-3 items-center">
                            <input name="email" placeholder="Job title"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                            <input name="email" placeholder="Employer"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                            <input name="email" placeholder="Duration of employment"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-4/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                        </div>
                        <div class="w-full ">
                            <label for="message" class="block mb-2 text-xs font-medium text-dark">Lorem ipsum dolor sit
                                amet,
                                consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed
                                voluptatibus?</label>
                            <textarea id="message" rows="4"
                                      class="focus:border-orange focus:ring-0 block p-2.5 w-full text-sm bg-slate-50 rounded-md md:text-xs border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                      placeholder="Write your thoughts here..."></textarea>

                        </div>
                    </div>

                </div>
            </div>


        </div>

    </div>

</template>

<script>
export default {
    name: "EmploymentComponent",
    data() {
        return {
            hoveredElement: null,
            employmentCount: 1,
            borderColor: 'border-slate-100'
        };
    },
    methods: {
        changeBorderColor(index, color) {
            this.hoveredElement = index
            this.borderColor = color;
        },
        sectionEdit(action) {
            this.borderColor = 'border-dark'

            if (action == 'add') {
                this.employmentCount++
            } else if (action == 'remove') {
                this.employmentCount--
            }

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
