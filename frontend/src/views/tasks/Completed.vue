<template>
    <div>
        <h2 class="h3">Completed Tasks</h2>

        <hr class="mb-4">

        <TaskList :tasks="tasks" @delete-request="confirmDelete" />

        <TaskDeleteModal :show="showModal" :task="selectedTask" @cancel="showModal = false" @confirm="remove" />
    </div>
</template>

<script setup>
import TaskList from '@/components/tasks/TaskList.vue'
import { api } from '@/plugins/axios'
import { ref, onMounted, onBeforeUnmount } from 'vue'
import TaskDeleteModal from '@/components/tasks/TaskDeleteModal.vue'

const tasks = ref([])
const showModal = ref(false)
const selectedTask = ref(null)

const confirmDelete = (task) => {
  selectedTask.value = task
  showModal.value = true
}

const fetch = async () => {
    const res = await api.get('/tasks', { params: { status: 'completed' } })
    tasks.value = res.data.tasks
}

const remove = async (task) => {
    await api.delete(`/tasks/${task.id}`)
    tasks.value = tasks.value.filter(t => t.id !== task.id)
    window.dispatchEvent(new CustomEvent('refresh-counts'))
}

onMounted(() => {
    fetch()
    window.addEventListener('refresh-counts', fetch)
})

onBeforeUnmount(() => {
    window.removeEventListener('refresh-counts', fetch)
})
</script>