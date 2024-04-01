<template>
    <div class="w-full space-y-4">
        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Courses</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div>
                <h1 class="text-sm font-semibold mb-1">3 months</h1>
                <h1 class="text-orange text-lg font-semibold mb-4">Graphic design</h1>
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
                <h1 class="text-sm font-semibold mb-1">3 months</h1>
                <h1 class="text-orange text-lg font-semibold mb-4">Graphic design</h1>
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

        <div v-else class="rounded-md bg-white p-4 space-y-4">

            <div v-for="index in courseCount" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement==index ? borderColor : 'border-slate-100'">
                <div class="w-full">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-slate-100')"
                            @click="sectionEdit(index == 1 ? 'add':'remove')"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        {{ index === 1 ? 'Add component' : 'Remove component' }}

                    </button>

                    <div class="w-full space-y-4 p-6 pr-0">
                        <div class="flex space-x-3 items-center ">
                            <input name="email" placeholder="Course title"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                            <input name="email" placeholder="Course duration"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                        </div>
                        <div class="w-full">
                            <label for="message" class="block mb-2 text-xs font-medium text-dark">Lorem ipsum dolor sit
                                amet, consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed
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
    name: "CoursesComponent",
    data() {
        return {
            hoveredElement: null,
            courseCount: 1,
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
                this.courseCount++
            } else if (action == 'remove') {
                this.courseCount--
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
