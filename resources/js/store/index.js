import { createStore } from 'vuex';
import axios from "axios";
import { getAuthUser } from "../utils/storeHelpers.js";

// Configure axios defaults
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

function getUserFromLocalStorage() {
    try {
        const userString = localStorage.getItem('user');
        if (!userString) return null;

        if (typeof userString !== 'string' || !userString.trim().startsWith('{')) {
            localStorage.removeItem('user');
            return null;
        }
        return JSON.parse(userString);
    } catch (error) {
        console.error('Error parsing user data from localStorage:', error);
        localStorage.removeItem('user');
        return null;
    }
}

const defaultFilters = {
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
};

export default createStore({
    state: {
        loading: false,
        isAdmin: false,
        statistics: null,
        advanceSearchInUse: false,
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
        filters: { ...defaultFilters },
        searchQuery: '',
        error: null,
        activeSpecialization: 'Latest',
        infiniteLoadingStatus: {
            hasMore: true,
            loading: false
        },
    },
    actions: {
        async checkAuth({ commit }) {
            try {
                const response = await axios.get('/api/auth');
                if (response.data) {
                    commit('setUser', response.data);
                    return response.data;
                } else {
                    commit('setUser', null);
                    return null;
                }
            } catch (error) {
                console.error('Error checking auth status:', error);
                commit('setUser', null);
                return null;
            }
        },

        async login({ commit }, { email, password, recaptcha_token }) {
            try {
                const response = await axios.post('/login', {
                    email,
                    password,
                    recaptcha_token
                });

                if (response.data.user) {
                    commit('setUser', response.data.user);
                }
                return response;
            } catch (error) {
                throw error;
            }
        },

        async logout({ commit }) {
            try {
                await axios.post('/logout');
                commit('setUser', null);
                commit('clearError');
            } catch (error) {
                console.error('Logout error:', error);
                throw error;
            }
        },

        async signup({ commit }, userData) {
            try {
                const response = await axios.post('/signup', userData);
                if (response.data.user) {
                    commit('setUser', response.data.user);
                }
                return response;
            } catch (error) {
                throw error;
            }
        },

        async forgotPassword({ commit }, email) {
            try {
                const response = await axios.post('/forgot-password', { email });
                return response.data;
            } catch (error) {
                throw error;
            }
        },

        async resetPassword({ commit }, credentials) {
            try {
                const response = await axios.post('/reset-password', credentials);
                return response.data;
            } catch (error) {
                throw error;
            }
        },

        async fetchStatistics({ commit }) {
            try {
                const response = await axios.get('/api/statistics');
                commit('setStatistics', response.data);
            } catch (error) {
                console.error('Error fetching statistics:', error);
            }
        },

        async searchApplicantsInfinite({ commit, state }, { page = 1, perPage = 12 } = {}) {
            try {
                commit('updateInfiniteLoadingStatus', { loading: true, hasMore: state.infiniteLoadingStatus.hasMore });

                const response = await axios.get('/api/applicants/search', {
                    params: {
                        search: state.searchQuery,
                        ...state.filters,
                        page,
                        per_page: perPage
                    }
                });

                const newApplicants = response.data;
                if (page === 1) {
                    commit('setSearchedApplicants', newApplicants);
                } else {
                    commit('appendSearchedApplicants', {
                        ...newApplicants,
                        data: [...state.searchedApplicants.data, ...newApplicants.data]
                    });
                }

                commit('updateInfiniteLoadingStatus', {
                    hasMore: newApplicants.current_page < newApplicants.last_page,
                    loading: false
                });
            } catch (error) {
                console.error('Error searching applicants:', error);
                commit('updateInfiniteLoadingStatus', { loading: false, hasMore: false });
                throw error;
            }
        },

        async getFilteredApplicants({ commit, state }, { page = 1, perPage = 12, sortBy = 'created_at', sortOrder = 'asc' } = {}) {
            try {
                commit('updateInfiniteLoadingStatus', { loading: true, hasMore: state.infiniteLoadingStatus.hasMore });

                const params = new URLSearchParams();
                params.append('page', page);
                params.append('per_page', perPage);
                params.append('sort_by', sortBy);
                params.append('sort_order', sortOrder);

                if (state.activeSpecialization !== 'Latest') {
                    params.append('mainSpecializations[]', state.activeSpecialization);
                }

                Object.entries(state.filters).forEach(([key, value]) => {
                    if (key === 'mainSpecializations' && state.activeSpecialization !== 'Latest') {
                        return;
                    }

                    if (Array.isArray(value) && value.length > 0) {
                        value.forEach(item => params.append(`${key}[]`, item));
                    } else if (value && typeof value === 'object' && !Array.isArray(value)) {
                        if ('min' in value) params.append(`${key}_min`, value.min);
                        if ('max' in value) params.append(`${key}_max`, value.max);
                    } else if (typeof value === 'boolean') {
                        params.append(key, value);
                    } else if (value !== null && value !== undefined && value !== '') {
                        params.append(key, value);
                    }
                });

                const response = await axios.get('/api/applicants/filter', { params });

                if (page === 1) {
                    commit('setFilteredApplicants', response.data);
                } else {
                    commit('appendFilteredApplicants', {
                        ...response.data,
                        data: [...state.filteredApplicants.data, ...response.data.data]
                    });
                }

                commit('updateInfiniteLoadingStatus', {
                    loading: false,
                    hasMore: response.data.current_page < response.data.last_page
                });
            } catch (error) {
                console.error('Error in getFilteredApplicants:', error);
                commit('updateInfiniteLoadingStatus', { loading: false, hasMore: false });
                throw error;
            }
        },

        setAdvanceSearchInUse({ commit }, isInUse) {
            commit('setAdvanceSearchInUse', isInUse);
        },

        resetFilters({ commit }) {
            commit('resetFilters');
        },

        updateFilter({ commit, state, dispatch }, { key, value }) {
            commit('updateFilter', { key, value });

            if (key === 'mainSpecializations' && (!value || value.length === 0)) {
                commit('setActiveSpecialization', 'Latest');
                dispatch('getFilteredApplicants', {
                    page: 1,
                    sortBy: 'created_at',
                    sortOrder: 'desc'
                });
            }
        },

        setEditMode({ commit }, isEdit) {
            commit('setEditMode', isEdit);
        },

        checkEditMode({ commit }, url) {
            if (url.includes('profile')) {
                commit('setEditMode', true);
            }
        },

        setPreviewMode({ commit }, isPreview) {
            commit('setPreviewMode', isPreview);
        },

        setCanEdit({ commit }, canEdit) {
            commit('setCanEdit', canEdit);
        },

        setSearchMode({ commit }, isSearchMode) {
            commit('setSearchMode', isSearchMode);
            if (!isSearchMode) {
                commit('setSearchQuery', '');
            }
        },

        async getUser({ commit }) {
            try {
                const user = await getAuthUser();
                commit('setUser', user);
                return user;
            } catch (error) {
                console.error('Error fetching user:', error);
                commit('setUser', null);
                return null;
            }
        },

        setFilters({ commit }, filters) {
            commit('setFilters', filters);
        },

        setSearchQuery({ commit }, query) {
            commit('setSearchQuery', query);
        },
    },
    mutations: {
        setLoading(state, value) {
            state.loading = value;
        },

        setStatistics(state, data) {
            state.statistics = data;
        },

        setActiveSpecialization(state, specialization) {
            state.activeSpecialization = specialization || 'Latest';
        },

        updateInfiniteLoadingStatus(state, { hasMore, loading }) {
            state.infiniteLoadingStatus = { hasMore, loading };
        },

        appendSearchedApplicants(state, applicants) {
            state.searchedApplicants = applicants;
        },

        appendFilteredApplicants(state, applicants) {
            state.filteredApplicants = applicants;
        },

        setAdvanceSearchInUse(state, isInUse) {
            state.advanceSearchInUse = isInUse;
        },

        setUser(state, user) {
            state.user = user;
            state.isAdmin = user?.role === 'admin';
            if (user) {
                localStorage.setItem('user', JSON.stringify(user));
            } else {
                localStorage.removeItem('user');
            }
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
            state.infiniteLoadingStatus.hasMore = applicants.current_page < applicants.last_page;
        },

        clearSearchedApplicants(state) {
            state.searchedApplicants = {
                data: [],
                current_page: 1,
                last_page: 1,
                per_page: 12,
                total: 0
            };
            state.infiniteLoadingStatus.hasMore = false;
        },

        setFilters(state, filters) {
            state.filters = { ...state.filters, ...filters };
        },

        updateFilter(state, { key, value }) {
            state.filters[key] = value;
        },

        resetFilters(state) {
            state.filters = { ...defaultFilters };
        },

        setCurrentPage(state, page) {
            state.currentPage = page;
        },

        setSearchQuery(state, query) {
            state.searchQuery = query;
        },

        setDefaultZone(state) {
            if (state.user?.city === 'Baghdad' && (!state.user?.zone || state.user?.zone === 'Choose your zone...')) {
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
            state.infiniteLoadingStatus.hasMore = false;
        },

        setError(state, error) {
            state.error = error;
        },

        clearError(state) {
            state.error = null;
        },
    },
    getters: {
        isLoading: state => state.loading,
        isAdmin: state => state.isAdmin,
        hasMoreResults: state => state.infiniteLoadingStatus.hasMore,
        isLoadingMore: state => state.infiniteLoadingStatus.loading,
        advanceSearchInUse: state => state.advanceSearchInUse,
        getCurrentApplicantId: (state) => state.user?.applicant?.id || null,
        isFormValid: state => state.isFormValid,
        user: (state) => state.user,
        isAuthenticated: (state) => !!state.user,
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
        activeFilters: (state) => {
            return Object.entries(state.filters).reduce((acc, [key, value]) => {
                if (value !== null && value !== '' && !(Array.isArray(value) && value.length === 0)) {
                    acc[key] = value;
                }
                return acc;
            }, {});
        },
    }
});
