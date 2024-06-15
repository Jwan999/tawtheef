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
        }
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
        setSearchMode(state) {
            state.searchMode = !state.searchMode;
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

    },
    getters: {
        user: (state) => state.user,
        isAuthenticated: (state) => state.user != null,
        invalidFields: (state) => state.invalidFields,
        editMode: state => state.editMode,
        searchMode: state => state.searchMode
    }
});

