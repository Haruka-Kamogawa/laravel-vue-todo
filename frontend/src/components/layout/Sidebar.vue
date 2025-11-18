<template>
  <div class="col-3 w-75">
    <div class="list-group">
      <router-link to="/pending" class="list-group-item list-group-item-action" :class="{ active: isActive('/pending') }">
        Pending <span class="badge ms-2">{{ counts.pending }}</span>
      </router-link>

      <router-link to="/progress" class="list-group-item list-group-item-action" :class="{ active: isActive('/progress') }">
        Progress <span class="badge ms-2">{{ counts.in_progress }}</span>
      </router-link>

      <router-link to="/completed" class="list-group-item list-group-item-action" :class="{ active: isActive('/completed') }">
        Completed <span class="badge ms-2">{{ counts.completed }}</span>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getCounts } from '@/api/tasks';

const route = useRoute();
const router = useRouter();

const counts = reactive({ pending: 0, in_progress: 0, completed: 0 });

const fetchCounts = async () => {
  try {
    const res = await getCounts();
    Object.assign(counts, res.data.counts);
  } catch (e) {
    // 認証切れならログインへ
    if (e.response?.status === 401) router.push({ name: 'Login' });
    console.error('counts fetch failed', e);
  }
};

const isActive = (path) => route.path === path;

const onRefresh = () => fetchCounts();

onMounted(() => {
  fetchCounts();
  window.addEventListener('refresh-counts', onRefresh);
});
onBeforeUnmount(() => {
  window.removeEventListener('refresh-counts', onRefresh);
});
</script>

<style scoped>
.list-group-item.active {
  background-color: #FFD803;
  color: #272343;
  border: none;
}
</style>