<template>
    <div class="w-full space-y-4">

        <div class="flex items-center space-x-3">
            <h1 class="flex-none text-lg font-semibold text-dark">Events and Activities</h1>
            <hr class="h-px w-full bg-orange border-0 mt-1">
        </div>

        <div v-if="!editMode" class="rounded-md p-4 bg-white space-y-8">
            <div>
                <h1 class="text-orange font-semibold text-lg mb-4">2019</h1>
                <ul class="text-sm">
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Speaker</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Attendee</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Volunteer</h1>
                    </li>
                </ul>
            </div>
            <div>
                <h1 class="text-orange font-semibold text-lg mb-4">2019</h1>
                <ul class="text-sm">
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Speaker</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Attendee</h1>
                    </li>
                    <li class="flex space-x-2 items-center ml-5">
                        <h1 class="font-semibold">Some Event</h1>
                        <h1>as</h1>
                        <h1 class="text-orange font-semibold">Volunteer</h1>
                    </li>
                </ul>
            </div>

        </div>



        <div v-else class="rounded-md bg-white space-y-4 p-4">


            <div v-for="index in activityCount" :key="index"
                 class="rounded-bl-md border-0 border-l-[1px] border-b-[1px]"
                 :class="hoveredElement==index ? borderColor : 'border-slate-100'">
                <div class="w-full">
                    <button @mouseover="changeBorderColor(index,'border-dark')"
                            @mouseleave="changeBorderColor(index,'border-slate-100')"
                            @click="sectionEdit(index == 1 ? 'add':'remove')"
                            class="flex-none w-auto appearance-none px-3 py-1 rounded-br-md font-semibold text-start text-orange bg-slate-100 hover:bg-dark hover:text-white text-sm">
                        {{ index === 1 ? 'Add component' : 'Remove component' }}

                    </button>

                    <div class="w-full p-6 pr-0">
                        <label for="message" class="block mb-2 text-xs font-medium text-dark">Lorem ipsum dolor
                            sit amet,
                            consectetur adipisicing elit. Blanditiis culpa ea hic illo nihil sed voluptatibus?</label>

                        <div class="flex space-x-3 items-center">
                            <input name="email" placeholder="Activity or event title"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                            <input name="email" placeholder="participated as"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                            <input name="email" placeholder="Year"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-6/12 rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                                   type="text">
                        </div>
                    </div>



                </div>
            </div>


        </div>


    </div>

</template>

<script>
export default {
    name: "ActivitiesComponent",
    data() {
        return {
            hoveredElement: null,
            activityCount: 1,
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
                this.activityCount++
            } else if (action == 'remove') {
                this.activityCount--
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
