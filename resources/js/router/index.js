import { createRouter, createWebHistory } from 'vue-router';
import ApplicantProfileView from '../../views/dashboard/DashboardApplicants.vue';
import Home from '../../views/public/MainView.vue';
import LoginView from "../components/publicAuth/LoginView.vue";
import SignupView from "../components/publicAuth/SignupView.vue";
import ForgotPassword from "../components/publicAuth/ForgotPassword.vue";
import ResetPassword from "../components/publicAuth/ResetPassword.vue";
import store from "../store/index";
import ResumePreviewMode from "../../views/public/Resume/ResumePreviewMode.vue";
import ResumeProfileMode from "../../views/public/Resume/ResumeProfileMode.vue";
import DescriptionAndBrandView from "../../views/public/DescriptionAndBrandView.vue";
import DashboardView from "../../views/dashboard/DashboardView.vue";

const routes = [
    {
        path: '/',
        component: Home,
        name: 'home',
        meta: {
            title: 'Home',
            requiresAuth: false
        }
    },
    {
        path: '/about',
        component: DescriptionAndBrandView,
        name: 'DescriptionAndBrandView',
        meta: {
            title: 'About Us',
            requiresAuth: false
        }
    },
    {
        path: '/dashboard',
        component: DashboardView,
        name: 'dashboard',
        meta: {
            title: 'Dashboard',
            requiresAuth: true,
            requiresAdmin: true
        }
    },
    {
        path: '/preview/:id',
        component: ResumePreviewMode,
        name: 'preview-view',
        meta: {
            title: 'Resume Preview',
            requiresAuth: false
        }
    },
    {
        path: '/profile/:id',
        component: ResumeProfileMode,
        name: 'profile-view',
        meta: {
            title: 'Profile',
            requiresAuth: true
        }
    },
    {
        path: '/login',
        component: LoginView,
        name: 'login',
        meta: {
            title: 'Login',
            requiresAuth: false,
            guestOnly: true
        }
    },
    {
        path: '/signup',
        component: SignupView,
        name: 'signup',
        meta: {
            title: 'Sign Up',
            requiresAuth: false,
            guestOnly: true
        }
    },
    {
        path: '/forgot-password',
        component: ForgotPassword,
        name: 'forgot-password',
        meta: {
            title: 'Forgot Password',
            requiresAuth: false,
            guestOnly: true
        }
    },
    {
        path: '/reset-password/:token',
        component: ResetPassword,
        name: 'reset-password',
        meta: {
            title: 'Reset Password',
            requiresAuth: false,
            guestOnly: true
        }
    },
    {
        path: '/:catchAll(.*)',
        redirect: '/'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    // Show loading state
    store.commit('setLoading', true);

    // Try to fetch user if we don't have one and not accessing reset password
    if (!store.getters.user && !to.path.startsWith('/reset-password')) {
        try {
            await store.dispatch('checkAuth');
        } catch (error) {
            console.error('Error checking auth status:', error);
        }
    }

    const user = store.getters.user;

    // Allow access to public routes even if not authenticated
    const publicRoutes = ['login', 'signup', 'forgot-password', 'reset-password', 'home', 'DescriptionAndBrandView'];
    if (publicRoutes.includes(to.name)) {
        // Redirect to home if trying to access auth pages while logged in
        if (user && ['login', 'signup', 'forgot-password', 'reset-password'].includes(to.name)) {
            store.commit('setError', { message: 'You are already logged in', type: 'info' });
            next({ name: 'home' });
            return;
        }
        next();
        return;
    }

    // Check if route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!user || !user.id) {
            store.commit('setError', { message: 'Please login to access this page', type: 'error' });
            next({
                name: 'login',
                query: { redirect: to.fullPath }
            });
            return;
        }

        // Check admin requirement
        if (to.matched.some(record => record.meta.requiresAdmin)) {
            if (user.role !== 'admin') {
                store.commit('setError', { message: 'Admin access required', type: 'error' });
                next({
                    name: 'home'
                });
                return;
            }
        }

        next();
    } else {
        next();
    }
});

router.afterEach((to) => {
    // Update page title
    document.title = to.meta.title
        ? `${to.meta.title} - Tawtheef`
        : 'Tawtheef';

    // Hide loading state
    store.commit('setLoading', false);
});

export default router;
