<template>
  <div class="modal-backdrop" v-if="show">
    <div class="modal-box">
      <h5 class="modal-title">Delete Task</h5>
      <p class="modal-text">Are you sure you want to delete this task?</p>
      <p class="task-title">{{ task?.title }}</p>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <button class="btn btn-danger" @click="$emit('confirm', task)">Delete</button>
        <button class="btn btn-light" @click="$emit('cancel')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: Boolean,
  task: Object
})

defineEmits(['cancel', 'confirm'])
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  display: flex;
  justify-content: center;
  align-items: center;
  backdrop-filter: blur(2px);
}

.modal-box {
  background: #fff;
  padding: 28px 30px;
  border-radius: 14px;
  width: 340px;
  box-shadow: 0 8px 18px rgba(0,0,0,0.12);
  animation: fadeIn .18s ease-out;
}

.modal-title {
  font-size: 17px;
  font-weight: 600;
  margin-bottom: 6px;
}

.modal-text {
  font-size: 14px;
  color: #555;
  margin-bottom: 6px;
}

.task-title {
  font-size: 14px;
  color: #777;
  font-style: italic;
  border-left: 3px solid #ddd;
  padding-left: 8px;
  margin-top: 4px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>