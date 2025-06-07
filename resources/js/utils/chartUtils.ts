// Import Chart.js with type assertions
import { Chart, ChartConfiguration, ChartOptions, registerables } from 'chart.js';
import 'chartjs-adapter-date-fns';
import { format, parseISO, subMonths, subYears, isSameMonth, isSameYear, isSameDay } from 'date-fns';

// Register Chart.js components
if (registerables) {
  Chart.register(...registerables);
}

// Chart colors configuration
export const chartColors = {
  blue: '#3b82f6',
  green: '#10b981',
  red: '#ef4444',
  yellow: '#f59e0b',
  purple: '#8b5cf6',
  pink: '#ec4899',
  indigo: '#6366f1',
  teal: '#14b8a6',
  orange: '#f97316',
  cyan: '#06b6d4',
};

// Base chart options
export const chartOptions: ChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top' as const,
      labels: {
        boxWidth: 12
      },
    },
    tooltip: {
      mode: 'index' as const,
      intersect: false,
      callbacks: {
        label: function(context: any) {
          let label = context.dataset.label || '';
          if (label) {
            label += ': ';
          }
          if (context.parsed.y !== null) {
            // Format currency if the dataset has currency: true property
            if (context.dataset.currency) {
              label += new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
              }).format(context.parsed.y);
            } else {
              label += context.parsed.y;
            }
          }
          return label;
        }
      }
    },
  },
  scales: {
    x: {
      grid: {
        display: false,
      },
    },
    y: {
      beginAtZero: true,
      grid: {
        borderDash: [3, 3],
      },
      ticks: {
        callback: function(value: any) {
          // Format y-axis labels as currency if needed
          if (this.chart?.data?.datasets?.[0]?.currency) {
            return new Intl.NumberFormat('en-US', {
              style: 'currency',
              currency: 'USD',
              minimumFractionDigits: 0,
              maximumFractionDigits: 0,
            }).format(value);
          }
          return value;
        }
      }
    },
  },
};

// Calculate comparison with previous period
export const calculatePeriodComparison = (current: number, previous: number): { value: number; isPositive: boolean } => {
  if (previous === 0) return { value: 100, isPositive: true }; // Handle division by zero
  const change = ((current - previous) / Math.abs(previous)) * 100;
  return {
    value: Math.round(change * 10) / 10, // Round to 1 decimal place
    isPositive: change >= 0,
  };
};

// Create revenue chart
export const createRevenueChart = (
  ctx: HTMLCanvasElement,
  incomes: Array<{ date: string; amount: string }>,
  expenses: Array<{ date: string; amount: string }>,
  t: (key: string) => string
): Chart => {
  const labels = Array.isArray(incomes) ? 
    incomes.map((item) => format(new Date(item.date), 'MMM d')) : [];
  
  const incomeData = Array.isArray(incomes) ?
    incomes.map((item) => parseFloat(item.amount) || 0) : [];
    
  const expenseData = Array.isArray(expenses) ?
    expenses.map((item) => parseFloat(item.amount) || 0) : [];
  
  const config: ChartConfiguration = {
    type: 'line',
    data: {
      labels,
      datasets: [
        {
          label: t('statistics.income'),
          data: incomeData,
          borderColor: chartColors.green,
          backgroundColor: `${chartColors.green}20`,
          tension: 0.3,
          fill: true,
          currency: true,
        },
        {
          label: t('statistics.expenses'),
          data: expenseData,
          borderColor: chartColors.red,
          backgroundColor: `${chartColors.red}20`,
          tension: 0.3,
          fill: true,
          currency: true,
        },
      ],
    },
    options: {
      ...chartOptions,
      plugins: {
        ...chartOptions.plugins,
        title: {
          display: true,
          text: t('statistics.revenue_vs_expenses'),
        },
      },
    },
  };

  return new Chart(ctx, config);
};

// Create patients chart
export const createPatientsChart = (
  ctx: HTMLCanvasElement,
  patients: Array<{ date: string; count: number }>,
  t: (key: string) => string
): Chart => {
  const labels = Array.isArray(patients) ?
    patients.map((item) => format(new Date(item.date), 'MMM d')) : [];
    
  const patientData = Array.isArray(patients) ?
    patients.map((item) => item.count || 0) : [];
  
  const config: ChartConfiguration = {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          label: t('statistics.new_patients'),
          data: patientData,
          backgroundColor: chartColors.blue,
          borderRadius: 4,
        },
      ],
    },
    options: {
      ...chartOptions,
      plugins: {
        ...chartOptions.plugins,
        title: {
          display: true,
          text: t('statistics.patient_growth'),
        },
      },
    },
  };

  return new Chart(ctx, config);
};

// Create expenses chart
export const createExpensesChart = (
  ctx: HTMLCanvasElement,
  expenses: Array<{ date: string; amount: string; category?: string }>,
  t: (key: string) => string
): Chart | null => {
  if (!Array.isArray(expenses)) return null;
  
  // Group expenses by category
  const categories = new Map<string, number>();
  expenses.forEach((expense) => {
    const category = expense.category || t('statistics.uncategorized');
    const amount = parseFloat(expense.amount) || 0;
    categories.set(category, (categories.get(category) || 0) + amount);
  });
  
  const labels = Array.from(categories.keys());
  const data = Array.from(categories.values());
  
  // Generate colors for categories
  const backgroundColors = labels.map((_, index) => {
    const colors = Object.values(chartColors);
    return colors[index % colors.length];
  });
  
  const config: ChartConfiguration = {
    type: 'doughnut',
    data: {
      labels,
      datasets: [
        {
          data,
          backgroundColor: backgroundColors,
          borderWidth: 1,
          currency: true,
        },
      ],
    },
    options: {
      ...chartOptions,
      plugins: {
        ...chartOptions.plugins,
        title: {
          display: true,
          text: t('statistics.expenses_by_category'),
        },
      },
    },
  };

  return new Chart(ctx, config);
};

// Create appointments chart
export const createAppointmentsChart = (
  ctx: HTMLCanvasElement,
  visits: Array<{ date: string; status: string }>,
  t: (key: string) => string
): Chart | null => {
  if (!Array.isArray(visits)) return null;
  
  // Group visits by status
  const statuses = new Map<string, number>();
  visits.forEach((visit) => {
    const status = visit.status || 'unknown';
    statuses.set(status, (statuses.get(status) || 0) + 1);
  });
  
  const labels = Array.from(statuses.keys()).map(status => 
    t(`appointments.statuses.${status.toLowerCase()}`)
  );
  const data = Array.from(statuses.values());
  
  // Generate colors for statuses
  const backgroundColors = labels.map((_, index) => {
    const colors = Object.values(chartColors);
    return colors[(index + 2) % colors.length]; // Offset to get different colors
  });
  
  const config: ChartConfiguration = {
    type: 'pie',
    data: {
      labels,
      datasets: [
        {
          data,
          backgroundColor: backgroundColors,
          borderWidth: 1,
        },
      ],
    },
    options: {
      ...chartOptions,
      plugins: {
        ...chartOptions.plugins,
        title: {
          display: true,
          text: t('statistics.appointments_by_status'),
        },
      },
    },
  };

  return new Chart(ctx, config);
};

// Calculate period comparison data
export const calculateComparisonData = (currentData: any, previousData: any) => {
  if (!currentData || !previousData) return {};

  return {
    totalIncome: calculatePeriodComparison(
      currentData.totalIncome || 0,
      previousData.totalIncome || 0
    ),
    totalExpenses: calculatePeriodComparison(
      currentData.totalExpenses || 0,
      previousData.totalExpenses || 0
    ),
    totalPatients: calculatePeriodComparison(
      currentData.totalPatientsCount || 0,
      previousData.totalPatientsCount || 0
    ),
    totalDebts: calculatePeriodComparison(
      currentData.totalDebts || 0,
      previousData.totalDebts || 0
    ),
  };
};
