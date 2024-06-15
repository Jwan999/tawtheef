// resources/js/router/index.js

import {createRouter, createWebHistory} from 'vue-router';
import ApplicantProfileView from '../../views/dashboard/ApplicantProfileView.vue';
import Home from '../../views/public/MainView.vue';
import LoginView from "../components/publicAuth/LoginView.vue";
import SignupView from "../components/publicAuth/SignupView.vue";
import store from "../store/index";
// import ComingSoonComponent from "../components/publicMainComponents /ComingSoonComponent.vue";

const routes = [
    // public routes
    // {path: '/', component: ComingSoonComponent},
    {path: '/', component: Home},

    {path: '/profile/:id', component: ApplicantProfileView, name: 'profile-view', meta: {requiresAuth: true}},
    {path: '/profile/:id/edit', component: ApplicantProfileView, name: 'profile-edit', meta: {requiresAuth: true}},
    {path: '/resume/:id', component: ApplicantProfileView, name: 'resume-view'},

    {path: '/login', component: LoginView},
    {path: '/signup', component: SignupView},

    {path: '/api/selectables/:key', name: 'selectables',}


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {

        const user = store.getters.user;
        if (user && user.id) {
            // console.log(`userId: ${user?.id}`)
            next();
        } else {
            next({path: '/login', query: {redirect: to.fullPath}});
        }

    } else {
        next();
    }
});

export default router;
