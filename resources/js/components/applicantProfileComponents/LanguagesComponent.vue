<template>
    <div v-if="!editMode" class="rounded-md p-4 bg-white text-sm md:text-xs">

        <div class="space-y-1">
            <h1 class="text-lg font-semibold text-dark pb-4">Languages</h1>
            <div v-for="language in languages" :key="index" class="flex items-center justify-between">
                <h1 class="font-semibold">{{ language.language }}</h1>
                <div class="flex items-center space-x-2">
                    <div :class="efficiency > 1 ? 'bg-orange' : 'bg-light'"
                         class="bg-orange rounded-full w-6 h-2"></div>

                    <div :class="efficiency > 1 ? 'bg-orange' : 'bg-light'"
                         class="bg-orange rounded-full w-6 h-2"></div>
                    <div :class="efficiency > 1 ? 'bg-orange' : 'bg-light'"
                         class="bg-orange rounded-full w-6 h-2"></div>
                    <div :class="efficiency > 1 ? 'bg-orange' : 'bg-light'"
                         class="bg-orange rounded-full w-6 h-2"></div>
                    <div :class="efficiency > 1 ? 'bg-orange' : 'bg-light'"
                         class="bg-orange rounded-full w-6 h-2"></div>
                </div>
            </div>
        </div>

    </div>
    <div v-else class="rounded-md py-4 bg-white text-sm md:text-xs">

        <div class="space-y-3 px-4 text-sm md:text-xs">
            <h1 class="text-lg font-semibold text-dark pb-3">Languages</h1>

            <div class="relative w-full">
                <div class="flex">
                    <input type="text" v-model="language" name="language" placeholder="Languages"
                           class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-bl-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>
                    <input type="number" v-model="efficiency" name="efficiency" placeholder="Efficiency"
                           class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-br-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>

                </div>
                <button type="submit" @click="addLanguage" @keyup:enter="addLanguage"
                        class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-orange rounded-e-lg  hover:bg-dark focus:outline-none">
                    +
                </button>
            </div>

        </div>

        <div v-for="(language,index) in languages" :key="index"
             class="flex items-center justify-between w-full px-4 mt-6">
            <h1 class="font-semibold">{{ language.language }}</h1>
            <div class="flex items-center space-x-2">
                <div :class="1 <= language.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="2 <= language.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="3 <= language.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="4 <= language.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
                <div :class="5 <= language.efficiency ? 'bg-orange' : 'bg-light'"
                     class="rounded-full w-6 h-2"></div>
            </div>

            <button @click="deleteLanguage(index)"
                    class="bg-white focus:outline-none appearance-none text-sm hover:text-orange">
                X
            </button>


        </div>


    </div>


</template>

<script>
export default {
    name: "LanguagesComponent",
    data() {
        return {
            language: '',
            efficiency: null,
            languages: [{
                language: 'English',
                efficiency: 2,
            }],
        }
    },
    methods: {
        addLanguage() {
            if (this.language !== '' && this.efficiency && typeof this.efficiency === 'number' && this.efficiency >= 1 && this.efficiency <= 5) {
                const language = {
                    language: this.language,
                    efficiency: this.efficiency
                }
                this.languages.push(language)
                this.language = ''
                this.efficiency = null
            } else {

            }

        },
        deleteLanguage(index) {
            this.languages.splice(index, 1)
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
