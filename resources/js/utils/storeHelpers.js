import {computed} from "vue";
import store from "../store/index.js";

export const editMode = computed({
    get() {
        return store.getters.editMode;
    },
    set() {
        store.dispatch('setEditMode', true)
    }
})
