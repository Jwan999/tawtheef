<template>
    <nav class="px-6 py-2 flex justify-between items-center">
        <div>
            <router-link to="/" class="tracking-wider text-dark self-center text-xl md:text-3xl whitespace-nowrap font-semibold">
                TAWTHEEF
            </router-link>
        </div>

        <!-- Full menu for md and larger screens -->
        <div class="hidden md:flex items-center justify-between space-x-6">
            <template v-if="!isAuthenticated">
<!--                <router-link v-slot="{ navigate }" custom to="/login">-->
<!--                    <button @click="navigate" class="text-orange font-semibold text-md hover:bg-zinc-800 hover:text-white rounded-full px-12 py-2.5 transition-all duration-300 ease-in-out transform hover:scale-105">Login</button>-->
<!--                </router-link>-->
                <router-link v-slot="{ navigate }" custom to="/login">
                    <button @click="navigate" class="bg-orange text-white font-semibold text-md px-12 py-2 shadow-custom-3d rounded-full hover:bg-zinc-800 hover:shadow-none transition-all duration-300 ease-in-out transform hover:scale-105">Get Started</button>
                </router-link>
            </template>
            <template v-else>
                <button @click="logout" class="text-orange font-semibold text-md hover:bg-zinc-800 hover:text-white rounded-full px-12 py-2 transition-all duration-300 ease-in-out transform hover:scale-105">Logout</button>
                <router-link v-slot="{ navigate }" custom :to="`/profile/${user?.applicant?.id}`">
                    <button @click="navigate" class="bg-orange text-white font-semibold text-md px-12 py-2 shadow-custom-3d rounded-full hover:bg-zinc-800 hover:shadow-none transition-all duration-300 ease-in-out transform hover:scale-105">Resume</button>
                </router-link>
            </template>
        </div>

        <!-- Dropdown menu for smaller screens -->
        <div class="md:hidden relative dropdown">
            <button @click="toggleDropdown" class="p-2 text-zinc-800 hover:text-orange transition-colors duration-300 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 transform scale-95"
                enter-to-class="opacity-100 transform scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 transform scale-100"
                leave-to-class="opacity-0 transform scale-95"
            >
                <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-52 bg-zinc-50 rounded-md shadow-lg py-1 z-10">
                    <div class="px-4 py-2 text-xs text-zinc-400 uppercase tracking-wider">Menu</div>
                    <template v-if="!isAuthenticated">
                        <router-link v-slot="{ navigate }" custom to="/login">
                            <a @click="navigate" class="block px-4 py-2 text-sm text-zinc-800 hover:bg-orange hover:text-white">
                                Login
                            </a>
                        </router-link>
                        <router-link v-slot="{ navigate }" custom to="/login">
                            <a @click="navigate" class="block px-4 py-2 text-sm text-zinc-800 hover:bg-orange hover:text-white">
                                Create Resume
                            </a>
                        </router-link>
                    </template>
                    <template v-else>
                        <router-link v-slot="{ navigate }" custom :to="`/profile/${user?.applicant?.id}`">
                            <a @click="navigate" class="block px-4 py-2 text-sm text-zinc-800 hover:bg-orange hover:text-white">
                                Resume
                            </a>
                        </router-link>
                        <a @click="logout" class="block px-4 py-2 text-sm text-zinc-800 hover:bg-orange hover:text-white cursor-pointer">
                            Logout
                        </a>
                    </template>
                </div>
            </transition>
        </div>
    </nav>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from "vue";
import { mapActions, useStore } from "vuex";
import router from "../../../js/router/index.js";

const store = useStore();
const isDropdownOpen = ref(false);

const user = computed(() => store.getters.user);
const isAuthenticated = computed(() => store.getters.isAuthenticated);

const { checkAuthStatus } = mapActions(['checkAuthStatus']);

const logout = async () => {
    await axios.post('/logout');
    store.commit('setUser', null);
    await router.push('/');
    isDropdownOpen.value = false;
};

const toggleDropdown = (event) => {
    event.stopPropagation();
    isDropdownOpen.value = !isDropdownOpen.value;
};

// Close dropdown when clicking outside
const closeDropdown = (event) => {
    if (isDropdownOpen.value && !event.target.closest('.dropdown')) {
        isDropdownOpen.value = false;
    }
};

// Add event listener for closing dropdown
onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>

<style scoped>
.nav-link {
    @apply font-semibold border-b-[1px] border-zinc-600 px-4 md:px-8 py-2 cursor-pointer text-zinc-800 text-sm md:text-sm border transition-colors ease-in delay-100 hover:text-orange hover:border-orange;
}
</style>
