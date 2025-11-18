<template>
    <div class="task-list text-center">
        <!-- ヘッダ -->
        <div class="task-header row fw-bold text-muted mb-2">
            <div class="col-1">No.</div>
            <div class="col-3">Title</div>
            <div class="col-4">Countdown</div>
            <div class="col-2">Target Amount</div>
            <div class="col-1">Priority</div>
            <div class="col-1"></div>
        </div>

        <!-- カードリスト -->
        <div v-for="(task, i) in tasks" :key="task.id" class="task-card w-100 shadow-sm" @click="open(task)">
            <div class="row align-items-center text-center list">
                <div class="col-1 fw-semibold">
                    {{ i + 1 }}
                </div>

                <div class="col-3">
                    {{ task.title }}
                </div>

                <!-- カウントダウン + ProgressBar -->
                <div class="col-4 timer fw-semibold">
                  <div>{{ countdownTexts[task.id] || '-' }}</div>

                  <div class="progress mt-1" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" :class="progressBarClasses[task.id]" :style="{ width: (progressValues[task.id] || 0) + '%' }" :aria-valuenow="progressValues[task.id] || 0" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col-2">
                  {{ formatCost(task.cost) }}
                </div>

                <div class="col-1">
                  <span class="badge" :class="priorityColor(task.priority)">
                      {{ task.priority }}
                  </span>
                </div>

                <div class="col-1">
                  <button class="btn p-0 border-0 bg-transparent" @click.stop="deleteTask(task)">
                    <i class="fa-solid fa-trash text-danger"></i>
                  </button>
                </div>
            </div>
        </div>

        <div v-if="!tasks.length" class="text-center text-muted py-4">
          タスクがありません
        </div>
    </div>

    <!-- 編集モーダル -->
    <TaskEditModal v-if="selectedTask" :task="selectedTask" @close="selectedTask = null" />
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import TaskEditModal from './TaskEditModal.vue'

const props = defineProps({ tasks: Array })

const selectedTask = ref(null)
const open = (task) => { selectedTask.value = task }

/* Delete */
const emit = defineEmits(['delete-request'])

const deleteTask = (task) => {
  emit('delete-request', task)
}

/* ===== 進行状態 ===== */
const countdownTexts = ref({})
const progressValues = ref({})
const progressBarClasses = ref({})
let timers = []

/* ===== Utility ===== */
const pad = (n, d = 2) => String(n).padStart(d, '0')
const formatCost = (c) => c == null ? '-' : `$${Number(c).toLocaleString()}`

const priorityColor = p => ({
    high: 'bg-danger',
    medium: 'bg-warning text-dark',
    low: 'bg-primary'
}[p])

/* ===== Countdown + Progress ===== */
function startCountdown(task) {
  if (!task.start_date || !task.due_date) return

  const id = task.id
  const start = new Date(task.start_date).getTime()
  const end = new Date(task.due_date).getTime()
  const totalSeconds = Math.floor((end - start) / 1000)

  function update() {
    const now = Date.now()
    let diff = Math.floor((end - now) / 1000)

    // 未開始
    if (now < start) {
      countdownTexts.value[id] = 'Not Started'
      progressValues.value[id] = 0
      progressBarClasses.value[id] = 'bg-secondary'
      return
    }

    // 終了済み
    if (diff <= 0) {
      countdownTexts.value[id] = '00 years : 000 days : 00:00:00'
      progressValues.value[id] = 100
      progressBarClasses.value[id] = 'bg-danger'
      return
    }

    const years = Math.floor(diff / (365 * 24 * 60 * 60))
    diff -= years * 365 * 24 * 60 * 60

    const days = Math.floor(diff / (24 * 60 * 60))
    diff -= days * 24 * 60 * 60

    const hours = Math.floor(diff / 3600)
    diff -= hours * 3600

    const minutes = Math.floor(diff / 60)
    const seconds = diff % 60

    countdownTexts.value[id] = `${pad(years,2)} years : ${pad(days,3)} days : ${pad(hours)}:${pad(minutes)}:${pad(seconds)}`

    // 経過割合
    const elapsed = Math.floor((now - start) / 1000)
    const percent = Math.min(Math.floor((elapsed / totalSeconds) * 100), 100)
    progressValues.value[id] = percent

    // 色制御
    if (percent >= 100) {
      progressBarClasses.value[id] = 'bg-danger'
    } else {
      progressBarClasses.value[id] = 'bg-primary'
    }
  }

update()
  timers.push(setInterval(update, 1000))
}

onMounted(() => {
  props.tasks.forEach(t => startCountdown(t))
})

watch(() => props.tasks, (ts) => {
  ts.forEach(t => {
    if (!countdownTexts.value[t.id]) startCountdown(t)
  })
}, { deep: true })

onBeforeUnmount(() => timers.forEach(clearInterval))
</script>

<style scoped>
.task-header { padding: 0 8px; }

.task-card {
  border: 1px solid #e1e4e8;
  border-radius: 10px;
  padding: 10px 8px;
  margin-bottom: 10px;
  background: #fff;
  cursor: pointer;
  transition: 0.2s;
  display: block;
}

.task-card:hover {
  box-shadow: 0 4px 10px rgba(0,0,0,0.12);
  transform: translateY(-2px);
}

.list { height: 40px; }
</style>