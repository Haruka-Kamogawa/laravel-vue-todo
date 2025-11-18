import { api } from '@/plugins/axios';

export function getUsers(params) {
    return api.get('/admin/users', { params });
}
export function deleteUser(id) {
    return api.delete(`/admin/users/${id}/delete`);
}

export function getCategories(params) {
    return api.get('/admin/categories', { params });
}
export function deleteCategory(id) {
    return api.delete(`/admin/categories/${id}/delete`);
}

export function getTasks(params){
    return api.get('/admin/tasks', { params });
}
export function deleteTask(id){
    return api.delete(`/admin/tasks/${id}/delete`);
}