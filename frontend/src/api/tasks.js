import { api } from '@/plugins/axios';

export function getTasks(params = {}) {
    return api.get('/tasks', { params });
}
export function getCounts() {
    return api.get('/tasks/counts');
}
export function getTask(id) {
    return api.get(`/tasks/${id}`);
}
export function createTask(payload) {
    return api.post('/tasks', payload);
}
export function updateTask(id, payload) {
    return api.put(`/tasks/${id}`, payload);
}
export function deleteTask(id) {
    return api.delete(`/tasks/${id}`);
}
export function getTaskStats() {
    return api.get('/tasks/stats');
}