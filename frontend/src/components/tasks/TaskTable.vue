<template>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Countdown</th>
            <th>Target Amount</th>
            <th>Priority</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(t, idx) in tasks" :key="t.id">
            <td>{{ idx + 1 }}</td>
            <td>{{ t.title }}</td>
            <td>{{ countdown(t.start_date, t.due_date) }}</td>
            <td>{{ formatCost(t.cost) }}</td>
            <td>{{ capitalize(t.priority) }}</td>
            <td>
                <button class="btn btn-sm btn-outline-danger" @click="$emit('delete', t)">Delete</button>
            </td>
        </tr>
    </tbody>
</table>
</template>

<script setup>
import { toRef } from 'vue';

const props = defineProps({
    tasks: { type: Array, default: () => [] }
});
const tasks = toRef(props, 'tasks');

const emit = defineEmits(['delete']);

const formatDate = (d) => d ? new Date(d).toLocaleDateString() : '';
const formatCost = (c) => c == null ? '-' : `$${Number(c).toFixed(2)}`;
const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : '';

const countdown = (start, end) => {
    const diff = new Date(end) - new Date(start);
    if (diff <= 0) return "Expired";

    const seconds = Math.floor(diff / 1000);
    const years = Math.floor(seconds / (3600 * 24 * 365));
    const days = Math.floor((seconds % (3600 * 24 * 365)) / (3600 * 24));
    const h = Math.floor((seconds % 86400) / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;

    return `${years}y : ${days}d : ${h}:${m}:${s}`;
};
</script>