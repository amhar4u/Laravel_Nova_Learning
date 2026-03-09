<template>
  <div>
    <Head title="Test Tool" />

    <Heading class="mb-6">Test Tool Form</Heading>

    <Card class="p-6">
      <form @submit.prevent="handleSubmit">
        <div class="space-y-6">
          <!-- Name Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              class="w-full form-control form-input form-input-bordered"
              :class="{ 'border-red-500': errors.name }"
              placeholder="Enter your name"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-500">
              {{ errors.name }}
            </p>
          </div>

          <!-- Email Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              class="w-full form-control form-input form-input-bordered"
              :class="{ 'border-red-500': errors.email }"
              placeholder="Enter your email"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-500">
              {{ errors.email }}
            </p>
          </div>

          <!-- Phone Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Phone <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.phone"
              type="tel"
              class="w-full form-control form-input form-input-bordered"
              :class="{ 'border-red-500': errors.phone }"
              placeholder="Enter your phone number"
            />
            <p v-if="errors.phone" class="mt-1 text-sm text-red-500">
              {{ errors.phone }}
            </p>
          </div>

          <!-- Message Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Message <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.message"
              rows="4"
              class="w-full form-control form-input form-input-bordered"
              :class="{ 'border-red-500': errors.message }"
              placeholder="Enter your message"
            ></textarea>
            <p v-if="errors.message" class="mt-1 text-sm text-red-500">
              {{ errors.message }}
            </p>
          </div>

          <!-- Submit Button -->
          <div class="flex items-center justify-end space-x-3">
            <button
              type="button"
              @click="resetForm"
              class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600"
            >
              Reset
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600"
            >
              Submit
            </button>
          </div>
        </div>
      </form>
    </Card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        message: '',
      },
      errors: {},
    }
  },
  methods: {
    validateForm() {
      this.errors = {}

      // Name validation
      if (!this.form.name || this.form.name.trim() === '') {
        this.errors.name = 'Name is required'
      } else if (this.form.name.length < 3) {
        this.errors.name = 'Name must be at least 3 characters'
      }

      // Email validation
      if (!this.form.email || this.form.email.trim() === '') {
        this.errors.email = 'Email is required'
      } else if (!this.isValidEmail(this.form.email)) {
        this.errors.email = 'Please enter a valid email address'
      }

      // Phone validation
      if (!this.form.phone || this.form.phone.trim() === '') {
        this.errors.phone = 'Phone is required'
      } else if (!this.isValidPhone(this.form.phone)) {
        this.errors.phone = 'Please enter a valid phone number'
      }

      // Message validation
      if (!this.form.message || this.form.message.trim() === '') {
        this.errors.message = 'Message is required'
      } else if (this.form.message.length < 10) {
        this.errors.message = 'Message must be at least 10 characters'
      }

      return Object.keys(this.errors).length === 0
    },

    isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return emailRegex.test(email)
    },

    isValidPhone(phone) {
      const phoneRegex = /^[\d\s\-\+\(\)]+$/
      return phoneRegex.test(phone) && phone.replace(/\D/g, '').length >= 10
    },

    handleSubmit() {
      if (this.validateForm()) {
        // Send data to API
        Nova.request()
          .post('/nova-vendor/test-tool/submit', this.form)
          .then((response) => {
            if (response.data.success) {
              Nova.success(response.data.message || 'User created successfully!')
              this.resetForm()
            }
          })
          .catch((error) => {
            if (error.response && error.response.status === 422) {
              // Validation errors from backend
              const backendErrors = error.response.data.errors
              this.errors = {}
              
              Object.keys(backendErrors).forEach(key => {
                this.errors[key] = backendErrors[key][0]
              })
              
              Nova.error('Please fix the validation errors')
            } else if (error.response && error.response.data.message) {
              Nova.error(error.response.data.message)
            } else {
              Nova.error('Failed to submit form. Please try again.')
            }
          })
      } else {
        Nova.error('Please fix the validation errors')
      }
    },

    resetForm() {
      this.form = {
        name: '',
        email: '',
        phone: '',
        message: '',
      }
      this.errors = {}
    },
  },
  mounted() {
    //
  },
}
</script>

<style scoped>
/* Custom styles for form inputs */
.form-input-bordered {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
}

.dark .form-input-bordered {
  border-color: #4b5563;
  background-color: #374151;
  color: #f9fafb;
}

.form-input-bordered:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}
</style>
