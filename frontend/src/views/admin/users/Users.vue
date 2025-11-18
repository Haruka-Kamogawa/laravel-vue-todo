<template>
  <div class="container py-5">
    <div class="serch">
      <form @submit.prevent="searchUsers" action="#" method="#" class="d-flex">
        <input v-model="search" type="text" for="text" class="serch form-control w-50 me-2" placeholder="Search by username
">
        <button class="btn button">Search</button>
      </form>
    </div>

    <table class="table table-hover align-middle mt-4 text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>BirthDate</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="u in users" :key="u.id">
          <td>{{ u.id }}</td>
          <td>{{ u.username }}</td>
          <td>{{ u.email }}</td>
          <td>{{ u.birth_date }}</td>
          <td>{{ formatDate(u.created_at) }}</td>
          <td>
            <button class="btn btn-sm text-danger" @click="openDeleteModal(u)">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- 削除モーダル -->
    <DeleteModal :show="showModal" :message="`Are you sure you want to delete「${selectedUser?.username}」？`" @confirm="confirmDelete" @cancel="showModal = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getUsers, deleteUser } from '@/api/admin';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import DeleteModal from '@/views/admin/users/DeleteModal.vue';

const users = ref([]);
const error = ref(null);
const showModal = ref(false);
const selectedUser = ref(null);
const search = ref("");

const auth = useAuthStore();
const router = useRouter();

const formatDate = (value) => {
    if (!value) return "-";

    if (typeof value === "string") {
        const dateOnly = value.match(/\d{4}-\d{2}-\d{2}/);
        return dateOnly ? dateOnly[0] : value;
    }

    return value;
};

onMounted(async () => {
    if (!auth.user || auth.user.is_admin !== 1) {
        router.push({ name: 'Progress' });
        return;
    }
    await loadUsers();
});

const loadUsers = async ( searchText = "") => {
  try {
    const res = await getUsers({ search: searchText });
    users.value = res.data.users;
  } catch (e) {
    if (e.response && (e.response.status === 401 || e.response.status === 403)) {
        router.push({ name: 'Progress' });
        return;
    }
    error.value = 'Failed to load users';
  }
};

const searchUsers = () => {
  loadUsers(search.value);
;}

const openDeleteModal = (user) => {
    selectedUser.value = user;
    showModal.value = true;
};

const confirmDelete = async () => {
    if (!selectedUser.value) return;

    try {
        await deleteUser(selectedUser.value.id);

        // Vue 側の配列から削除
        users.value = users.value.filter(u => u.id !== selectedUser.value.id);

    } catch (e) {
        error.value = 'Failed to delete user';
    }

    showModal.value = false;
};
</script>