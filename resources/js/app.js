import './bootstrap';
import 'flowbite';
import { createApp } from 'vue';
import router from './router';
import store from './store'; // Import Vuex store instance
import App from './App.vue';

const app = createApp(App);
app.use(router);
app.use(store); // Integrate Vuex store with Vue application
app.mount('#app');
