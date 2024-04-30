// resources/js/store/index.js

import {createStore} from 'vuex';

export default createStore({
    state: {
        editMode: false,
        invalidFields: []
    },
    actions: {
        setEditMode({commit}) { // Action named setEditMode
            commit('setEditMode');
        },
        checkEditMode({ commit }, url) {
            if (url.includes('edit')) {
                commit('setEditMode');
            }
        }
    },
    mutations: {
        setEditMode(state) {
            state.editMode = !state.editMode; // Toggle the editMode value in state
        },
        clearValidationErrors(state) {
            state.invalidFields = [];
        },
        // Mutation to update invalid fields array (replace with actual validation logic)
        setValidationErrors(state, errors) {
            state.invalidFields = errors.map(error => error.field); // Extract field names
        }
    },
    getters: {
        invalidFields: (state) => state.invalidFields,
        editMode: state => state.editMode
    }
});

