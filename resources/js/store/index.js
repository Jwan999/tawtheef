import {createStore} from 'vuex';
import axios from "axios";
import {getAuthUser} from "../utils/storeHelpers.js";

function getUserFromLocalStorage() {
    try {
        const userString = localStorage.getItem('user');
        return userString ? JSON.parse(userString) : null;
    } catch (error) {
        console.error('Error parsing user data from localStorage:', error);
        localStorage.removeItem('user'); // Clear the invalid data
        return null;
    }
}
export default createStore({
    state: {
        lastScrollPosition: 0,
        isFormValid: false,
        editMode: false,
        previewMode: false,
        canEdit: false,
        searchMode: false,
        invalidFields: [],
        user: getUserFromLocalStorage() || null,
        filteredApplicants: {
            data: [],
            current_page: 1,
            last_page: 1,
            per_page: 12,
            total: 0
        },
        searchedApplicants: {
            data: [],
            current_page: 1,
            last_page: 1,
            per_page: 12,
            total: 0
        },
        currentPage: 1,
        filters: {
            experience: null,
            age: null,
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
        error: null,
    },
    actions: {
        // setFormValidity({commit, state}, isValid) {
        //     if (state.user?.city === 'Baghdad' && (!state.user.zone || state.user.zone === 'Choose your zone...')) {
        //         commit('setFormValidity', true);
        //         commit('setDefaultZone');
        //     } else {
        //         commit('setFormValidity', isValid);
        //     }
        // },
        setLastState({ commit }, lastState) {
            commit('setLastState', lastState);
        },
        setEditMode({commit}, isEdit) {
            commit('setEditMode', isEdit);
        },
        checkEditMode({commit}, url) {
            if (url.includes('profile')) {
                commit('setEditMode', true);
            }
        },
        setPreviewMode({commit}, isPreview) {
            commit('setPreviewMode', isPreview);
        },
        setCanEdit({commit}, canEdit) {
            commit('setCanEdit', canEdit);
        },
        setSearchMode({commit}, isSearchMode) {
            commit('setSearchMode', isSearchMode);
            if (!isSearchMode) {
                commit('setSearchQuery', '');
            }
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
                console.error('Error fetching auth status:', error);
                commit('setAuthStatus', false);
            }
        },
        setFilters({commit}, filters) {
            commit('setFilters', filters);
        },

        async getFilteredApplicants({commit, state}, {page = 1, perPage = 12} = {}) {
            try {
                commit('clearError');
                const response = await axios.get('/applicants/filter', {
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
                if (error.response) {
                    console.error('Server responded with:', error.response.data);
                }
                commit('setError', 'Failed to fetch filtered applicants');
            }
        },
        async searchApplicants({commit, state}, {page = 1, perPage = 12} = {}) {
            try {
                commit('clearError');
                commit('clearSearchedApplicants');
                const response = await axios.get('/api/applicants/search', {
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
                if (error.response && error.response.data) {
                    console.error('Server responded with:', error.response.data);
                    commit('setError', `Failed to search applicants: ${error.response.data.error || error.response.data.message || 'Unknown error'}`);
                } else {
                    commit('setError', 'Failed to search applicants: Network error');
                }
                commit('setSearchedApplicants', {data: [], current_page: 1, last_page: 1, per_page: 12, total: 0});
            }
        },
        resetFilters({commit}) {
            commit('resetFilters');
        },
        setSearchQuery({commit}, query) {
            commit('setSearchQuery', query);
        },
    },
    mutations: {
        setLastScrollPosition(state, position) {
            state.lastScrollPosition = position;
        },
        setLastState(state, lastState) {
            state.lastState = lastState;
        },
        restoreLastState(state) {
            state.searchMode = state.lastState.searchMode;
            state.filters = { ...state.lastState.filters };
            state.lastScrollPosition = state.lastState.scrollPosition;
        },
        // setFormValidity(state, isValid) {
        //     state.isFormValid = isValid;
        // },
        setUser(state, user) {
            state.user = user;
            const userAsString = JSON.stringify(user);
            localStorage.setItem('user', userAsString);
        },
        setEditMode(state, isEdit) {
            state.editMode = isEdit;
        },
        setPreviewMode(state, isPreview) {
            state.previewMode = isPreview;
            if (isPreview) {
                state.editMode = false;
            }
        },
        setCanEdit(state, canEdit) {
            state.canEdit = canEdit;
        },
        setSearchMode(state, isSearchMode) {
            state.searchMode = isSearchMode;
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
        clearSearchedApplicants(state) {
            state.searchedApplicants = {
                data: [],
                current_page: 1,
                last_page: 1,
                per_page: 12,
                total: 0
            };
        },
        setFilters(state, filters) {
            state.filters = {...state.filters, ...filters};
        },
        updateFilter(state, {key, value}) {
            if (key === 'mainSpecializations' || key === 'subSpecialities') {
                state.filters[key] = Array.isArray(value) ? value : [value].filter(Boolean);
            } else {
                state.filters[key] = value;
            }
        },
        resetFilters(state) {
            state.filters = {
                experience: null,
                age: null,
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
        setDefaultZone(state) {
            if (state.user.city === 'Baghdad' && (!state.user.zone || state.user.zone === 'Choose your zone...')) {
                state.user.zone = 'Default Zone';
            }
        },
        clearFilteredApplicants(state) {
            state.filteredApplicants = {
                data: [],
                current_page: 1,
                last_page: 1,
                per_page: 12,
                total: 0
            };
        },
        setError(state, error) {
            state.error = error;
        },
        clearError(state) {
            state.error = null;
        },
    },
    getters: {
        lastState: state => state.lastState,
        getCurrentApplicantId: (state) => state.user?.applicant?.id || null,
        isFormValid: state => state.isFormValid,
        user: (state) => state.user,
        isAuthenticated: (state) => state.user !== null,
        invalidFields: (state) => state.invalidFields,
        editMode: state => state.editMode,
        isPreviewMode: state => state.previewMode,
        canEdit: state => state.canEdit,
        searchMode: state => state.searchMode,
        filteredApplicants: state => state.filteredApplicants,
        searchedApplicants: state => state.searchedApplicants,
        filters: state => state.filters,
        currentPage: state => state.currentPage,
        searchQuery: state => state.searchQuery,
        error: state => state.error,
    }
});
