import { createRouter, createWebHistory } from 'vue-router';
import MainLayout from '@/layouts/MainLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import Register from '@/views/auth/Register.vue';
import Login from '@/views/auth/Login.vue';
import Pending from '@/views/tasks/Pending.vue';
import Progress from '@/views/tasks/Progress.vue';
import Completed from '@/views/tasks/Completed.vue';
import CreateTask from '@/views/settings/CreateTask.vue';
import Settings from '@/views/settings/Settings.vue';
import { useAuthStore } from '@/stores/auth';
import Category from '@/views/settings/Category.vue';
import EditProfile from '@/views/settings/EditProfile.vue';
import ChangePassword from '@/views/settings/ChangePassword.vue';
import AdminUsers from '@/views/admin/users/Users.vue';
import AdminCategories from '@/views/admin/categories/Categories.vue';
import AdminTasks from '@/views/admin/tasks/Tasks.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

const routes = [
    { path: '/', redirect: { name: 'Login' } },
    {
        path: '/',
        component: AuthLayout,
        children: [
            { path: 'register', name: 'Register', component: Register },
            { path: 'login', name: 'Login', component: Login },
            { path: 'create-task', name: 'CreateTask', component: CreateTask, meta: { requiresAuth: true } },
            { path: 'settings', name: 'Settings', component: Settings, meta: { requiresAuth: true } },
            { path: 'settings/categories', name: 'Category', component: Category, meta: { requiresAuth: true } },
            { path: 'settings/edit-profile', name: 'EditProfile', component: EditProfile, meta: { requiresAuth: true } },
            { path: 'settings/change-password', name: 'ChangePassword', component: ChangePassword, meta: { requiresAuth: true } },
        ],
    },
    { 
        path: '/',
        component: MainLayout,
        children: [
            { path: 'pending', name: 'Pending', component: Pending, meta: { requiresAuth: true } },
            { path: 'progress', name: 'Progress', component: Progress, meta: { requiresAuth: true } },
            { path: 'completed', name: 'Completed', component: Completed, meta: { requiresAuth: true } },
        ],
    },
    {
        path: '/admin/',
        component: AdminLayout,
        children: [
            { path: 'users', name: 'AdminUsers', component: AdminUsers, meta: { requiresAuth: true, requiresAdmin: true } },
            { path: 'categories', name: 'AdminCategories', component: AdminCategories, meta: { requiresAuth: true, requiresAdmin: true } },
            { path: 'tasks', name: 'AdminTasks', component: AdminTasks, meta: { requiresAuth: true, requiresAdmin: true } },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();

    if (to.meta.requiresAuth && !auth.user) {
        try {
            await auth.fetchUser();
        }catch (err) {
            return { name: 'Login' };
        }
    }

    if (to.meta.requiresAdmin) {
        if (!auth.user || auth.user.is_admin !== 1) {
            return { name: 'Progress' };
        }
    }
});

export default router;