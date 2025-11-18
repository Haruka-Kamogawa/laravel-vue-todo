<template>
  <div class="container mt-5">
    <div class="row justify-content-center register-page">
      <div class="col-5">
        <div class="text-center my-2">
          <h2>Register</h2>
        </div>


        <hr>

        <form @submit.prevent="handleRegister">
          <div class="">
            <label for="username" class="form-label">Username</label>
            <input v-model="form.username" type="text" id="username" class="form-control" placeholder="" required />
          </div>
     

          <div class="mt-3">
            <label for="email" class="form-label">Email Address</label>
            <input v-model="form.email" type="email" id="email" class="form-control" placeholder="" required />
          </div>


          <div class="mt-3">
            <label for="birth" class="form-label">Birthdate</label>
            <input v-model="form.birth_date" type="date" id="birth" class="form-control" placeholder="" required />
          </div>


          <div class="mt-3">
            <label for="password" class="form-label">Password</label>
            <input v-model="form.password" type="password" id="password" class="form-control" placeholder="" required />
          </div>
       

          <div class="mt-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" id="confirm_password" class="form-control" placeholder="" required />
          </div>


          <div class="d-flex justify-content-center my-4">
            <button type="submit" class="btn button w-50">Register</button> 
          </div>
 
        </form>

        <div v-if="message" class="alert alert-danger">{{ message }}</div>

        <hr>

        <div class="text-center">
          <router-link to="/login" class="text-dark">Sign in</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import * as AuthAPI from '../../api/auth';

const router = useRouter();
const form = ref({
  username: '',
  email: '',
  birth_date: '',
  password: '',
  password_confirmation: '',
});
const message = ref(null);

const handleRegister = async () => {
  try {
    message.value = null;
    await AuthAPI.csrf();
    await AuthAPI.register(form.value);
    router.push({ name: 'Login' });
  } catch (e) {
    message.value = e.response?.data?.message || 'Register failed';
  }
}
</script>