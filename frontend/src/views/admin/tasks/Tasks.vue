<template>
  <div class="container py-5">
    <div class="serch">
      <form @submit.prevent="searchTasks" action="#" method="#" class="d-flex">
        <input v-model="search" type="text" for="text" class="serch form-control w-50 me-2" placeholder="Search by task title
">
        <button class="btn button">Search</button>
      </form>
    </div>

    <table class="table table-hover align-middle mt-4 text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Username</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="t in tasks" :key="t.id">
          <td>{{ t.id }}</td>
          <td>{{ t.title }}</td>
          <td>{{ t.name }}</td>
          <td>{{ t.username }}</td>
          <td>{{ t.status}}</td>
          <td>
            <button class="btn btn-sm text-danger" @click="openDeleteModal(t)">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- 削除モーダル -->
    <DeleteModal :show="showModal" :message="`Are you sure you want to delete「${selectedTask?.title}」？`" @confirm="confirmDelete" @cancel="showModal = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getTasks, deleteTask } from '@/api/admin';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import DeleteModal from '@/views/admin/tasks/DeleteModal.vue';

const tasks = ref([]);
const error = ref(null);
const showModal = ref(false);
const selectedTask = ref(null);
const search = ref("");

const auth = useAuthStore();
const router = useRouter();


onMounted(async () => {
  if (!auth.user || auth.user.is_admin !== 1) {
      router.push({ name: 'Progress' });
      return;
  }

  await loadTasks();
});

const loadTasks = async ( searchText = "" ) => {
  try {
    const res = await getTasks({ search: searchText });
    tasks.value = res.data.tasks;
  } catch (e) {
    if (e.response && (e.response.status === 401 || e.response.status === 403)) {
        router.push({ name: 'Progress' });
        return;
    }
    error.value = 'Failed to load tasks';
  }
};
    
const searchTasks = () => {
  loadTasks(search.value);
};


const openDeleteModal = (task) => {
    selectedTask.value = task;
    showModal.value = true;
};

const confirmDelete = async () => {
    if (!selectedTask.value) return;

    try {
        await deleteTask(selectedTask.value.id);

        // Vue 側の配列から削除
        tasks.value = tasks.value.filter(u => u.id !== selectedTask.value.id);

    } catch (e) {
        error.value = 'Failed to delete task';
    }

    showModal.value = false;
};
</script>