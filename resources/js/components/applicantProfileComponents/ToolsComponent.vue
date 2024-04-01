<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Tools and Technologies</h1>
            <div v-for="(tool,index) in tools" :key="index" class="flex items-center justify-between w-full mt-3">
                <h1 class="font-semibold">{{ tool.tool }}</h1>
                <div class="flex items-center space-x-2">
                    <div :class="1 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                         class="rounded-full w-6 h-2"></div>
                    <div :class="2 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                         class="rounded-full w-6 h-2"></div>
                    <div :class="3 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                         class="rounded-full w-6 h-2"></div>
                    <div :class="4 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                         class="rounded-full w-6 h-2"></div>
                    <div :class="5 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                         class="rounded-full w-6 h-2"></div>
                </div>


            </div>

        </div>

    </div>
    <div v-else class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Tools and Technologies</h1>
            <div class="relative w-full">
                <div class="flex">
                    <input type="text" v-model="tool" name="tool" placeholder="Tools and technologies"
                           class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-bl-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>
                    <input type="number" v-model="efficiency" name="efficiency" placeholder="Efficiency"
                           class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-br-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>
                </div>
                <button type="submit" @click="addTool" @keyup:enter="addTool"
                        class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-orange rounded-e-lg  hover:bg-dark focus:outline-none">
                    +
                </button>
            </div>

        </div>
        <div v-for="(tool,index) in tools" :key="index" class="flex items-center justify-between w-full mt-6">
            <h1 class="font-semibold">{{ tool.tool }}</h1>
            <div class="flex items-center space-x-2">
                <div :class="1 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="2 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="3 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="4 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="5 <= tool.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
            </div>

            <button @click="deleteTool(index)"
                    class="bg-white focus:outline-none appearance-none text-sm hover:text-orange">
                X
            </button>


        </div>


    </div>


</template>

<script>
export default {
    name: "ToolsComponent",
    data() {
        return {
            tool: '',
            efficiency: null,
            tools: [{
                tool: 'English',
                efficiency: 2,
            }],
        }
    },
    methods: {
        addTool() {
            if (this.tool !== '' && this.efficiency && typeof this.efficiency === 'number' && this.efficiency >= 1 && this.efficiency <= 5) {
                const tool = {
                    tool: this.tool,
                    efficiency: this.efficiency
                }
                this.tools.push(tool)
                this.tool = ''
                this.efficiency = null
            } else {

            }

        },
        deleteTool(index) {
            this.tools.splice(index, 1)
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
