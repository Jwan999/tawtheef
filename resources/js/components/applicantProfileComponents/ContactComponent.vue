<template>
    <!--preview-->
    <div v-if="!editMode" class=" rounded-md py-4 bg-white">

        <div class="space-y-1 px-4 text-sm md:text-xs">
            <h1 class="">jwanaalfatla1999@gmail.com</h1>
            <h1 class="">+964 781 615 1297</h1>
            <h1 v-for="(link, index) in links" :key="index" class="text-orange font-semibold underline">{{ link }}</h1>
        </div>

    </div>
    <!--form-->
    <div v-else>
        <div class="rounded-md py-4 bg-white">

            <div class="space-y-3 px-4 text-sm md:text-xs">
                <input name="email"
                       placeholder="Email Address"
                       class="bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                       type="text">

                <input name="phone" placeholder="Phone number"
                       class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                       type="text">
                <div class="relative w-full">
                    <input type="text" v-model="link" name="link" placeholder="Links"
                           class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>

                    <button type="submit" @click="addLink" @keyup:enter="addLink"
                            class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-orange rounded-e-lg  hover:bg-dark focus:outline-none">
                        +
                    </button>
                </div>
                <h1 v-for="(link, index) in links" :key="index"
                    class="flex justify-between text-orange font-semibold underline mt-6">
                    <span>{{ link }}</span>
                    <button @click="deleteLink(index)"
                            class="bg-white text-dark focus:outline-none appearance-none text-sm hover:text-orange">
                        X
                    </button>
                </h1>


            </div>

        </div>

    </div>
</template>

<script>
export default {
    name: "ContactComponent",
    data() {
        return {
            link: '',
            links: [],
        }
    },
    methods: {
        addLink() {
            if (this.link != '') {
                this.links.push(this.link)
                this.link = ''
            }
        },
        deleteLink(index) {
            this.links.splice(index, 1)
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
