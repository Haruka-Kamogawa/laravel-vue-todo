import axios from 'axios';
import Cookies from 'js-cookie';

// API
export const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  withCredentials: true,
});

//CSRF
export const csrfApi = axios.create({
  baseURL: 'http://127.0.0.1:8000',
  withCredentials: true,
})

csrfApi.interceptors.request.use(config => {
    const token = Cookies.get('XSRF-TOKEN');
    if (token) {
        config.headers['X-XSRF-TOKEN'] = token;
    }
    return config;
});