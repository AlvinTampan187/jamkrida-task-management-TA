import axios from 'axios';
import NProgress from 'nprogress';

// Tentukan baseURL berdasarkan environment
const baseURL = import.meta.env.VITE_API_URL;

// Buat instance Axios
export const http = axios.create({
    baseURL, // Gunakan baseURL dari environment
    withCredentials: false,
});

// Tambahkan header Authorization jika token ada
const token = localStorage.getItem('token');
if (token) {
  http.defaults.headers.common.Authorization = `Bearer ${token}`;
}

// Tambahkan request interceptor
http.interceptors.request.use(
    (config) => {
        NProgress.start(); // Mulai progress bar
        return config;
    },
    (error) => {
        NProgress.done(); // Hentikan progress bar jika ada error
        console.error(error);
        return Promise.reject(error);
    }
);

// Tambahkan response interceptor
http.interceptors.response.use(
    (response) => {
        NProgress.done(); // Hentikan progress bar
        return response;
    },
    (error) => {
        NProgress.done(); // Hentikan progress bar jika ada error
        console.error(error);
        return Promise.reject(error);
    }
);
