<template>
    <div class="px-6  flex justify-between items-center">
        <div>
            <router-link to="/"
                         class="self-center text-xl md:text-3xl font-semibold whitespace-nowrap zinc-800:text-white">
                TAWTHEEF
            </router-link>
        </div>
        <div class="flex items-center justify-between space-x-6">
            <template v-if="!isAuthenticated">
                <router-link
                    class="font-semibold border-b-[1px] border-zinc-600 border-t-0 px-4 md:px-8 pt-1 py-2 cursor-pointer text-zinc-800 text-sm md:text-base border transition-colors ease-in delay-100 hover:text-orange hover:border-orange "
                    to="/login">Login
                </router-link>
                <router-link
                    class="font-semibold border-b-[1px] border-zinc-600 border-t-0 px-4 md:px-8 pt-1 py-2 cursor-pointer text-zinc-800 text-sm md:text-base border transition-colors ease-in delay-100 hover:text-orange hover:border-orange "
                    to="/login">
                    Create Resume
                </router-link>
            </template>
            <template v-else>
                <router-link
                    class="font-semibold border-b-[1px] border-zinc-600 border-t-0 px-4 md:px-8 pt-1 py-2 cursor-pointer text-zinc-800 text-sm md:text-base border transition-colors ease-in delay-100 hover:text-orange hover:border-orange "
                    :to="`/profile/${user?.applicant?.id}`">
                    Resume
                </router-link>
                <button @click="logout"
                        class="font-semibold border-b-[1px] border-zinc-600 border-t-0 px-4 md:px-8 pt-1 py-2 cursor-pointer text-zinc-800 text-sm md:text-base border transition-colors ease-in delay-100 hover:text-orange hover:border-orange ">
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
