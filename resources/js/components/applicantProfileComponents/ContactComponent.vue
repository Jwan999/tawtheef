<template>
    <!--preview-->
    <div v-if="!editMode" class=" rounded-md py-4 bg-white">

        <div class="space-y-1 px-4 text-sm md:text-xs">
            <h1 class="">jwanaalfatla1999@gmail.com</h1>
            <h1 class="">jwanaalfatla1999@gmail.com</h1>
            <h1 class="">jwanaalfatla1999@gmail.com</h1>
            <h1 v-if="isChecked" class="">+964 781 615 1297</h1>
            <a :href="link.link" v-for="(link, index) in links" :key="index" class="text-orange font-semibold underline">{{ link.hyperLink }}</a>
        </div>

    </div>
    <!--form-->
    <div v-else>
        <div class="rounded-md pt-2 pb-4 bg-white">

            <div class="space-y-6 px-4 text-sm md:text-xs">
                <div class="flex space-x-3 items-center">
                    <div class="relative mt-2 w-full">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <input @input="emitInputData" type="text" v-model="email"
                               class=" block w-full p-2.5 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                               placeholder="name@flowbite.com">
<!--                        <h1 class="text-red-500 text-xs font-semibold">This field is required.</h1>-->
                    </div>
                    <div class="w-full mt-5">
                        <input @input="emitInputData" type="text" v-model="phone"
                               class="block w-full p-2.5 focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"
                               pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890" required/>
                        <div class="me-4 mt-2">
                            <input @input="emitInputData" checked id="orange-checkbox" type="checkbox" value=""
                                   v-model="isChecked"
                                   class="w-4 h-4 mr-2 mb-1 text-orange bg-gray-100 border-gray-300 rounded focus:ring-orange dark:focus:ring-orange dark:ring-offset-gray-800 focus:ring-1 dark:bg-gray-700 dark:border-gray-600">
                            <span class="mb-2 text-xs font-medium text-gray-600">Make phone number public.</span>
                        </div>
                    </div>
                </div>



                <div class="flex space-x-3 items-center mt-3">
                    <div class="relative w-full">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <input @input="emitInputData" type="text" v-model="birthdate"
                               class=" block w-full p-2.5 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                               placeholder="Date of birth">
<!--                        <h1 class="text-red-500 text-xs font-semibold">This field is required.</h1>-->
                    </div>
                    <div class="w-full relative">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <div class="flex">
                            <input @input="emitInputData" type="text" v-model="city" placeholder="City"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-l-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>
                            <input @input="emitInputData" type="text" v-model="zone" placeholder="Zone"
                                   class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-r-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>

                        </div>
<!--                        <h1 class="text-red-500 text-xs font-semibold">This field is required.</h1>-->
                    </div>
                </div>
                <div class="relative w-full">
                    <div class="flex">
                        <input @input="emitInputData" type="text" v-model="link" placeholder="Link"
                               class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-l-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>
                        <input @input="emitInputData" type="text" v-model="hyperLink" placeholder="Hyper link"
                               class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-r-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none"/>

                    </div>

                    <button type="submit" @click="addLink" @keyup:enter="addLink"
                            class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-orange rounded-e-md  hover:bg-dark focus:outline-none">
                        <svg class="w-2 h-2 fill-white" viewBox="0 0 512 512">
                            <g>
                                <path
                                    d="m467 211h-166v-166c0-24.853-20.147-45-45-45s-45 20.147-45 45v166h-166c-24.853 0-45 20.147-45 45s20.147 45 45 45h166v166c0 24.853 20.147 45 45 45s45-20.147 45-45v-166h166c24.853 0 45-20.147 45-45s-20.147-45-45-45z"/>
                            </g>
                        </svg>
                    </button>

                </div>
                <h1 v-for="(link, index) in links" :key="index"
                    class="flex justify-between text-orange font-semibold underline mt-6">
                    <a :href="link.link">{{ link.hyperLink }}</a>
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
            location: [],
            city: '',
            zone: '',
            email: '',
            phone: '',
            birthdate: '',
            residence: '',
            link: '',
            hyperLink: '',
            links: [],
            isChecked: false
        }
    },
    methods: {
        emitInputData() {
            this.$emit('contactUpdated', {
                email: this.email,
                phone: this.phone,
                links: this.links,
                location: this.location.push({city: this.city, zone: this.zone}),
                birthdate: this.birthdate,
                residence: this.residence
            });
        },
        addLink() {
            if (this.link !== '') {
                this.links.push({link: this.link, hyperLink: this.hyperLink})
                this.link = ''
                this.hyperLink = ''
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
