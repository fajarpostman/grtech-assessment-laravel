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
                <h1 class="text-2xl font-bold text-gray-700">Company List</h1>
                <a-button 
                    type="primary" 
                    size="large" 
                    class="rounded-lg shadow-md"
                    @click="openCreate"
                >
                    Add Company
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
                    <template #logo="{ record }">
                        <div class="flex justify-center">
                            <img 
                                v-if="record.logo_url" 
                                :src="record.logo_url" 
                                class="h-12 w-12 object-contain rounded-lg border"
                            />
                            <span v-else class="text-gray-400 italic">No Logo</span>
                        </div>
                    </template>
                    <template #website="{ record }">
                        <a 
                            v-if="record.website" 
                            :href="record.website" 
                            target="_blank"
                            class="text-blue-600 hover:underline"
                        >
                            {{ record.website }}
                        </a>
                        <span v-else class="text-gray-400 italic">N/A</span>
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
            <CompanyModal ref="modal" @saved="reload" />
        </a-layout-content>
    </a-layout>
  <div class="p-6 bg-gray-50 min-h-screen">

  </div>
</template>

<script>
import axios from '@/axios'
import CompanyModal from './CompanyModal.vue'
import { message, Modal } from 'ant-design-vue'
import { router } from '@inertiajs/vue3'

export default {
  components: { CompanyModal },
  data() {
    return {
      data: [],
      pagination: { current: 1, pageSize: 10, total: 0 },
      selectedMenu: ['companies'],
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
        { title: 'Name', dataIndex: 'name', key: 'name' },
        { title: 'Email', dataIndex: 'email', key: 'email' },
        { title: 'Logo', dataIndex: 'logo_url', key: 'logo', align: 'center', slots: { customRender: 'logo' } },
        { title: 'Website', dataIndex: 'website', key: 'website', slots: { customRender: 'website' } },
        { title: 'Action', key: 'action', align: 'center', slots: { customRender: 'action' } },
      ]
    }
  },
  mounted() {
    this.load()
  },
  methods: {
        logout() {
        router.post('/logout', {}, {
            onFinish: () => {
            window.location.href = '/login'
            }
        })
    },
    load(params = {}) {
      const { current = this.pagination.current, pageSize = this.pagination.pageSize } = params;
      axios.get('/api/companies', { params: { page: current, per_page: pageSize}})
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
        content: `Delete company "${record.name}"?`,
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk: () => {
          axios.delete(`/api/companies/${record.id}`).then(() => {
            message.success('Company deleted')
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
    }
  }
}
</script>
