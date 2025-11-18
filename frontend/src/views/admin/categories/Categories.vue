<template>
  <div class="container py-5">
    <div class="serch">
      <form @submit.prevent="searchCategories" action="#" method="#" class="d-flex">
        <input v-model="search" type="text" for="text" class="serch form-control w-50 me-2" placeholder="Search by category name
">
        <button class="btn button">Search</button>
      </form>
    </div>

    <table class="table table-hover align-middle mt-4 text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Username</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="c in categories" :key="c.id">
          <td>{{ c.id }}</td>
          <td>{{ c.name }}</td>
          <td>{{ c.username }}</td>
          <td>{{ formatDate(c.created_at) }}</td>
          <td>
            <button class="btn btn-sm text-danger" @click="openDeleteModal(c)">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- 削除モーダル -->
    <DeleteModal :show="showModal" :message="`Are you sure you want to delete「${selectedCategory?.name}」？`" @confirm="confirmDelete" @cancel="showModal = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getCategories, deleteCategory } from '@/api/admin';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import DeleteModal from '@/views/admin/categories/DeleteModal.vue';

const categories = ref([]);
const error = ref(null);
const showModal = ref(false);
const selectedCategory = ref(null);
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

  await loadCategories();
});

const loadCategories = async (searchText = "") => {
  try {
      const res = await getCategories({ search: searchText });
      categories.value = res.data.categories;
  } catch (e) {
      if (e.response && (e.response.status === 401 || e.response.status === 403)) {
          router.push({ name: 'Progress' });
          return;
      }
      error.value = 'Failed to load users';
  }
};


const searchCategories = () => {
  loadCategories(search.value);
};


const openDeleteModal = (category) => {
    selectedCategory.value = category;
    showModal.value = true;
};

const confirmDelete = async () => {
    if (!selectedCategory.value) return;

    try {
        await deleteCategory(selectedCategory.value.id);

        // Vue 側の配列から削除
        categories.value = categories.value.filter(u => u.id !== selectedCategory.value.id);

    } catch (e) {
        error.value = 'Failed to delete category';
    }

    showModal.value = false;
};
</script>