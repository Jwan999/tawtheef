// import Vue from 'vue'
import './bootstrap';
import 'flowbite';
// import {createApp} from 'vue'
// import App from './App.vue'
// import router from './router';

import {createApp} from 'vue';  // Use the correct import statement

// import App from './App.vue';
import router from './router';
import App from "./App.vue";

createApp(App).use(router).mount('#app');
// const app = createApp(App)
// app.mount('#app')
// createApp(App).use(router).mount('#app');
// new Vue({
//     router,
//     // axios,
//     // VueAxios,
//     render: h => h(App)
// }).$mount('#app')
