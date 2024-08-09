import {createStore} from 'vuex';
import axios from "axios";
import {getAuthUser} from "../utils/storeHelpers.js";

function getUserFromLocalStorage() {
    try {
        const userString = localStorage.getItem('user');
        if (userString) {
            return JSON.parse(userString);
        }
    } catch (error) {
        console.error('Error parsing user data from localStorage:', error);
    }
    return {}; // Return an empty object as a fallback
}

export default createStore({
    state: {
        editMode: false,
        searchMode: true,
        invalidFields: [],
        user: getUserFromLocalStorage(),
        filteredApplicants: {},
        searchedApplicants: {},
        currentPage: 1,
        filters: {
            experience: [2, 6],
            age: [19, 26],
            gender: null,
            workAvailability: null,
            freshGraduate: null,
            mainSpecializations: [],
            subSpecialities: [],
            degree: '',
            city: '',
            zone: '',
        },
        searchQuery: '',
    },
    actions: {
        setEditMode({commit}) {
            commit('setEditMode');
        },
        checkEditMode({commit}, url) {
            if (url.includes('edit')) {
                commit('setEditMode');
            }
        },
        setSearchMode({commit}) {
            commit('setSearchMode');
        },
        async getUser({commit}) {
            const user = await getAuthUser();
            commit('setUser', user);
            return user;
        },
        async checkAuthStatus({commit}) {
            try {
                const response = await axios.get('/api/auth');
                commit('setAuthStatus', !!response.data);
            } catch (error) {
                console.error('Error fetching data:', error);
                commit('setAuthStatus', false);
            }
        },
        async fetchFilteredApplicants({ commit, state }, { page = 1, perPage = 12 } = {}) {
            try {
                const response = await axios.get('/api/applicants/search', {
                    params: {
                        ...state.filters,
                        page,
                        per_page: perPage
                    }
                });
                commit('setFilteredApplicants', response.data);
                commit('setCurrentPage', page);
            } catch (error) {
                console.error('Error fetching filtered applicants:', error);
            }
        },
        async searchApplicants({ commit, state }, { page = 1, perPage = 12 } = {}) {
            try {
                const response = await axios.get('/api/search-applicants', {
                    params: {
                        search: state.searchQuery,
                        page,
                        per_page: perPage
                    }
                });
                commit('setSearchedApplicants', response.data);
                commit('setCurrentPage', page);
            } catch (error) {
                console.error('Error searching applicants:', error);
            }
        },
        resetFilters({ commit }) {
            commit('resetFilters');
        },
        setSearchQuery({ commit }, query) {
            commit('setSearchQuery', query);
        },
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
            const userAsString = JSON.stringify(user);
            localStorage.setItem('user', userAsString);
        },
        setEditMode(state) {
            state.editMode = !state.editMode;
        },
        setSearchMode(state, newVal) {
            console.log(newVal)
            state.searchMode = newVal;
        },
        clearValidationErrors(state) {
            state.invalidFields = [];
        },
        setValidationErrors(state, errors) {
            state.invalidFields = errors.map(error => error.field);
        },
        setAuthStatus(state, isAuthenticated) {
            state.isAuthenticated = isAuthenticated;
        },
        setFilteredApplicants(state, applicants) {
            state.filteredApplicants = applicants;
        },
        setSearchedApplicants(state, applicants) {
            state.searchedApplicants = applicants;
        },
        updateFilter(state, { key, value }) {
            if (key === 'mainSpecializations' || key === 'subSpecialities') {
                state.filters[key] = Array.isArray(value) ? value : [value].filter(Boolean);
            } else {
                state.filters[key] = value;
            }
        },
        resetFilters(state) {
            state.filters = {
                experience: [2, 6],
                age: [19, 26],
                gender: null,
                workAvailability: null,
                freshGraduate: null,
                mainSpecializations: [],
                subSpecialities: [],
                degree: null,
                city: null,
                zone: null,
            };
        },
        setCurrentPage(state, page) {
            state.currentPage = page;
        },
        setSearchQuery(state, query) {
            state.searchQuery = query;
        },
    },
    getters: {
        user: (state) => state.user,
        isAuthenticated: (state) => state.user != null,
        invalidFields: (state) => state.invalidFields,
        editMode: state => state.editMode,
        searchMode: state => state.searchMode,
        filteredApplicants: state => state.filteredApplicants,
        searchedApplicants: state => state.searchedApplicants,
        filters: state => state.filters,
        currentPage: state => state.currentPage,
        searchQuery: state => state.searchQuery,
    }
});
