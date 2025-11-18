<template>
  <div class="container mt-5">
    <div class="row justify-content-center login-page">
      <div class="col-5">
        <div class="text-center my-2">
          <h2 class="my-2">Login</h2>
        </div>


        <hr class="">

        <form @submit.prevent="login" class="pt-3">
          <div class="">
            <label for="email" class="form-label">E-mail Address</label>
            <input v-model="form.email" type="email" id="email" class="form-control" placeholder="" required />
          </div>

          <div class="">
            <label for="password" class="form-label mt-3">Password</label>
            <input v-model="form.password" type="password" id="password" class="form-control" placeholder="" required />
          </div>

          <div class="d-flex justify-content-center my-4">
            <button type="submit" class="btn button w-50">Login</button>
          </div>

        </form>

        <div v-if="message" class="alert alert-danger">{{ message }}</div>

        <hr>

        <div class="login_btn text-center my-3">
          <button @click="loginGoogle" class="mx-2">
            <img src="/icons/google_btn.svg" />
            <br>
            <span>Google</span>
          </button>

          <button  class="mx-2">
            <img src="/icons/github-btn.svg" alt="">
            <br>
            <span>GitHub</span>
          </button>
        </div>

        <div class="text-center">
          <router-link to="/register" class="text-dark">Sign up</router-link>
        </div>


      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const loginGoogle = () => {
  window.location.href = `${import.meta.env.VITE_API_BASE_URL}/auth/google/redirect`;
};

const auth = useAuthStore();
const router = useRouter();

const form = ref({ email: '', password: ''});
const message = ref(null);

const login = async () => {
  try {
    message.value = null;
    await auth.login(form.value);
    await auth.fetchUser();
    router.push({ name: 'Progress' });
  } catch (e) {
    message.value = e.response?.data?.message || 'Login failed';
  }
}
</script>

<style scoped>
.login_btn button {
  background: none;
  border: none;
}

.login_btn button img {
  width: 42px;
  height: 42px;
}
</style>