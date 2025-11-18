<template>
    <div class="container text-center mb-4 timerhead">
        <h1 class="h2 mb-2">Life Countdown</h1>
        <div class="card mx-auto p-4 rounded-lg timer-box">
            <div class="timer mb-1 fw-semibold">{{ countdownText }}</div>
            <div class="small fw-semibold">{{ totalSecondsText }}</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

// ---------- Countdown logic (Vue 化) ----------
// import { padStart as _padStart } from 'lodash-es'; // optional; we can implement pad ourselves
// Simple pad function to avoid extra deps:
const pad = (n, d = 2) => String(n).padStart(d, '0');
const comma = (n) => n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

// Set birth and lifespan here or get from user/profile (hard-coded for example)
const lifespanYears = 80;

const countdownText = ref('');
const totalSecondsText = ref('');
let timerId = null;


function startCountdown(birthDate) {
    if (!birthDate) return

    const birth = new Date(birthDate)
    const target = new Date(birth)
    target.setFullYear(target.getFullYear() + lifespanYears)

    function updateCountdown() {
        const now = Date.now();
        let diff = target.getTime() - now;
        if (diff <= 0) {
            countdownText.value = '00 years : 000 days : 00:00:00';
            totalSecondsText.value = '0 seconds remaining';
            clearInterval(timerId);
            return;
        }
        const totalSeconds = Math.floor(diff / 1000);
        const secondsPerYear = 365.2425 * 24 * 60 * 60;
        const years = Math.floor(totalSeconds / secondsPerYear);
        let rem = totalSeconds - Math.floor(years * secondsPerYear);
        const days = Math.floor(rem / (24 * 60 * 60));
        rem -= days * 24 * 60 * 60;
        const hours = Math.floor(rem / 3600);
        rem -= hours * 3600;
        const minutes = Math.floor(rem / 60);
        const seconds = rem - minutes * 60;

        countdownText.value = `${pad(years,2)} years : ${pad(days,3)} days : ${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
        totalSecondsText.value = `${comma(totalSeconds)} seconds remaining`;
    }

    if (timerId) clearInterval(timerId)
    updateCountdown()
    timerId = setInterval(updateCountdown, 1000)
}

onMounted(async () => {
    if (!authStore.user) {
        await authStore.fetchUser()
    }
    startCountdown(authStore.user?.birth_date)
})

watch(() => authStore.user?.birth_date, (newVal) => {
    if (newVal) startCountdown(newVal)
})

onBeforeUnmount(() => {
    if (timerId) clearInterval(timerId)
})

</script>

<style scoped>
.timerhead {
    height: 250px;
}

</style>