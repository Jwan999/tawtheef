// resources/js/router/index.js

import {createRouter, createWebHistory} from 'vue-router';

import Statistics from '../../views/dashboard/StatisticsView.vue';
import Applicants from '../../views/dashboard/ApplicantsView.vue';
import ApplicantProfile from '../../views/dashboard/ApplicantProfileView.vue';
import CompaniesView from '../../views/dashboard/CompaniesView.vue';
import CompanyProfile from '../../views/dashboard/CompanyProfileView.vue';
import Home from '../../views/public/MainView.vue';
import LoginAndSignupView from "../../views/public/LoginAndSignupView.vue";
import ApplicantProfileView from "../../views/dashboard/ApplicantProfileView.vue";
import {getAuthUser} from "../utils/storeHelpers.js";
import {logoutUser} from "../utils/auth.js";
// import ComingSoonComponent from "../components/publicMainComponents /ComingSoonComponent.vue";

const routes = [
    // dashboard routes
    {path: '/dashboard', component: Statistics},
    {path: '/dashboard/applicants', component: Applicants},
    // todo add :id
    {path: '/dashboard/applicants/:id', component: ApplicantProfile},
    {path: '/dashboard/companies', component: CompaniesView},
    // todo add :id
    {path: '/dashboard/companies/:id', component: CompanyProfile},
    {path: '/dashboard/login', component: LoginAndSignupView},

    // public routes
    // {path: '/', component: ComingSoonComponent},
    {path: '/', component: Home},

    {path: '/profile/:id', component: ApplicantProfileView, meta: {requiresAuth: true}},
    {path: '/profile/:id/edit', component: ApplicantProfileView, meta: {requiresAuth: true}},

    {path: '/login', component: LoginAndSignupView},
    {path: '/signup', component: LoginAndSignupView},

    {path: '/api/selectables/:key', name: 'selectables',}


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        try {
            const user = await getAuthUser();
            if (user && user.id) {
                next();
            } else {
                next({path: '/login', query: {redirect: to.fullPath}});
            }
        } catch (error) {
            console.error('Error during authentication check:', error);
            next('/login');
        }
    } else {
        next();
    }
});

export default router;
