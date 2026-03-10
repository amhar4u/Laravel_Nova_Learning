<template>
  <div>
    <Head title="Test Tool" />

    <!-- Hero Header Banner -->
    <div class="tool-hero mb-8 rounded-2xl overflow-hidden relative">
      <div class="tool-hero-bg"></div>
      <div class="relative z-10 flex items-center justify-between px-8 py-6">
        <div class="flex items-center space-x-4">
          <div class="tool-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 text-white">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l5.653-4.655m5.8-2.672l1.766-1.766a4.125 4.125 0 00-5.838-5.838L7.8 5.966m5.8 2.672l-5.8-2.672" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Test Tool</h1>
            <p class="text-indigo-200 text-sm mt-0.5">Send a message or inquiry to the admin panel</p>
          </div>
        </div>
        <div class="hidden md:flex items-center space-x-2 text-indigo-200 text-sm">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
          </svg>
          <span>Secure Submission</span>
        </div>
      </div>
    </div>

    <!-- Steps Indicator -->
    <div class="flex items-center justify-center mb-8 space-x-3">
      <div class="step-item" :class="currentStep >= 1 ? 'step-active' : 'step-inactive'">
        <span class="step-number">1</span>
        <span class="step-label">Personal Info</span>
      </div>
      <div class="step-connector" :class="currentStep >= 2 ? 'connector-active' : 'connector-inactive'"></div>
      <div class="step-item" :class="currentStep >= 2 ? 'step-active' : 'step-inactive'">
        <span class="step-number">2</span>
        <span class="step-label">Message</span>
      </div>
      <div class="step-connector" :class="submitted ? 'connector-active' : 'connector-inactive'"></div>
      <div class="step-item" :class="submitted ? 'step-active' : 'step-inactive'">
        <span class="step-number">3</span>
        <span class="step-label">Done</span>
      </div>
    </div>

    <!-- Success State -->
    <div v-if="submitted" class="success-card text-center py-12 px-6">
      <div class="success-icon-wrapper mx-auto mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-white">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
      </div>
      <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">Submitted Successfully!</h3>
      <p class="text-gray-500 dark:text-gray-400 mb-6">Your message has been sent. We'll get back to you shortly.</p>
      <button @click="startNew" class="btn-primary-gradient">
        Submit Another
      </button>
    </div>

    <!-- Form Card -->
    <Card v-else class="overflow-hidden shadow-lg border-0">
      <!-- Card Header -->
      <div class="form-card-header px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-indigo-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
          </svg>
          <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">
            {{ currentStep === 1 ? 'Step 1 — Personal Information' : 'Step 2 — Your Message' }}
          </span>
        </div>
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ currentStep }}/2 steps</span>
      </div>

      <form @submit.prevent="handleSubmit" class="p-6">

        <!-- Step 1: Personal Info -->
        <div v-if="currentStep === 1" class="space-y-5">
          <!-- Name Field -->
          <div class="field-group">
            <label class="field-label">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
              </svg>
              Full Name <span class="text-red-400">*</span>
            </label>
            <div class="field-input-wrapper" :class="{ 'field-error': errors.name }">
              <input
                v-model="form.name"
                type="text"
                class="field-input"
                placeholder="e.g. John Doe"
                @input="clearError('name')"
              />
            </div>
            <p v-if="errors.name" class="field-error-msg">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              {{ errors.name }}
            </p>
          </div>

          <!-- Email Field -->
          <div class="field-group">
            <label class="field-label">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
              </svg>
              Email Address <span class="text-red-400">*</span>
            </label>
            <div class="field-input-wrapper" :class="{ 'field-error': errors.email }">
              <input
                v-model="form.email"
                type="email"
                class="field-input"
                placeholder="e.g. john@example.com"
                @input="clearError('email')"
              />
            </div>
            <p v-if="errors.email" class="field-error-msg">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              {{ errors.email }}
            </p>
          </div>

          <!-- Phone Field -->
          <div class="field-group">
            <label class="field-label">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
              </svg>
              Phone Number <span class="text-red-400">*</span>
            </label>
            <div class="field-input-wrapper" :class="{ 'field-error': errors.phone }">
              <input
                v-model="form.phone"
                type="tel"
                class="field-input"
                placeholder="e.g. +1 (555) 000-0000"
                @input="clearError('phone')"
              />
            </div>
            <p v-if="errors.phone" class="field-error-msg">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              {{ errors.phone }}
            </p>
          </div>

          <!-- Next Button -->
          <div class="flex justify-end pt-2">
            <button type="button" @click="goToStep2" class="btn-primary-gradient">
              Continue
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 ml-1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Step 2: Message -->
        <div v-if="currentStep === 2" class="space-y-5">
          <!-- Summary Card -->
          <div class="summary-card">
            <div class="flex items-center space-x-3">
              <div class="summary-avatar">{{ form.name.charAt(0).toUpperCase() }}</div>
              <div>
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ form.name }}</p>
                <p class="text-xs text-gray-400">{{ form.email }} &bull; {{ form.phone }}</p>
              </div>
              <button type="button" @click="currentStep = 1" class="ml-auto text-xs text-indigo-500 hover:text-indigo-700 flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                </svg>
                <span>Edit</span>
              </button>
            </div>
          </div>

          <!-- Message Field -->
          <div class="field-group">
            <label class="field-label">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
              </svg>
              Your Message <span class="text-red-400">*</span>
            </label>
            <div class="field-input-wrapper" :class="{ 'field-error': errors.message }">
              <textarea
                v-model="form.message"
                rows="5"
                class="field-input resize-none"
                placeholder="Write your message here..."
                @input="clearError('message')"
              ></textarea>
            </div>
            <div class="flex items-center justify-between mt-1">
              <p v-if="errors.message" class="field-error-msg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                {{ errors.message }}
              </p>
              <span v-else class="text-xs text-gray-400">{{ form.message.length }} / 500 chars</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-between pt-2">
            <button
              type="button"
              @click="currentStep = 1"
              class="btn-ghost"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 mr-1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
              </svg>
              Back
            </button>
            <div class="flex items-center space-x-3">
              <button
                type="button"
                @click="resetForm"
                class="btn-secondary"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
              </button>
              <button
                type="submit"
                :disabled="isSubmitting"
                class="btn-primary-gradient"
              >
                <svg v-if="isSubmitting" class="animate-spin w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
                {{ isSubmitting ? 'Sending...' : 'Send Message' }}
              </button>
            </div>
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
      currentStep: 1,
      submitted: false,
      isSubmitting: false,
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
    clearError(field) {
      if (this.errors[field]) {
        delete this.errors[field]
        this.errors = { ...this.errors }
      }
    },

    validateStep1() {
      this.errors = {}

      if (!this.form.name || this.form.name.trim() === '') {
        this.errors.name = 'Name is required'
      } else if (this.form.name.length < 3) {
        this.errors.name = 'Name must be at least 3 characters'
      }

      if (!this.form.email || this.form.email.trim() === '') {
        this.errors.email = 'Email is required'
      } else if (!this.isValidEmail(this.form.email)) {
        this.errors.email = 'Please enter a valid email address'
      }

      if (!this.form.phone || this.form.phone.trim() === '') {
        this.errors.phone = 'Phone is required'
      } else if (!this.isValidPhone(this.form.phone)) {
        this.errors.phone = 'Please enter a valid phone number'
      }

      return Object.keys(this.errors).length === 0
    },

    validateStep2() {
      this.errors = {}

      if (!this.form.message || this.form.message.trim() === '') {
        this.errors.message = 'Message is required'
      } else if (this.form.message.length < 10) {
        this.errors.message = 'Message must be at least 10 characters'
      }

      return Object.keys(this.errors).length === 0
    },

    goToStep2() {
      if (this.validateStep1()) {
        this.currentStep = 2
      }
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
      if (!this.validateStep2()) {
        return
      }

      this.isSubmitting = true

      Nova.request()
        .post('/nova-vendor/test-tool/submit', this.form)
        .then((response) => {
          if (response.data.success) {
            this.submitted = true
            Nova.success(response.data.message || 'Message sent successfully!')
          }
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            const backendErrors = error.response.data.errors
            this.errors = {}
            Object.keys(backendErrors).forEach(key => {
              this.errors[key] = backendErrors[key][0]
            })
            Nova.error('Please fix the validation errors')
          } else if (error.response && error.response.data.message) {
            Nova.error(error.response.data.message)
          } else {
            Nova.error('Failed to send message. Please try again.')
          }
        })
        .finally(() => {
          this.isSubmitting = false
        })
    },

    resetForm() {
      this.form = { name: '', email: '', phone: '', message: '' }
      this.errors = {}
    },

    startNew() {
      this.submitted = false
      this.currentStep = 1
      this.resetForm()
    },
  },
}
</script>

<style scoped>
/* ─── Hero Banner ─────────────────────────────────── */
.tool-hero {
  position: relative;
}
.tool-hero-bg {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a21caf 100%);
  border-radius: inherit;
}
.tool-icon-wrapper {
  width: 48px;
  height: 48px;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 12px;
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ─── Steps ───────────────────────────────────────── */
.step-item {
  display: flex;
  align-items: center;
  gap: 8px;
}
.step-number {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 700;
  transition: all 0.3s;
}
.step-label {
  font-size: 13px;
  font-weight: 500;
  transition: color 0.3s;
}
.step-active .step-number {
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  color: #fff;
  box-shadow: 0 4px 12px rgba(79,70,229,0.4);
}
.step-active .step-label {
  color: #4f46e5;
}
.step-inactive .step-number {
  background: #e5e7eb;
  color: #9ca3af;
}
.step-inactive .step-label {
  color: #9ca3af;
}
.dark .step-inactive .step-number {
  background: #374151;
  color: #6b7280;
}
.dark .step-inactive .step-label {
  color: #6b7280;
}
.step-connector {
  width: 48px;
  height: 2px;
  border-radius: 1px;
  transition: background 0.3s;
}
.connector-active  { background: linear-gradient(90deg, #4f46e5, #7c3aed); }
.connector-inactive { background: #e5e7eb; }
.dark .connector-inactive { background: #374151; }

/* ─── Form Card Header ────────────────────────────── */
.form-card-header {
  border-bottom: 1px solid #f3f4f6;
  background: #fafafa;
}
.dark .form-card-header {
  border-bottom-color: #374151;
  background: #1f2937;
}

/* ─── Field Groups ────────────────────────────────── */
.field-group { display: flex; flex-direction: column; gap: 6px; }

.field-label {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}
.dark .field-label { color: #d1d5db; }

.field-input-wrapper {
  border-radius: 10px;
  border: 1.5px solid #e5e7eb;
  background: #fff;
  transition: border-color 0.2s, box-shadow 0.2s;
  overflow: hidden;
}
.dark .field-input-wrapper {
  border-color: #4b5563;
  background: #1f2937;
}
.field-input-wrapper:focus-within {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.field-input-wrapper.field-error {
  border-color: #f87171;
  box-shadow: 0 0 0 3px rgba(248,113,113,0.1);
}
.field-input {
  width: 100%;
  padding: 10px 14px;
  font-size: 14px;
  background: transparent;
  color: #111827;
  outline: none;
  border: none;
}
.dark .field-input { color: #f9fafb; }
.field-input::placeholder { color: #9ca3af; }

.field-error-msg {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 12px;
  color: #ef4444;
  margin-top: 2px;
}

/* ─── Summary Card ────────────────────────────────── */
.summary-card {
  background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
  border: 1px solid #c7d2fe;
  border-radius: 12px;
  padding: 12px 16px;
}
.dark .summary-card {
  background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 100%);
  border-color: #4338ca;
}
.summary-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  color: #fff;
  font-weight: 700;
  font-size: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

/* ─── Buttons ─────────────────────────────────────── */
.btn-primary-gradient {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 9px 20px;
  border-radius: 9px;
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  box-shadow: 0 4px 14px rgba(79,70,229,0.35);
  transition: opacity 0.2s, transform 0.15s;
  cursor: pointer;
  border: none;
}
.btn-primary-gradient:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-primary-gradient:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

.btn-secondary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 9px 16px;
  border-radius: 9px;
  font-size: 14px;
  font-weight: 500;
  color: #6b7280;
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
  transition: background 0.2s;
  cursor: pointer;
}
.btn-secondary:hover { background: #e5e7eb; }
.dark .btn-secondary {
  background: #374151;
  border-color: #4b5563;
  color: #d1d5db;
}
.dark .btn-secondary:hover { background: #4b5563; }

.btn-ghost {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 9px 14px;
  border-radius: 9px;
  font-size: 14px;
  font-weight: 500;
  color: #6b7280;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: color 0.2s;
}
.btn-ghost:hover { color: #4f46e5; }

/* ─── Success Card ────────────────────────────────── */
.success-card {
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 4px 32px rgba(79,70,229,0.1);
  border: 1px solid #e5e7eb;
}
.dark .success-card {
  background: #1f2937;
  border-color: #374151;
}
.success-icon-wrapper {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 24px rgba(79,70,229,0.35);
}
</style>
