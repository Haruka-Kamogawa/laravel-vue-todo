<template>
  <div class="container mt-5">
    <div class="row justify-content-center editprofile-page">
      <div class="card shadow" style="width: 100%; max-width: 600px;">
        <div class="text-center my-2">
          <h2 class="h3 my-2">
              <i class="fa-solid fa-key fs-4 me-2"></i>
              Change Password
          </h2>
        </div>

        <hr class="my-0 mb-2">

        <div class="card-body">
          <form @submit.prevent="change">

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input v-model="form.password" type="password" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" class="form-control" required>
            </div>

            <div class="d-flex justify-content-center py-2">
                <button type="submit" class="btn button me-5 w-25">Change</button>
                <button @click="cancel" type="button" class="btn btn-secondary w-25">Cancel</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { changePassword } from '@/api/profile'
import { csrfApi } from '@/plugins/axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  password: '',
  password_confirmation: ''
})

async function getCsrf() {
  await csrfApi.get('/sanctum/csrf-cookie')
}

async function change() {
  await getCsrf()
  await changePassword(form.value)
  router.push({ name: 'Settings' })
}

function cancel() {
  router.push({ name: 'Settings' })
}
</script>