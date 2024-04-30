<template>
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                            aria-controls="logo-sidebar" type="button"
                            class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                  d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="/" class="flex ms-2 md:me-24">
                        <img src="https://makersiq.org/images/makerss.svg" class="h-10 me-3" alt="FlowBite Logo"/>
                        <span class="self-center text-md font-semibold sm:text-2xl whitespace-nowrap dark:text-white">TAWTHEEF</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button"
                                    class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                    aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full"
                                     src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                                     alt="user photo">
                            </button>
                        </div>
                        <div
                            class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                            id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    Neil Sims
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    neil.sims@flowbite.com
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                       role="menuitem">Dashboard</a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                       role="menuitem">Settings</a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                       role="menuitem">Earnings</a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                       role="menuitem">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar"
           class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
           aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <router-link to="/dashboard"
                                 class="flex items-center p-2 text-dark rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg
                            class="w-5 h-5 text-dark transition duration-75 group-hover:text-gray-900"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ms-3">Main</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/dashboard/applicants"
                                 class="flex items-center p-2 text-dark hover:fill-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="flex-shrink-0 w-5 h-5 fill-dark text-dark transition duration-75 group-hover:fill-gray-900" viewBox="0 0 511.999 511.999">
                            <g>
                                <g>
                                    <path d="M438.09,273.32h-39.596c4.036,11.05,6.241,22.975,6.241,35.404v149.65c0,5.182-0.902,10.156-2.543,14.782h65.461
			c24.453,0,44.346-19.894,44.346-44.346v-81.581C512,306.476,478.844,273.32,438.09,273.32z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M107.265,308.725c0-12.43,2.205-24.354,6.241-35.404H73.91c-40.754,0-73.91,33.156-73.91,73.91v81.581
			c0,24.452,19.893,44.346,44.346,44.346h65.462c-1.641-4.628-2.543-9.601-2.543-14.783V308.725z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M301.261,234.815h-90.522c-40.754,0-73.91,33.156-73.91,73.91v149.65c0,8.163,6.618,14.782,14.782,14.782h208.778
			c8.164,0,14.782-6.618,14.782-14.782v-149.65C375.171,267.971,342.015,234.815,301.261,234.815z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M256,38.84c-49.012,0-88.886,39.874-88.886,88.887c0,33.245,18.349,62.28,45.447,77.524
			c12.853,7.23,27.671,11.362,43.439,11.362c15.768,0,30.586-4.132,43.439-11.362c27.099-15.244,45.447-44.28,45.447-77.524
			C344.886,78.715,305.012,38.84,256,38.84z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M99.918,121.689c-36.655,0-66.475,29.82-66.475,66.475c0,36.655,29.82,66.475,66.475,66.475
			c9.298,0,18.152-1.926,26.195-5.388c13.906-5.987,25.372-16.585,32.467-29.86c4.98-9.317,7.813-19.946,7.813-31.227
			C166.393,151.51,136.573,121.689,99.918,121.689z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M412.082,121.689c-36.655,0-66.475,29.82-66.475,66.475c0,11.282,2.833,21.911,7.813,31.227
			c7.095,13.276,18.561,23.874,32.467,29.86c8.043,3.462,16.897,5.388,26.195,5.388c36.655,0,66.475-29.82,66.475-66.475
			C478.557,151.509,448.737,121.689,412.082,121.689z"/>
                                </g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                        </svg>

                        <span class="flex-1 ms-3 whitespace-nowrap">Applicants</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/dashboard/companies"
                                 class="flex items-center p-2 text-dark rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">

                        <svg class="flex-shrink-0 w-6 h-6 -ml-1 fill-dark text-dark transition duration-75 group-hover:fill-gray-900" viewBox="0 0 512 512">
                            <g>
                                <path d="m286 271.989h75.667v-256.989c0-8.284-6.716-15-15-15h-181.334c-8.284 0-15 6.716-15 15v196.989h90.667c24.813 0 45 20.186 45 45zm-50.5-241.989h41c8.284 0 15 6.716 15 15s-6.716 15-15 15h-41c-8.284 0-15-6.716-15-15s6.716-15 15-15zm0 60h41c8.284 0 15 6.716 15 15s-6.716 15-15 15h-41c-8.284 0-15-6.716-15-15s6.716-15 15-15zm41 90h-41c-8.284 0-15-6.716-15-15s6.716-15 15-15h41c8.284 0 15 6.716 15 15s-6.716 15-15 15z"/>
                                <path d="m241 241.989h-166c-8.284 0-15 6.716-15 15v240.011c0 8.284 6.716 15 15 15h37.5v-60.011c0-8.284 6.716-15 15-15h61c8.284 0 15 6.716 15 15v60.011h52.5v-255.011c0-8.284-6.716-15-15-15zm-62.5 150h-41c-8.284 0-15-6.716-15-15s6.716-15 15-15h41c8.284 0 15 6.716 15 15s-6.716 15-15 15zm0-60h-41c-8.284 0-15-6.716-15-15s6.716-15 15-15h41c8.284 0 15 6.716 15 15s-6.716 15-15 15z"/>
                                <path d="m142.5 466.989h31v45.011h-31z"/>
                                <path d="m437 301.989h-151v210.011h35.5v-60.011c0-8.284 6.716-15 15-15h61c8.284 0 15 6.716 15 15v60.011h24.5c8.284 0 15-6.716 15-15v-180.011c0-8.284-6.716-15-15-15zm-49.5 90h-41c-8.284 0-15-6.716-15-15s6.716-15 15-15h41c8.284 0 15 6.716 15 15s-6.716 15-15 15z"/>
                                <path d="m351.5 466.989h31v45.011h-31z"/>
                            </g>
                        </svg>


                        <span class="flex-1 ms-3 whitespace-nowrap">Companies</span>
                    </router-link>
                </li>

            </ul>
        </div>
    </aside>

</template>
<script>
export default {
    name: "ApplicantsView",
    mounted(){
        console.log("im mounted")
    }
}
</script>

<style scoped>

</style>
