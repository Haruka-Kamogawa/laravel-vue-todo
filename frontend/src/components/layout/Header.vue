<template>
  <nav class="navbar navbar-expand-lg py-3">
    <div class="container">
      <router-link to="/progress" class="navbar-brand fw-bold">Mochiii</router-link>

      <div class="collapse navbar-collapse justify-content-end">

        <!-- for user -->
        <template v-if="user">
          <router-link v-if="user.is_admin === 1" to="/admin/users" class="btn me-3" title="Admin">
            <i class="fa-solid fa-user-shield header-nav"></i>
          </router-link>

          <router-link to="/create-task" class="btn me-3"><i class="fa-solid fa-circle-plus header-nav"></i></router-link>
          <router-link to="/settings" class="btn me-3"><i class="fa-solid fa-gear header-nav"></i></router-link>
          <button class="btn" @click="logout"><i class="fa-solid fa-right-from-bracket header-nav"></i></button>
        </template>

        <!-- for guest -->
        <template v-else>
          <router-link to="/login" class="btn header-nav me-2">Login</router-link>
          <router-link to="/register" class="btn header-nav">Register</router-link>
        </template>

      </div>
    </div>
  </nav>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { computed } from 'vue';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const user = computed(() => auth.user);
const router = useRouter();

const logout = () => {
  auth.logout();
  router.push({ name: 'Login' });
};
</script>