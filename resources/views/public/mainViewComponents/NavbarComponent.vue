<template>
    <div class="px-6 py-2 flex justify-between items-center">
        <div>
            <span
                class="self-center text-md font-semibold sm:text-2xl whitespace-nowrap dark:text-white">TAWTHEEF</span>
        </div>
        <div class="flex items-center justify-between space-x-3">
            <router-link
                class="cursor-pointer text-dark text-sm border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                to="/login">Login
            </router-link>
            <router-link
                class="cursor-pointer text-dark text-sm border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                to="/signup">Signup
            </router-link>
            <!--todo add user id-->
            <router-link
                class="cursor-pointer text-dark text-sm border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                to="/profile/1">
                Profile
            </router-link>

        </div>

    </div>
</template>

<script>
export default {
    name: "NavbarComponent"
}
</script>

<style scoped>

</style>
