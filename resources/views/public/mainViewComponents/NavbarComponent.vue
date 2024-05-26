<template>
    <div class="px-6 py-2 flex justify-between items-center">
        <div>
            <router-link to="/"
                         class="self-center text-md font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                TAWTHEEF
            </router-link>
        </div>
        <div class="flex items-center justify-between space-x-3">

            <router-link v-if="!userId"
                         class="font-semibold cursor-pointer text-dark text-sm border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                         to="/login">Login
            </router-link>

            <router-link
                class="font-semibold cursor-pointer text-dark text-sm border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                :to="userId ? `/profile/${userId}/edit` : '/login'">
                Resume
            </router-link>
            <button v-if="userId" @click="logout"
                    class="font-semibold cursor-pointer text-dark text-sm border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2">
                Logout
            </button>

        </div>

    </div>
</template>

<script setup>
import {getAuthUser} from "../../../js/utils/storeHelpers";
import {onMounted, ref} from "vue";

const userId = ref(null)
const logout = () => {
    axios.post('/logout').then(res => {
        window.location.href = '/';
    })
}
onMounted(() => {
    getAuthUser().then(response => {
        userId.value = response.id;
    }).catch(error => {
        console.error('Error fetching user data:', error);
    })
});



</script>

<style scoped>

</style>
