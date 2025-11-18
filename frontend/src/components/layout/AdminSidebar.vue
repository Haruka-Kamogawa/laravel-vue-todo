<template>
  <div class="col-3 w-75" style="margin-top: 150px;">
    <div class="list-group">
      <router-link to="/admin/users" class="list-group-item list-group-item-action" :class="{ active: isActive('/admin/users') }">
        Users
      </router-link>

      <router-link to="/admin/categories" class="list-group-item list-group-item-action" :class="{ active: isActive('/admin/categories') }">
        Categories
      </router-link>

      <router-link to="/admin/tasks" class="list-group-item list-group-item-action" :class="{ active: isActive('/admin/tasks') }">
        Tasks
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getCounts } from '@/api/tasks';

const route = useRoute();
const router = useRouter();




const isActive = (path) => route.path === path;


</script>

<style scoped>
.list-group-item.active {
  background-color: #FFD803;
  color: #272343;
  border: none;
}
</style>