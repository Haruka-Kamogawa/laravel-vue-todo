import { defineStore } from 'pinia';
import * as AuthAPI from '../api/auth';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
  }),
  actions: {
    async register(data) {
      await AuthAPI.register(data);
      await this.fetchUser();
    },
    async login(data) {
      await AuthAPI.login(data);
      await this.fetchUser(); 
    },
    async logout() {
      await AuthAPI.logout();
      this.user = null;
    },
    async fetchUser() {
      try {
        const res = await AuthAPI.getUser()
        this.user = res.data
      } catch (err) {
        this.user = null
      }
    }
  }
});