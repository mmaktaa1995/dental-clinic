import { defineStore } from 'pinia';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { api } from '@/logic/api';

interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
  roles?: Array<{
    id: number;
    name: string;
    permissions: Array<{
      id: number;
      name: string;
    }>;
  }>;
  permissions?: Array<{
    id: number;
    name: string;
  }>;
}

interface LoginCredentials {
  email: string;
  password: string;
  remember: boolean;
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const isAuthenticated = ref(false);
  const router = useRouter();

  const login = async (credentials: LoginCredentials) => {
    try {
      // We still need to use axios for the CSRF cookie as api utility doesn't have this method
      await fetch('/api/v1/sanctum/csrf-cookie', { credentials: 'include' });
      await api.post('/api/v1/login', credentials);
      
      const userResponse = await api.get('/user');
      user.value = userResponse.data;
      isAuthenticated.value = true;
      
      return true;
    } catch (error: any) {
      if (error.response?.status === 422) {
        throw error;
      }
      console.error('Login error:', error);
      throw new Error('An error occurred during login');
    }
  };

  const logout = async () => {
    try {
      await api.post('/logout');
      user.value = null;
      isAuthenticated.value = false;
      await router.push({ name: 'login' });
    } catch (error) {
      console.error('Logout error:', error);
    }
  };

  const fetchUser = async () => {
    try {
      const response = await api.get('/user');
      user.value = response.data;
      isAuthenticated.value = true;
      return response;
    } catch (error) {
      user.value = null;
      isAuthenticated.value = false;
      return null;
    }
  };
  
  const isEmailVerified = () => {
    return user.value?.email_verified_at !== null;
  };
  
  const resendVerificationEmail = async () => {
    try {
      await api.post('/email/resend');
      return { success: true, message: 'Verification link sent!' };
    } catch (error: any) {
      console.error('Error resending verification email:', error);
      return { 
        success: false, 
        message: error.response?.data?.message || 'Failed to send verification email' 
      };
    }
  };

  return {
    user,
    isAuthenticated,
    login,
    logout,
    fetchUser,
    isEmailVerified,
    resendVerificationEmail
  };
});
