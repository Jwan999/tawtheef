import {computed} from "vue";
import store from "../store/index.js";
import axios from 'axios';

export const getAuthUser = async () => {
    try {
        const {data} = await axios.get('/api/auth');
        return data;

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

export function countWords(str) {
    if (!str) {
        return 0;
    }
    const words = str.trim().split(/\s+/).filter(word => word.length > 0);
    return words.length;
}
export const editMode = computed({
    get() {
        return store.getters.editMode;
    },
    set() {
        store.dispatch('setEditMode', false)
    }
})




export const getSelectables = async (key) => {
    const response = await axios.get(`/api/selectables/${key}`)
    return response.data;
};


