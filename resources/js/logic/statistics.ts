import { ref } from 'vue';
import { api } from './api';
import type { 
  TimeSeriesData, 
  StatisticsResponse, 
  AppointmentStats, 
  RevenueStats, 
  NewPatientsStats 
} from '@/types/statistics';

const API_PREFIX = '/api/v1/statistics';

interface UseStatisticsReturn {
  loading: typeof loading;
  error: typeof error;
  fetchOverview: (params?: Record<string, any>) => Promise<StatisticsResponse | null>;
  fetchAppointments: (params?: Record<string, any>) => Promise<AppointmentStats | null>;
  fetchRevenue: (params?: Record<string, any>) => Promise<RevenueStats | null>;
  fetchPatientGrowth: (params?: Record<string, any>) => Promise<NewPatientsStats | null>;
  fetchTimeSeries: (metric: string, params?: Record<string, any>) => Promise<TimeSeriesData[] | null>;
}

const loading = ref(false);
const error = ref<string | null>(null);

export const useStatistics = (): UseStatisticsReturn => {
  const fetchOverview = async (params: Record<string, any> = {}): Promise<StatisticsResponse | null> => {
    try {
      loading.value = true;
      error.value = null;
      return await api.get(`${API_PREFIX}/overview`, params);
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch statistics';
      return null;
    } finally {
      loading.value = false;
    }
  };

  const fetchAppointments = async (params: Record<string, any> = {}): Promise<AppointmentStats | null> => {
    try {
      loading.value = true;
      error.value = null;
      return await api.get(`${API_PREFIX}/appointments`, params);
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch appointment statistics';
      return null;
    } finally {
      loading.value = false;
    }
  };

  const fetchRevenue = async (params: Record<string, any> = {}): Promise<RevenueStats | null> => {
    try {
      loading.value = true;
      error.value = null;
      return await api.get(`${API_PREFIX}/revenue`, params);
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch revenue statistics';
      return null;
    } finally {
      loading.value = false;
    }
  };

  const fetchPatientGrowth = async (params: Record<string, any> = {}): Promise<NewPatientsStats | null> => {
    try {
      loading.value = true;
      error.value = null;
      return await api.get(`${API_PREFIX}/patient-growth`, params);
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch patient growth statistics';
      return null;
    } finally {
      loading.value = false;
    }
  };

  const fetchTimeSeries = async (metric: string, params: Record<string, any> = {}): Promise<TimeSeriesData[] | null> => {
    try {
      loading.value = true;
      error.value = null;
      return await api.get(`${API_PREFIX}/time-series/${metric}`, params);
    } catch (err: any) {
      error.value = err.message || `Failed to fetch ${metric} time series data`;
      return null;
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    error,
    fetchOverview,
    fetchAppointments,
    fetchRevenue,
    fetchPatientGrowth,
    fetchTimeSeries
  };
};
