import axios from "axios";

const apiUrl = import.meta.env.VITE_KEY;


console.log(apiUrl);
export const fetchCategories = async () => {
    try {
        const response = await axios.get(`${apiUrl}/products/categories`);
        return response.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
        throw error;
    }
};


export const createUser = async (data) => {
    try {
        console.log(data)
        const response = await axios.post(`${apiUrl}/users`, data);
        return response.data;
    } catch (error) {
        console.error('Error while creating user:', error);
        throw error;
    }
};