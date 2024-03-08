// resources/js/router/index.js

import {createRouter, createWebHistory} from 'vue-router';

import Main from '../../views/pages/MainView.vue';
import Applicants from '../../views/pages/ApplicantsView.vue';
import ApplicantProfile from '../../views/pages/ApplicantProfileView.vue';
import ApplicantForm from '../../views/pages/ApplicantFormView.vue';
import CompaniesView from '../../views/pages/CompaniesView.vue';
import CompanyProfile from '../../views/pages/CompanyProfileView.vue';
import CompaniesForm from '../../views/pages/CompanyFormView.vue';

const routes = [
    {path: '/', component: Main},
    {path: '/applicants', component: Applicants},
    {path: '/applicant/:id', component: ApplicantProfile},
    {path: '/applicant/create', component: ApplicantForm},
    {path: '/companies', component: CompaniesView},
    {path: '/companies/:id', component: CompanyProfile},
    {path: '/company/create', component: CompaniesForm},

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
