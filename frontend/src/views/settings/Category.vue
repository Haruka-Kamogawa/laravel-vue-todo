<template>
  <div class="container mt-5">
    <div class="row justify-content-center setting-page">
      <div class="card shadow" style="width: 100%; max-width: 600px;">
        <div class="text-center my-2">
          <h2 class="h3 my-2">
            <i class="fa-solid fa-folder-plus fs-4 me-2"></i>
            Manage Categories
          </h2>
        </div>


        <hr class="my-0 mb-3">

        <div class="card-body">
          <!-- 作成・編集フォーム -->
          <form @submit.prevent="submitCategory">
            <div class="mb-3">
              <label for="name" class="form-label">Category Name</label>
              <input v-model="category.name" type="text" class="form-control" id="name" placeholder="Enter category name" required>
            </div>

            <div class="d-flex justify-content-center py-2">
              <button type="submit" class="btn button me-5 w-25">{{ isEditing ? 'Update' : 'Save' }}</button>
              <button type="button" @click="cancelCategory" class="btn btn-secondary w-25">Cancel</button>
            </div>
          </form>

          <hr>

          <!-- カテゴリー一覧 -->
          <div class="mt-4">
            <h5>Your Categories</h5>
            <ul class="list-group">
              <li v-for="cat in categories" :key="cat.id" class="list-group-item d-flex justify-content-between align-items-center">
                {{ cat.name }}
                <div>
                  <button @click="editCategory(cat)" class="btn btn-sm btn-primary me-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </button>
                  <button @click="deleteCategoryItem(cat.id)" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getCategories, createCategory, updateCategory, deleteCategory } from '@/api/categories';
import { csrfApi } from '@/plugins/axios';
import { useRouter } from 'vue-router';

const router = useRouter();

// カテゴリフォーム
const category = ref({ name: '', id: null });
const isEditing = ref(false);

// カテゴリー一覧
const categories = ref([]);

// CSRF取得
async function getCsrf() {
  await csrfApi.get('/sanctum/csrf-cookie');
}

// 一覧取得
async function fetchCategories() {
  try {
    const response = await getCategories();
    categories.value = response.data.categories;
  } catch (error) {
    console.error('Error fetching categories:', error.response?.data || error);
  }
}

// 作成・更新
async function submitCategory() {
  if (!category.value.name.trim()) {
    alert('Category name is required');
    return;
  }

  try {
    await getCsrf();

    if (isEditing.value) {
      // 更新
      await updateCategory(category.value.id, { name: category.value.name });
    } else {
      // 作成
      await createCategory({ name: category.value.name });
    }

    await fetchCategories();
    resetForm();
  } catch (error) {
    console.error('Error saving category:', error.response?.data || error);
  }
}

// 編集ボタン
function editCategory(cat) {
  category.value = { ...cat };
  isEditing.value = true;
}

// 削除
async function deleteCategoryItem(id) {
  if (!confirm('Are you sure you want to delete this category?')) return;

  try {
    await getCsrf();
    await deleteCategory(id);
    await fetchCategories();
  } catch (error) {
    console.error('Error deleting category:', error.response?.data || error);
  }
}

// キャンセル
function cancelCategory() {
  router.push({ name: 'Settings' });
}

// フォームリセット
function resetForm() {
  category.value = { name: '', id: null };
  isEditing.value = false;
}

// マウント時にカテゴリー取得
onMounted(() => {
  fetchCategories();
});
</script>