<template>
  <div class="modal-backdrop" @click.self="close">
    <div class="modal-body bg-white rounded p-4" style="width: 100%; max-width: 600px;">
      <div class="text-center mb-3">
        <h2 class="h3">
          <i class="fa-solid fa-pen-to-square fs-4 me-2"></i>
          Edit Task
        </h2>
      </div>

      <hr class="my-0 mb-3">

      <form @submit.prevent="save">
        <!-- Title -->
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input v-model="local.title" class="form-control" required />
        </div>

        <!-- Category -->
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select v-model="local.category_id" class="form-select" required>
            <option value="" disabled>Select category</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
              {{ cat.name }}
            </option>
          </select>
        </div>

        <!-- Description -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea v-model="local.description" class="form-control" rows="3"></textarea>
        </div>

        <!-- Duration -->
        <div class="mb-3 row">
          <div class="col-md-6">
            <label class="form-label">Start Date</label>
            <input v-model="local.start_date" type="date" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">End Date</label>
            <input v-model="local.due_date" type="date" class="form-control" required>
          </div>
        </div>

        <!-- Cost -->
        <div class="mb-3">
          <label class="form-label">Cost</label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input v-model="local.cost" type="number" step="0.01" class="form-control">
          </div>
        </div>

        <!-- Priority -->
        <div class="mb-3">
          <label class="form-label">Priority</label>
          <select v-model="local.priority" class="form-select">
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
              <input v-model="local.status" class="form-check-input" type="radio" value="pending">
              <label class="form-check-label">Pending</label>
            </div>
            <div class="form-check form-check-inline me-5">
              <input v-model="local.status" class="form-check-input" type="radio" value="in_progress">
              <label class="form-check-label">Progress</label>
            </div>
            <div class="form-check form-check-inline">
              <input v-model="local.status" class="form-check-input" type="radio" value="completed">
              <label class="form-check-label">Completed</label>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-center py-2">
          <button type="submit" class="btn button me-5 w-25">Save</button>
          <button type="button" class="btn btn-secondary w-25" @click="close">Cancel</button>
        </div>
      </form>

    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch, onMounted } from 'vue'
import { api } from '@/plugins/axios'
import { getCategories } from '@/api/categories'

const props = defineProps({ task: Object })
const emit = defineEmits(['close'])

const categories = ref([])

// 追加：日付整形関数
const formatDate = (value) => {
  if (!value) return ''
  return value.split('T')[0] // ← これでOK
}

// local 作成（初期値）
const local = reactive({
  ...props.task,
  start_date: formatDate(props.task.start_date),
  due_date: formatDate(props.task.due_date)
})

// props.task が更新された時も同期させる
watch(() => props.task, (task) => {
  local.title = task.title
  local.category_id = task.category_id
  local.description = task.description
  local.start_date = formatDate(task.start_date)
  local.due_date = formatDate(task.due_date)
  local.cost = task.cost
  local.priority = task.priority
  local.status = task.status
}, { deep: true })

const save = async () => {
  await api.put(`/tasks/${props.task.id}`, local)
  emit('close')
  window.dispatchEvent(new CustomEvent('refresh-counts'))
}

const close = () => emit('close')

onMounted(async () => {
  const res = await getCategories()
  categories.value = res.data.categories
})
</script>

<style scoped>
.modal-backdrop {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.45);
  display: flex; justify-content: center; align-items: center;
}
.modal-body { width: 400px; max-width: 92%; }
</style>