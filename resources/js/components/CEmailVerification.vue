<template>
  <div v-if="!isVerified" class="email-verification-alert">
    <div class="alert alert-warning" role="alert">
      <div class="d-flex align-items-center">
        <div class="me-3">
          <i class="fas fa-exclamation-triangle fa-2x"></i>
        </div>
        <div>
          <h5 class="alert-heading">{{ $t('Email Verification Required') }}</h5>
          <p class="mb-0">{{ $t('Please verify your email address to access all features.') }}</p>
          <div v-if="message" class="mt-2" :class="{'text-success': success, 'text-danger': !success}">
            {{ message }}
          </div>
        </div>
        <div class="ms-auto">
          <button 
            class="btn btn-outline-primary" 
            @click="resendVerificationEmail" 
            :disabled="isResending"
          >
            <span v-if="isResending" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
            {{ $t('Resend Verification Email') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const isResending = ref(false);
const message = ref('');
const success = ref(false);

const isVerified = computed(() => authStore.isEmailVerified());

const resendVerificationEmail = async () => {
  isResending.value = true;
  message.value = '';
  
  try {
    const result = await authStore.resendVerificationEmail();
    success.value = result.success;
    message.value = result.message;
  } catch (error) {
    success.value = false;
    message.value = 'An error occurred while sending the verification email.';
    console.error('Error resending verification email:', error);
  } finally {
    isResending.value = false;
    
    // Clear success message after 5 seconds
    if (success.value) {
      setTimeout(() => {
        message.value = '';
      }, 5000);
    }
  }
};
</script>

<style scoped>
.email-verification-alert {
  margin-bottom: 1.5rem;
}
</style>
