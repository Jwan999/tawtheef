import './bootstrap';
import 'flowbite';
import { createApp } from 'vue';
import router from './router';
import store from './store/index.js'; // Import Vuex store instance
import App from './App.vue';

const app = createApp(App);
app.config.compilerOptions.isCustomElement = tag => tag.includes('dotlottie-player');
app.use(router);
app.use(store); // Integrate Vuex store with Vue application
app.mount('#app');
// main.js
