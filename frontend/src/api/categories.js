import { api } from '@/plugins/axios';

export function getCategories() {
    return api.get('/categories');
}
export function createCategory(payload) {
    return api.post('/categories', payload);
}
export function updateCategory(id, payload) {
    return api.put(`/categories/${id}`, payload);
}
export function deleteCategory(id) {
    return api.delete(`/categories/${id}`);
}