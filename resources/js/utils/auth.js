import axios from 'axios';

export const logoutUser = async () => {
    try {
        await axios.post('/logout');
        return true;
    } catch (error) {
        console.error('Logout error:', error);
        return false;
    }
};
