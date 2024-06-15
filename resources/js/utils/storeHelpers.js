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
export const editMode = computed({
    get() {
        return store.getters.editMode;
    },
    set() {
        store.dispatch('setEditMode', false)
    }
})


export const countWords = (summary) => {
    return computed(() => {
        const words = summary?.trim().split(/\s+/);
        const filteredWords = words?.filter(word => word !== '');
        return filteredWords?.length;
    });
};

export const getSelectables = async (key) => {
    const response = await axios.get(`/api/selectables/${key}`)
    const {data} = response;
    return data;

};


