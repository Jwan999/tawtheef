// resources/js/router/index.js

import {createRouter, createWebHistory} from 'vue-router';
import ApplicantProfileView from '../../views/dashboard/ApplicantProfileView.vue';
import Home from '../../views/public/MainView.vue';
import LoginView from "../components/publicAuth/LoginView.vue";
import SignupView from "../components/publicAuth/SignupView.vue";
import store from "../store/index";
import ResumePreviewMode from "../../views/public/Resume/ResumePreviewMode.vue"
import ResumeProfileMode from "../../views/public/Resume/ResumeProfileMode.vue"

const routes = [
    {path: '/', component: Home},

    // {path: '/profile/:id', component: ApplicantProfileView, name: 'profile-view', meta: {requiresAuth: true}},
    // {path: '/profile/:id/edit', component: ApplicantProfileView, name: 'profile-edit', meta: {requiresAuth: true}},
    // {path: '/resume/:id', component: ApplicantProfileView, name: 'resume-view'},

    // {path: '/profile/:id/edit', component: ApplicantProfileView, name: 'profile-edit', meta: {requiresAuth: true}},

    {path: '/preview/:id', component: ResumePreviewMode, name: 'preview-view'}, // preview view is resume page to preview
    {path: '/profile/:id', component: ResumeProfileMode, name: 'profile-view', meta: {requiresAuth: true}}, // profile view is edit mode

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
            next();
        } else {
            next({path: '/login', query: {redirect: to.fullPath}});
        }

    } else {
        next();
    }
});

export default router;
