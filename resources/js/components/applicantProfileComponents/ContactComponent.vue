<template>
    <!--preview-->
    <div v-if="!editMode" class="">

        <div class=" text-sm md:text-xs">

            <div class="flex justify-between items-center">
                <div class="w-6/12">

                    <h1 class="flex-none text-xs font-semibold mb-3 text-zinc-500">Contact info</h1>

                    <h1 class="text-md font-semibold">{{ email }}</h1>
                    <h1 v-if="isChecked" class="text-md">+964 781 615 1297</h1>
                </div>
                <div class="w-6/12 -mt-4">

                    <h1 class="flex-none text-xs font-semibold mb-3 text-zinc-500">Date of birth</h1>
                    <h1 class="text-md font-semibold">{{ birthdate }}</h1>

                </div>
            </div>


            <div class="flex w-full justify-between mt-4">
                <div class="w-6/12">
                    <h1 class="flex-none text-xs font-semibold mb-3 text-zinc-500">Residence</h1>

                    <h1 class="text-md font-semibold">{{ city + ', ' + zone }}</h1>
                </div>
                <div class="w-6/12">
                    <h1 class="flex-none text-xs font-semibold mb-3 text-zinc-500">Links</h1>

                    <a :href="link.link" v-for="(link, index) in links" :key="index"
                       class="text-orange text-md font-semibold underline">{{ link.hyperLink }}</a>
                </div>


            </div>
        </div>

    </div>
    <!--form-->
    <div v-else>
        <div class="rounded-md pt-2 pb-4 bg-white">

            <div class="space-y-6 px-4 text-sm md:text-xs">
                <div class="flex space-x-3 items-center">
                    <div class="relative -mt-2 w-full">
                        <span class="text-orange absolute top-0 right-0 ml-24 -mt-3">*</span>
                        <input @input="emitInputData" type="text" v-model="email"
                               class=" block w-full p-2.5 bg-slate-50 w-full rounded-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none focus:border-orange focus:ring-0"
                               placeholder="name@flowbite.com">
                        <!--                        <h1 class="text-red-500 text-xs font-semibold">This field is required.</h1>-->
                    </div>
                    <div class="w-full mt-6">
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
                            <select @change="emitInputData" v-model="city" class="focus:border-orange focus:ring-0 bg-slate-50 w-full rounded-l-md md:text-xs text-sm border-0 border-b-[1px] border-gray-300 hover:border-orange focus:outline-none">
                                <option selected>Choose a city</option>
                                <option>Baghdad</option>
                                <option>Najaf</option>
                                <option>Karbala</option>
                                <option>Basra</option>
                                <option>Mosul</option>
                                <option>Erbil</option>
                                <option>Dohuk</option>
                                <option>Kirkuk</option>
                                <option>Amara</option>
                                <option>Diwaniyah</option>
                                <option>Hilla</option>
                                <option>Samawah</option>
                                <option>Nasiriyah</option>
                                <option>Ramadi</option>
                                <option>Fallujah</option>
                                <option>Heet</option>
                                <option>Al-Qa'im</option>
                            </select>
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
                            class="bg-white focus:outline-none appearance-none hover:text-orange">
                        <svg class="fill-zinc-700 w-4 h-4 hover:fill-orange" viewBox="0 0 1024 1024"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m808.1 265.9c-.1 12.8-5.7 26.3-14.7 35.4l-.8.8c-6.4 6.5-12.8 12.8-19.2 19.2l-190.7 190.7 210.7 210.7c9.5 9.5 14.1 22.1 14.7 35.4.5 13.4-6 25.8-14.7 35.4-8.4 9.3-23.1 14.7-35.4 14.7-12.8-.1-26.3-5.7-35.4-14.7l-.8-.8c-6.5-6.4-12.8-12.8-19.2-19.2l-190.6-190.8-210.7 210.7c-9.6 9.6-22.1 14.1-35.4 14.7-13.4.5-25.8-6-35.4-14.7-9.3-8.4-14.7-23.1-14.7-35.4.1-12.8 5.7-26.3 14.7-35.4l.8-.8c6.4-6.5 12.8-12.8 19.2-19.2l190.8-190.6-210.7-210.7c-9.6-9.6-14.1-22.1-14.7-35.4-.5-13.4 6-25.8 14.7-35.4 8.4-9.3 23.1-14.7 35.4-14.7 12.8.1 26.3 5.7 35.4 14.7l.8.8c6.5 6.4 12.8 12.8 19.2 19.2l190.6 190.8 210.7-210.7c9.5-9.5 22.1-14.1 35.4-14.7 13.4-.5 25.8 6 35.4 14.7 9.2 8.4 14.6 23 14.6 35.3z"></path>
                        </svg>
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
            city: 'Choose a city',
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
