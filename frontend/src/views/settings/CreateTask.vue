<template>
  <div class="container my-5">
    <div class="row justify-content-center editprofile-page">
      <div class="card shadow" style="width: 100%; max-width: 600px;">
        <!-- Card Header -->
        <div class="text-center my-2">
          <h2 class="h3 my-2">
            <i class="fa-solid fa-gear fs-4 me-2"></i>
            Create Task
          </h2>
        </div>

        <hr class="my-0 mb-2">

        <div class="card-body">
          <!-- Form -->
          <form @submit.prevent="submitTask">
            <!-- Title -->
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input v-model="task.title" type="text" class="form-control" id="title" placeholder="Enter task title" required>
            </div>

            <!-- Category -->
            <div class="mb-3">
              <label for="category" class="form-label">Category</label>
              <select v-model="task.category_id" class="form-select" id="category" required>
                <option value="" disabled>Select category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.name }}
                </option>
              </select>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea v-model="task.description" class="form-control" id="description" rows="3" placeholder="Enter task description"></textarea>
            </div>

            <!-- Task Duration -->
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="start_date" class="form-label">Start Date</label>
                <input v-model="task.start_date" type="date" class="form-control" id="start_date" required>
              </div>
              <div class="col-md-6">
                <label for="due_date" class="form-label">End Date</label>
                <input v-model="task.due_date" type="date" class="form-control" id="due_date" required>
              </div>
            </div>

            <!-- Cost -->
            <div class="mb-3">
              <label for="cost" class="form-label">Cost</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input v-model="task.cost" type="number" step="0.01" class="form-control" id="cost" placeholder="Enter cost">
              </div>
            </div>

            <!-- Priority -->
            <div class="mb-3">
              <label for="priority" class="form-label">Priority</label>
              <select v-model="task.priority" class="form-select" id="priority">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
              </select>
            </div>

            <!-- Status -->
            <div class="mb-4">
              <label class="form-label">Status</label>
              <div class="text-center">
                <div class="form-check form-check-inline me-5">
                  <input v-model="task.status" class="form-check-input" type="radio" id="statusPending" value="pending">
                  <label class="form-check-label" for="statusPending">Pending</label>
                </div>
                <div class="form-check form-check-inline me-5">
                  <input v-model="task.status" class="form-check-input" type="radio" id="statusProgress" value="in_progress">
                  <label class="form-check-label" for="statusProgress">Progress</label>
                </div>
                <div class="form-check form-check-inline">
                  <input v-model="task.status" class="form-check-input" type="radio" id="statusCompleted" value="completed">
                  <label class="form-check-label" for="statusCompleted">Completed</label>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-center py-2">
              <button type="submit" class="btn button me-5 w-25">Save</button>
              <button type="button" @click="cancelTask" class="btn btn-secondary w-25">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { createTask } from '@/api/tasks';
import { getCategories } from '@/api/categories';
import { csrfApi } from '@/plugins/axios';
import { useRouter } from 'vue-router';

const router = useRouter();

// フォームデータ
const task = ref({
  category_id: '', // カテゴリ選択があればここでセット
  title: '',
  description: null,
  start_date: '',
  due_date: '',
  cost: null,
  priority: 'medium',
  status: 'pending',
});

const categories = ref([]);

// CSRFトークン取得
async function getCsrf() {
  await csrfApi.get('/sanctum/csrf-cookie');
}

// フォーム送信
async function submitTask() {
  try {
    await getCsrf(); // CSRF トークン取得
    const response = await createTask(task.value);
    // console.log('Task created:', response.data.task);
    router.push({ name: 'Progress' });
  } catch (error) {
    console.error('Error creating task:', error.response?.data || error);
  }
}

// キャンセル
function cancelTask() {
  router.back();
}

// マウント時にカテゴリー取得
onMounted(async () => {
  try {
    const response = await getCategories();
    categories.value = response.data.categories;
  } catch (error) {
    console.error('Error fetching categories:', error.response?.data || error);
  }
});
</script>