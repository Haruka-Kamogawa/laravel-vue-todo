<template>
  <div v-if="show" class="modal-backdrop">
    <div class="modal-box">
      <h4>Delete User</h4>
      <hr>
      <p>{{ message }}</p>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <button class="btn btn-secondary" @click="$emit('cancel')">Cancel</button>
        <button class="btn btn-danger" @click="$emit('confirm')">Delete</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: Boolean,
  message: String
});
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-box {
  background: white;
  padding: 20px;
  border-radius: 10px;
  width: 500px;
}
</style>