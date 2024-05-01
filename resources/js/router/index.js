// resources/js/router/index.js

import {createRouter, createWebHistory} from 'vue-router';

import Statistics from '../../views/dashboard/StatisticsView.vue';
import Applicants from '../../views/dashboard/ApplicantsView.vue';
import ApplicantProfile from '../../views/dashboard/ApplicantProfileView.vue';
import CompaniesView from '../../views/dashboard/CompaniesView.vue';
import CompanyProfile from '../../views/dashboard/CompanyProfileView.vue';
import Home from '../../views/public/MainView.vue';
import LoginAndSignupView from "../../views/public/LoginAndSignupView.vue";
import LoginView from "../../views/dashboard/LoginView.vue";
import ApplicantProfileView from "../../views/dashboard/ApplicantProfileView.vue";
import ComingSoonComponent from "../components/publicMainComponents /ComingSoonComponent.vue";

const routes = [
    // dashboard routes
    {path: '/dashboard', component: Statistics},
    {path: '/dashboard/applicants', component: Applicants},
    // todo add :id
    {path: '/dashboard/applicants/1', component: ApplicantProfile},
    {path: '/dashboard/companies', component: CompaniesView},
    // todo add :id
    {path: '/dashboard/companies/1', component: CompanyProfile},
    {path: '/dashboard/login', component: LoginView},

    // public routes
    {path: '/', component: ComingSoonComponent},
    // {path: '/', component: Home},
    {path: '/profile/1', component: ApplicantProfileView},
    {path: '/profile/1/edit', component: ApplicantProfileView},

    {path: '/login', component: LoginAndSignupView},
    {path: '/signup', component: LoginAndSignupView},


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
