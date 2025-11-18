<template>
  <div class="container mt-5">
    <div class="row justify-content-center editprofile-page">
      <div class="card shadow" style="width: 100%; max-width: 600px;">
        <div class="text-center my-2">
          <h2 class="h3 my-2">
            <i class="fa-solid fa-user fs-4 me-2"></i>
            Profile Setting
          </h2>
        </div>

        <hr class="my-0 mb-2">

        <div class="card-body">
          <form @submit.prevent="saveProfile">

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input v-model="profile.username" type="text" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input v-model="profile.email" type="email" class="form-control" required>
            </div>

            <div class="mb-4">
              <label class="form-label">Birth Date</label>
              <input v-model="profile.birth_date" type="date" class="form-control" required>
            </div>

            <div class="d-flex justify-content-center py-2">
              <button type="submit" class="btn button me-5 w-25">Save</button>
              <button @click="cancel" type="button" class="btn btn-secondary w-25">Cancel</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getProfile, updateProfile } from '@/api/profile';
import { csrfApi } from '@/plugins/axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const profile = ref({
  username: '',
  email: '',
  birth_date: ''
});

async function loadProfile() {
  const res = await getProfile();
  profile.value = res.data.profile;
}

async function getCsrf() {
  await csrfApi.get('/sanctum/csrf-cookie');
}

async function saveProfile() {
  await getCsrf();
  await updateProfile(profile.value);
  router.push({ name: 'Settings' });
}

function cancel() {
  router.push({ name: 'Settings' });
}

onMounted(loadProfile);
</script>