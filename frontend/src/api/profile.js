import { api } from '@/plugins/axios';

export function getProfile() {
    return api.get('/profile');
}
export function updateProfile(payload) {
    return api.put('/profile', payload);
}
export function changePassword(payload) {
    return api.put('/profile/password', payload)
}