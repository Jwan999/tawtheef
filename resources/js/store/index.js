// resources/js/store/index.js

import { createStore } from 'vuex';

export default createStore({
    state: {
        editMode: false
    },
    mutations: {
        toggleEditMode(state) {
            state.editMode = !state.editMode;
        }
    },
    actions: {
        toggleEditMode({ commit }) {
            commit('toggleEditMode');
        }
    },
    getters: {
        editMode: state => state.editMode
    }
});
