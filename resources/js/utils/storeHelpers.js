import {computed} from "vue";
import store from "../store/index.js";
import axios from 'axios';
// import router from "../router/index.js";

export const getAuthUser = async () => {
    try {
        const response = await axios.get('/api/auth');
        return {
            id: response.data.id,
            name: response.data.name,
            email: response.data.email,
            profileType: response.data.profile_type,
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}
export const editMode = computed({
    get() {
        return store.getters.editMode;
    },
    set() {
        store.dispatch('setEditMode', true)
    }
})

export const countWords = (summary) => {
    return computed(() => {
        // Remove extra white spaces and split the text into words
        const words = summary.trim().split(/\s+/);
        // Filter out empty strings
        const filteredWords = words.filter(word => word !== '');
        // Update the word count
        return filteredWords.length;
    });
};

export const getSelectables = async (key) => {
    const response = await axios.get(`/api/selectables/${key}`)
    const {data} = response;
    return data;

};


