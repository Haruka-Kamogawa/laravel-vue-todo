<template>
  <div class="d-flex flex-column" style="min-height:100vh;">
    <Header />
    <div class="container pb-3 mt-5" v-if="currentHead()">
      <component :is="currentHead()" />
    </div>

    <div class="container flex-grow-1">
      <div class="row h-100">
        <div class="col-3" style="min-height: 100%;">
            <Sidebar />
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
import Header from '@/components/layout/Header.vue'
import Sidebar from '@/components/layout/Sidebar.vue'
import Footer from '@/components/layout/Footer.vue'
import { useRoute } from 'vue-router'
import TimerHead from '../components/head/TimerHead.vue'
import CompletedHead from '../components/head/CompletedHead.vue'

const route = useRoute()

const currentHead = () => {
  if (route.name === 'Progress') 
  return TimerHead

  if (route.name === 'Pending') 
  return TimerHead

  if (route.name === 'Completed') return CompletedHead
  return null
}
</script>