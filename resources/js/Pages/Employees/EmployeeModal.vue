<template>
  <a-modal
    :visible="visible"
    :title="editing ? 'Edit Employee' : 'New Employee'"
    centered
    destroyOnClose
    okText="Save"
    cancelText="Cancel"
    @ok="submit"
    @cancel="close"
  >
    <a-form :model="form" layout="vertical" class="space-y-2">
      <a-form-item label="First Name" :rules="[{ required: true, message: 'First name is required' }]">
        <a-input v-model:value="form.first_name" placeholder="Enter first name" />
      </a-form-item>
      <a-form-item label="Last Name" :rules="[{ required: true, message: 'Last name is required' }]">
        <a-input v-model:value="form.last_name" placeholder="Enter last name" />
      </a-form-item>
      <a-form-item label="Email" :rules="[{ type: 'email', message: 'Enter valid email' }]">
        <a-input v-model:value="form.email" placeholder="example@mail.com" />
      </a-form-item>
      <a-form-item label="Phone">
        <a-input v-model:value="form.phone" placeholder="+62..." />
      </a-form-item>
      <a-form-item label="Company" :rules="[{ required: true, message: 'Please select a company' }]">
        <a-select v-model:value="form.company_id" placeholder="Select company">
          <a-select-option v-for="c in companies" :key="c.id" :value="c.id">
            {{ c.name }}
          </a-select-option>
        </a-select>
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script>
import axios from '@/axios'
import { message } from 'ant-design-vue'

export default {
  data() {
    return {
      visible: false,
      editing: false,
      form: { first_name: '', last_name: '', email: '', phone: '', company_id: null },
      editingId: null,
      companies: []
    }
  },
  methods: {
    async open(record = null) {
      this.visible = true
      await this.fetchCompanies()

      if (record) {
        this.editing = true
        this.editingId = record.id
        this.form = {
          first_name: record.first_name,
          last_name: record.last_name,
          email: record.email,
          phone: record.phone,
          company_id: record.company_id
        }
      } else {
        this.editing = false
        this.editingId = null
        this.form = { first_name: '', last_name: '', email: '', phone: '', company_id: null }
      }
    },
    close() {
      this.visible = false
    },
    async fetchCompanies() {
      const res = await axios.get('/api/companies?per_page=100')
      this.companies = res.data.data || []
    },
    submit() {
      const url = this.editing
        ? `/api/employees/${this.editingId}?_method=PUT`
        : '/api/employees'

      axios.post(url, this.form)
        .then(() => {
          message.success(this.editing ? 'Employee updated' : 'Employee created')
          this.$emit('saved')
          this.close()
        })
        .catch(() => message.error('Something went wrong'))
    }
  }
}
</script>
