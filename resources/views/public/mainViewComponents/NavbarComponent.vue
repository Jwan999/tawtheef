<template>
    <div class="px-6 py-2 flex justify-between items-center">
        <div>
            <router-link to="/"
                         class="self-center text-base font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                TAWTHEEF
            </router-link>
        </div>
        <div class="flex items-center justify-between space-x-3">
            <template v-if="!isAuthenticated">
                <router-link
                    class="font-semibold cursor-pointer text-dark text-base border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                    to="/login">Login
                </router-link>
                <router-link
                    class="font-semibold cursor-pointer text-dark text-base border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                    to="/login">
                    Create Resume
                </router-link>
            </template>
            <template v-else>
                <router-link
                    class="font-semibold cursor-pointer text-dark text-base border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2"
                    :to="`/profile/${user?.applicant?.id}`">
                    Resume
                </router-link>
                <button @click="logout"
                        class="font-semibold cursor-pointer text-dark text-base border border-transparent transition-colors ease-in delay-100 hover:text-orange hover:border-orange rounded-full lg:px-5 px-4 py-2">
                    Logout
                </button>
            </template>


        </div>

    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import {mapActions, useStore} from "vuex";
import router from "../../../js/router/index.js";

const store = useStore();
const userId = ref(null);
const user = computed(() => {
    return store.getters.user;
})
const isAuthenticated = computed(() => {
    return store.getters.isAuthenticated;
})

const {checkAuthStatus} = mapActions(['checkAuthStatus']);

const logout = async () => {
    await axios.post('/logout');
    store.commit('setUser', null);
    await router.push('/');
};


</script>

<style scoped>

</style>
