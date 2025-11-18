<template>
    <div class="d-flex flex-column" style="min-height:100vh;">
        <Header />
        <div class="container flex-grow-1">
            <router-view />
        </div>
        <Footer />
    </div>
</template>

<script setup>
import Header from '@/components/layout/Header.vue'
import Footer from '@/components/layout/Footer.vue'
</script>