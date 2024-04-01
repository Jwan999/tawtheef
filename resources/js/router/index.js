// resources/js/router/index.js

import {createRouter, createWebHistory} from 'vue-router';

import Main from '../../views/pages/MainView.vue';
import Applicants from '../../views/pages/ApplicantsView.vue';
import ApplicantProfile from '../../views/pages/ApplicantProfileView.vue';
import CompaniesView from '../../views/pages/CompaniesView.vue';
import CompanyProfile from '../../views/pages/CompanyProfileView.vue';

const routes = [
    {path: '/', component: Main},
    {path: '/applicants', component: Applicants},
    // todo add :id
    {path: '/applicants/1', component: ApplicantProfile},
    {path: '/companies', component: CompaniesView},
    // todo add :id
    {path: '/companies/1', component: CompanyProfile},

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
