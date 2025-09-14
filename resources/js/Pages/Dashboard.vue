<template>
  <Head title="Dashboard" />
  <a-layout class="min-h-screen">
    <a-layout-header class="bg-white shadow-md px-6 flex justify-between items-center">
      <div class="flex items-center space-x-8">
        <div class="text-xl font-bold text-blue-600 tracking-wide">MyApp</div>
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
          <h2 class="text-2xl font-bold mb-6 text-gray-700">Profile</h2>
          <a-form layout="vertical" class="space-y-4">
            <a-form-item label="Name">
              <a-input v-model:value="user.name" placeholder="Enter your name" />
            </a-form-item>
            <a-form-item label="Email">
              <a-input v-model:value="user.email" disabled />
            </a-form-item>
            <div class="flex justify-end">
              <a-button type="primary" @click="saveProfile">
                Save Changes
              </a-button>
            </div>
          </a-form>
        </a-card>
      </div>
    </a-layout-content>
  </a-layout>
</template>
<script setup>
import { Head, usePage, router } from '@inertiajs/vue3'
import axios from '@/axios'
import { reactive } from 'vue'
import { message } from 'ant-design-vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { computed } from 'vue'

const { props } = usePage()
const user = reactive({
  name: props.auth.user.name,
  email: props.auth.user.email,
})

const saveProfile = () => {
  axios.put('/user/profile', user)
    .then(() => message.success('Profile updated successfully'))
    .catch(() => message.error('Something went wrong'))
}

const logout = () => {
  axios.post('/logout').then(() => {
    router.visit('/login')
  })
}

const selectedMenu = ['dashboard']
const onMenuClick = (key) => {
  if (key === 'dashboard') router.visit('/dashboard')
  if (key === 'companies') router.visit('/companies')
  if (key === 'employees') router.visit('/employees')
}
</script>