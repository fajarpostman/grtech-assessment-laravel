<template>
  <Head title="Dashboard" />
  <a-layout class="min-h-screen">
    <a-layout-header class="bg-white shadow-md px-6 flex justify-between items-center">
      <div class="flex items-center space-x-8">
        <div class="text-xl font-bold text-blue-600 tracking-wide">GRTech-Test</div>
        <nav class="flex space-x-6">
          <button
            v-for="item in [
              { key: 'dashboard', label: 'Dashboard' },
              { key: 'companies', label: 'Companies' },
              { key: 'employees', label: 'Employees' }
            ]"
            :key="item.key"
            @click="onMenuClick(item.key)"
            class="relative text-gray-600 hover:text-blue-600 font-medium transition"
            :class="{ 'text-blue-600 font-semibold': selectedMenu.includes(item.key) }"
          >
            {{ item.label }}
            <span
              v-if="selectedMenu.includes(item.key)"
              class="absolute -bottom-2 left-0 w-full h-0.5 bg-blue-600 rounded"
            ></span>
          </button>
        </nav>
      </div>
      <div>
        <a-button type="primary" danger shape="round" @click="logout">
          Logout
        </a-button>
      </div>
    </a-layout-header>
    <a-layout-content class="p-8 bg-gray-50">
      <div class="max-w-3xl mx-auto">
        <a-card class="shadow-md rounded-xl">
          <h2 class="text-2xl font-bold mb-6 text-gray-700">User Profile</h2>
          <ul class="space-y-2 text-gray-700">
            <li><strong>Name:</strong> {{ user?.name || '-' }}</li>
            <li><strong>Email:</strong> {{ user?.email || '-' }}</li>
          </ul>
        </a-card>
      </div>
    </a-layout-content>
  </a-layout>
</template>
<script setup>
import { Head, usePage, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import axios from '@/axios'

function logout() {
  router.post('/logout', {}, {
    onFinish: () => {
      window.location.href = '/login'
    }
  })
}

const page = usePage()
const user = computed(() => page.props.auth?.user || {})

const selectedMenu = ['dashboard']

const onMenuClick = (key) => {
  if (key === 'dashboard') router.visit('/dashboard')
  if (key === 'companies') router.visit('/companies')
  if (key === 'employees') router.visit('/employees')
}
</script>
