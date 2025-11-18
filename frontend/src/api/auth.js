import { csrfApi } from '@/plugins/axios'

export const csrf = () => csrfApi.get('/sanctum/csrf-cookie');

export const register = (data) => csrf().then(() => csrfApi.post('/register', data));
export const login = (data) => csrf().then(() => csrfApi.post('/login', data));
export const getUser = () => csrfApi.get('/user');
export const logout = () => csrfApi.post('/logout');