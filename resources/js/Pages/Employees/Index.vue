<template>
  <a-layout class="min-h-screen">
    <a-layout-header class="bg-white shadow-md px-6 flex justify-between items-center">
      <div class="flex items-center space-x-8">
        <div class="text-xl font-bold text-blue-600 tracking-wide">
          GRTech-Test
        </div>
        <nav class="flex space-x-6">
          <button
            v-for="item in menuItems"
            :key="item.key"
            @click="onMenuClick(item.key)"
            class="relative text-gray-600 hover:text-blue-600 font-medium transition"
            :class="{
              'text-blue-600 font-semibold': selectedMenu.includes(item.key)
            }"
          >
            <span>{{ item.label }}</span>
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
    <a-layout-content class="p-6 bg-gray-50">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Employees</h1>
        <a-button 
          type="primary" 
          size="large" 
          class="rounded-lg shadow-md"
          @click="openCreate"
        >
          Add Employee
        </a-button>
      </div>
      <a-card class="shadow-md rounded-xl">
        <a-table
          bordered
          :columns="columns"
          :data-source="data"
          :pagination="pagination"
          rowKey="id"
          size="middle"
          @change="onTableChange"
        >
          <template #company="{ record }">
            <span class="font-medium text-gray-700">
              {{ record.company?.name || '-' }}
            </span>
          </template>
          <template #action="{ record }">
            <a-space>
              <a-button 
                type="default" 
                size="small" 
                class="rounded-md"
                @click="openEdit(record)"
              >
                Edit
              </a-button>
              <a-button 
                type="primary" 
                danger 
                size="small" 
                class="rounded-md"
                @click="confirmDelete(record)"
              >
                Delete
              </a-button>
            </a-space>
          </template>
        </a-table>
      </a-card>
      <EmployeeModal ref="modal" @saved="reload" />
    </a-layout-content>
  </a-layout>
</template>

<script>
import axios from '@/axios'
import EmployeeModal from './EmployeeModal.vue'
import { message, Modal } from 'ant-design-vue'
import { router } from '@inertiajs/vue3'

export default {
  components: { EmployeeModal },
  data() {
    return {
      data: [],
      pagination: { current: 1, pageSize: 10, total: 0 },
      selectedMenu: ['employees'],
      menuItems: [
        { key: 'dashboard', label: 'Dashboard' },
        { key: 'companies', label: 'Companies' },
        { key: 'employees', label: 'Employees' }
      ],
      columns: [
        { 
          title: 'No',
          key: 'index',
          width: 60,
          align: 'center',
          customRender: ({ index }) => {
            return (this.pagination.current - 1) * this.pagination.pageSize + index + 1
          }
        },
        { title: 'First Name', dataIndex: 'first_name', key: 'first_name' },
        { title: 'Last Name', dataIndex: 'last_name', key: 'last_name' },
        { title: 'Email', dataIndex: 'email', key: 'email' },
        { title: 'Phone', dataIndex: 'phone', key: 'phone' },
        { title: 'Company', dataIndex: 'company', key: 'company', slots: { customRender: 'company' } },
        { title: 'Action', key: 'action', align: 'center', slots: { customRender: 'action' } },
      ]
    }
  },
  mounted() {
    this.load()
  },
  methods: {
    load(params = {}) {
      const { current = this.pagination.current, pageSize = this.pagination.pageSize } = params
      axios.get('/api/employees', { params: { page: current, per_page: pageSize } })
        .then(res => {
          const payload = res.data
          this.data = payload.data || []
          this.pagination.total = payload.meta ? payload.meta.total : (payload.length || 0)
        })
    },
    onTableChange(pagination) {
      this.pagination.current = pagination.current
      this.pagination.pageSize = pagination.pageSize
      this.load({ current: pagination.current, pageSize: pagination.pageSize })
    },
    openCreate() { this.$refs.modal.open() },
    openEdit(record) { this.$refs.modal.open(record) },
    confirmDelete(record) {
      Modal.confirm({
        title: 'Are you sure?',
        content: `Delete employee "${record.first_name} ${record.last_name}"?`,
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk: () => {
          axios.delete(`/api/employees/${record.id}`).then(() => {
            message.success('Employee deleted')
            this.load()
          })
        }
      })
    },
    reload() { this.load() },
    onMenuClick(key) {
      this.selectedMenu = [key]
      if (key === 'dashboard') router.visit('/dashboard')
      if (key === 'companies') router.visit('/companies')
      if (key === 'employees') router.visit('/employees')
    },
    logout() {
      axios.post('/logout').then(() => {
        router.visit('/login')
      })
    }
  }
}
</script>
