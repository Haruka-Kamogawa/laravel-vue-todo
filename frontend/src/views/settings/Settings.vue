<template>
  <div class="container mt-5">
    <div class="row justify-content-center setting-page">
      <div class="card shadow" style="width: 100%; max-width: 600px;">
        <div class="text-center my-2">
          <h2 class="h3 my-2">
            <i class="fa-solid fa-gear fs-4 me-2"></i>
            Settings Page
          </h2>
        </div>

        <hr class="my-0 mb-2"/>

        <div class="card-body">

          <!-- Routerリンクの設定項目 -->
          <div v-for="item in items" :key="item.label" class="setting-item card shadow-sm mb-3">
            <router-link :to="item.to" class="card-body d-flex justify-content-between align-items-center text-dark text-decoration-none">
              <div class="d-flex align-items-center">
                <i :class="item.icon"></i>
                <span>{{ item.label }}</span>
              </div>
              <span class="fw-bold">&gt;</span>
            </router-link>
          </div>

          <!-- Log Out（クリックアクション） -->
          <div class="setting-item card shadow-sm mb-3" @click="logout" style="cursor: pointer;">
            <div class="card-body d-flex justify-content-between align-items-center text-danger">
              <div class="d-flex align-items-center">
                <i class="fa-solid fa-right-from-bracket me-2"></i>
                <span>Log Out</span>
              </div>
              <span class="fw-bold">&gt;</span>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router'

const auth = useAuthStore();
const router = useRouter()

// メニュー項目
const items = [
  { label: 'Profile Setting', to: '/settings/edit-profile', icon: 'fa-solid fa-user me-2' },
  { label: 'Change Password', to: '/settings/change-password', icon: 'fa-solid fa-key me-2' },
  { label: 'Manege Categories', to: '/settings/categories', icon: 'fa-solid fa-folder-plus me-2' },
  { label: 'Create Task', to: '/create-task', icon: 'fa-solid fa-circle-plus me-2' },
]

const logout = () => {
  auth.logout();
  router.push({ name: 'Login' });
};
</script>

<style scoped>
.setting-item {
  border: 1px solid #ddd;
  border-radius: 8px;
  transition: background 0.2s;
}
.setting-item:hover {
  background: #f8f9fa;
}
</style>