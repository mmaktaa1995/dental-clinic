// Type definitions for Chart.js
import 'chart.js';

declare module 'chart.js' {
  // Re-export all types from chart.js
  export * from 'chart.js/auto';
  
  // Extend the Plugin interface
  interface Plugin<TType extends keyof ChartTypeRegistry = keyof ChartTypeRegistry, O = unknown> {
    id: string;
    beforeInit?: (chart: Chart, args: { cancelable: boolean }) => void | { cancelable: boolean };
    afterInit?: (chart: Chart) => void;
  }
  
  // Extend Chart interface
  interface Chart<TType extends keyof ChartTypeRegistry = keyof ChartTypeRegistry, TData = any, TLabel = unknown> {
    // Add any custom properties or methods
  }
  
  // Extend ChartConfiguration to include our custom options
  interface ChartConfiguration<TType extends keyof ChartTypeRegistry = keyof ChartTypeRegistry, TData = any, TLabel = unknown> {
    // Add any custom configuration options
  }
}

// Global Chart.js types
declare const Chart: typeof import('chart.js').Chart;
declare const registerables: any; // This is a workaround for the registerables issue
