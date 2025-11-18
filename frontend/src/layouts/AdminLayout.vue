<template>
    <div class="d-flex flex-column" style="min-height:100vh;">
        <Header />
        <div class="container flex-grow-1">
            <div class="row h-100">
                <div class="col-3" style="min-height: 100%;">
                    <AdminSidebar />
                </div>
                <div class="col-9">
                    <router-view />
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>

<script setup>
import Header from '@/components/layout/Header.vue';
import Footer from '@/components/layout/Footer.vue';
import AdminSidebar from '@/components/layout/AdminSidebar.vue';
</script>