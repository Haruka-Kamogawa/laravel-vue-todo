<template>
    <div>
        <!-- タブ -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item" v-for="t in tabs" :key="t.key">
                <button class="nav-link" :class="{ active: tab === t.key }" @click="tab = t.key">
                    {{ t.label }}
                </button>
            </li>
        </ul>

        <!-- List / DueDate（通常表示） -->
        <TaskList v-if="['list','duedate'].includes(tab)" :tasks="displayedTasks" @delete-request="confirmDelete"/>

        <!-- Category / Priority（グループ表示） -->
        <div v-else>
            <div v-for="(groupTasks, groupName) in groupedTasks" :key="groupName" class="mb-4">
                <h5 class="fw-semibold mb-2 mt-3">{{ groupName }}</h5>
                <hr>
                <TaskList :tasks="groupTasks" @delete-request="confirmDelete"/>
            </div>
        </div>

        <TaskDeleteModal :show="showModal" :task="selectedTask" @cancel="showModal = false" @confirm="remove" />
    </div>
</template>

<script setup>
import TaskList from '@/components/tasks/TaskList.vue'
import { api } from '@/plugins/axios'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import TaskDeleteModal from '@/components/tasks/TaskDeleteModal.vue'

const tasks = ref([])
const categories = ref([])
const tab = ref('list')
const showModal = ref(false)
const selectedTask = ref(null)

const confirmDelete = (task) => {
  selectedTask.value = task
  showModal.value = true
}

const remove = async (task) => {
  await api.delete(`/tasks/${task.id}`)
  tasks.value = tasks.value.filter(t => t.id !== task.id)
  showModal.value = false
  window.dispatchEvent(new CustomEvent('refresh-counts'))
}

const tabs = [
    { key: 'list', label: 'List' },
    { key: 'duedate', label: 'Due Date' },
    { key: 'category', label: 'Category' },
    { key: 'priority', label: 'Priority' }
]

// 1. タスク取得
const fetchTasks = async () => {
    const res = await api.get('/tasks', { params: { status: 'in_progress' } })
    tasks.value = res.data.tasks
}

const fetchCategories = async () => {
    const res = await api.get('/categories')
    categories.value = res.data.categories
}

const tasksWithCategoryName = computed(() => {
    return tasks.value.map(t => {
    const cat = categories.value.find(c => c.id === t.category_id)
    return { ...t, category_name: cat ? cat.name : '未設定' }
    })
})

/* ===== 単純表示 (List / DueDate) ===== */
const displayedTasks = computed(() => {
  const ts = tasksWithCategoryName.value
    if (tab.value === 'duedate') {
    return [...ts].sort((a, b) =>
        new Date(a.due_date || '9999-12-31') - new Date(b.due_date || '9999-12-31')
    )
    }
    return [...ts].sort((a, b) => a.id - b.id)
})

/* ===== グループ表示 (Category / Priority) ===== */
const groupedTasks = computed(() => {
    const key = tab.value === 'category' ? 'category_name' : 'priority'
    const groups = {}
    tasksWithCategoryName.value.forEach(t => {
    const g = t[key] || '未設定'
    if (!groups[g]) groups[g] = []
    groups[g].push(t)
    })
    return groups
})

onMounted(async () => {
    await Promise.all([fetchTasks(), fetchCategories()])

    // ✅ TaskEditModal からの更新イベント受け取り
    window.addEventListener('refresh-counts', fetchTasks)
})

onBeforeUnmount(() => {
    window.removeEventListener('refresh-counts', fetchTasks)
})
</script>

<style scoped>
.nav-link.active {
    background-color: #ffd803 !important;
}

</style>