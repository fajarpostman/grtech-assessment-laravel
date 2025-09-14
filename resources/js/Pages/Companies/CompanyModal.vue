<template>
  <a-modal
    :visible="visible"
    :title="editing ? 'Edit Company' : 'New Company'"
    centered
    destroyOnClose
    okText="Save"
    cancelText="Cancel"
    @ok="submit"
    @cancel="close"
  >
    <a-form
      :model="form"
      layout="vertical"
      class="space-y-2"
    >
      <a-form-item
        label="Name"
        :rules="[{ required: true, message: 'Name is required' }]"
      >
        <a-input v-model:value="form.name" placeholder="Enter company name" />
      </a-form-item>
      <a-form-item
        label="Email"
        :rules="[{ required: true, type: 'email', message: 'Enter a valid email' }]"
      >
        <a-input v-model:value="form.email" placeholder="example@company.com" />
      </a-form-item>

      <a-form-item label="Logo">
        <input type="file" accept="image/*" @change="onFileChange" />
        <div v-if="preview" class="mt-3">
          <a-card
            hoverable
            class="w-28 shadow-sm border rounded-md overflow-hidden"
          >
            <img :src="preview" class="h-24 w-24 object-contain mx-auto" />
          </a-card>
        </div>
      </a-form-item>
      <a-form-item
        label="Website"
        :rules="[{ type: 'url', message: 'Enter a valid URL' }]"
      >
        <a-input v-model:value="form.website" placeholder="https://example.com" />
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
      form: { name: '', email: '', website: '' },
      file: null,
      preview: null,
      editingId: null,
    }
  },
  methods: {
    open(record = null) {
      this.visible = true
      if (record) {
        this.editing = true
        this.editingId = record.id
        this.form = { name: record.name, email: record.email, website: record.website }
        this.preview = record.logo_url
      } else {
        this.editing = false
        this.editingId = null
        this.form = { name: '', email: '', website: '' }
        this.preview = null
      }
    },
    close() {
      this.visible = false
      this.file = null
    },
    onFileChange(e) {
      const f = e.target.files[0]
      this.file = f
      this.preview = URL.createObjectURL(f)
    },
    submit() {
      const fd = new FormData()
      fd.append('name', this.form.name)
      fd.append('email', this.form.email || '')
      fd.append('website', this.form.website || '')
      if (this.file) fd.append('logo', this.file)

      const url = this.editing
        ? `/api/companies/${this.editingId}?_method=PUT`
        : '/api/companies'

      axios.post(url, fd)
        .then(() => {
          message.success(this.editing ? 'Company updated' : 'Company created')
          this.$emit('saved')
          this.close()
        })
        .catch(() => message.error('Something went wrong'))
    },
  },
}
</script>
