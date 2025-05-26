<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="text-center">
        <div class="flex justify-center">
          <svg style="transform: rotate(180deg)" viewBox="0 0 40 40" class="h-24 w-24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.345 9h10.55L9.618 20 4.345 9zm21.099 0h10.55l-5.276 11-5.274-11z" fill="#E9F9FD" fill-opacity=".1" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.62 20h10.549l-5.275 11L9.62 20z" fill="#25C4F2" fill-opacity=".22" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.169 20h10.55l-5.275 11-5.275-11z" fill="#25C4F2" fill-opacity=".2" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.169 20H9.619l5.275-11 5.275 11z" fill="#25C4F2" fill-opacity=".4" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M30.718 20h-10.55l5.276-11 5.274 11z" fill="#25C4F2" fill-opacity=".4" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M25.444 31h-10.55l5.275-11 5.275 11z" fill="#25C4F2" fill-opacity=".5" />
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M3.494 8.467A1 1 0 0 1 4.34 8h10.55a1 1 0 0 1 .902.568l4.373 9.12 4.373-9.12A1 1 0 0 1 25.44 8h10.55a1 1 0 0 1 .902 1.432L26.345 31.424a1.001 1.001 0 0 1-.905.576H14.89a1 1 0 0 1-.902-.568l-10.55-22a1 1 0 0 1 .056-.965zm21.95 2.846L29.13 19h-7.372l3.686-7.687zM5.934 10l3.686 7.687L13.306 10H5.933zm8.96 1.313L18.58 19h-7.372l3.686-7.687zM27.032 10l3.686 7.687L34.405 10h-7.373zm-1.588 18.687L21.758 21h7.372l-3.686 7.687zM23.855 30l-3.686-7.687L16.483 30h7.372zm-8.96-1.313L11.207 21h7.372l-3.686 7.687z"
              fill="#25C4F2"
            />
          </svg>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          {{ $t('auth.verifyEmail.title', 'Verify Your Email Address') }}
        </h2>
      </div>

      <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <!-- Success Message -->
        <div v-if="verificationSent" class="rounded-md bg-green-50 p-4 mb-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">
                {{ $t('auth.verifyEmail.verificationSent', 'A fresh verification link has been sent to your email address.') }}
              </p>
            </div>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="rounded-md bg-red-50 p-4 mb-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-red-800">
                {{ errorMessage }}
              </p>
            </div>
          </div>
        </div>

        <div class="text-center">
          <p class="text-sm text-gray-600">
            {{ $t('auth.verifyEmail.instructions', 'Before proceeding, please check your email for a verification link.') }}
            {{ $t('auth.verifyEmail.noEmail', 'If you did not receive the email,') }}
          </p>
          <div class="mt-4">
            <button
              @click="resendVerificationEmail"
              :disabled="resendDisabled || loading"
              :class="`w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ${(resendDisabled || loading) ? 'opacity-50 cursor-not-allowed' : ''}`"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ resendButtonText }}
            </button>
          </div>
          
          <div class="mt-4">
            <button
              @click="logout"
              class="text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none"
            >
              {{ $t('auth.verifyEmail.logout', 'Logout') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '@/stores/auth';
import { useAccountStore } from '@/modules/auth/accountStore';

const router = useRouter();
const { t } = useI18n();
const accountStore = useAccountStore();
const authStore = useAuthStore();

const verificationSent = ref(false);
const resendDisabled = ref(false);
const loading = ref(false);
const errorMessage = ref('');
const countdown = ref(0);
const resendButtonText = ref(t('auth.verifyEmail.resend', 'Resend Verification Email'));

// Handle email verification status
const checkVerificationStatus = async () => {
  try {
    await accountStore.getUser();
    
    // If email is already verified, redirect to dashboard
    if (accountStore.user?.email_verified_at) {
      router.push({ name: 'dashboard' });
    }
  } catch (error) {
    console.error('Error checking verification status:', error);
  }
};

// Resend verification email
const resendVerificationEmail = async () => {
  if (resendDisabled.value || loading.value) return;
  
  loading.value = true;
  errorMessage.value = '';
  verificationSent.value = false;
  
  try {
    // Check for pending verification data
    const pendingVerification = localStorage.getItem('pendingVerification');
    
    if (pendingVerification) {
      // If we have pending verification, try to log the user in first
      const { email, password } = JSON.parse(pendingVerification);
      await accountStore.login({ email, password });
    }
    
    // Request a new verification email
    const result = await authStore.resendVerificationEmail();
    
    if (!result.success) {
      errorMessage.value = result.message;
      return;
    }
    
    verificationSent.value = true;
    startCountdown();
  } catch (error) {
    console.error('Error resending verification email:', error);
    
    if (error.response?.status === 401 || error.response?.status === 403) {
      // If unauthorized, clear any pending verification and redirect to login
      localStorage.removeItem('pendingVerification');
      errorMessage.value = t('auth.verifyEmail.sessionExpired', 'Your session has expired. Please log in again.');
      
      // Redirect to login after a short delay
      setTimeout(() => {
        router.push({ name: 'login' });
      }, 3000);
    } else if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = t('auth.verifyEmail.resendError', 'Failed to send verification email. Please try again later.');
    }
  } finally {
    loading.value = false;
  }
};

// Start countdown timer for resend button
const startCountdown = () => {
  resendDisabled.value = true;
  countdown.value = 60;
  
  const timer = setInterval(() => {
    countdown.value--;
    
    if (countdown.value > 0) {
      resendButtonText.value = t('auth.verifyEmail.resendIn', 'Resend in {count} seconds', { count: countdown.value });
    } else {
      clearInterval(timer);
      resendDisabled.value = false;
      resendButtonText.value = t('auth.verifyEmail.resend', 'Resend Verification Email');
    }
  }, 1000);
};

// Logout user
const logout = async () => {
  try {
    await accountStore.logout();
    localStorage.removeItem('pendingVerification');
    router.push({ name: 'login' });
  } catch (error) {
    console.error('Logout error:', error);
  }
};

// Check verification status on component mount
onMounted(() => {
  checkVerificationStatus();
  
  // Check if email was just verified
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('verified') === '1') {
    router.push({ 
      name: 'login',
      query: { verified: '1' }
    });
  }
});
</script>
