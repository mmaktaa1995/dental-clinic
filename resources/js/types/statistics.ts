// Types for statistics data

export interface TimeSeriesData {
  value: number;
  label: string;
}

export interface StatisticsResponse {
  expenses: TimeSeriesData[];
  visits: TimeSeriesData[];
  patients: TimeSeriesData[];
  incomes: TimeSeriesData[];
  debts: TimeSeriesData[];
  totalPatientsCount: number;
  totalExpenses: number;
  totalIncome: number;
  totalDebts: number;
}

export interface AppointmentStats {
  total: number;
  completed: number;
  cancelled: number;
  completion_rate: number;
}

export interface RevenueStats {
  total_revenue: number;
  cash_revenue: number;
  card_revenue: number;
  total_patients: number;
  average_revenue_per_patient: number;
}

export interface NewPatientsStats {
  new_patients: number;
  previous_period_new_patients: number;
  growth_rate: number;
}

export interface Widget {
  title: string;
  value: number | string;
  icon: string;
  color: string;
  trend?: {
    value: number;
    isPositive: boolean;
  };
}

export interface ChartData {
  labels: string[];
  datasets: {
    label: string;
    data: number[];
    backgroundColor: string;
    borderColor: string;
    borderWidth: number;
    fill?: boolean;
  }[];
}
