declare module 'chart.js' {
  import { ChartType, ChartConfiguration, ChartData, ChartOptions, Plugin } from 'chart.js/auto';
  
  // Re-export all types from chart.js/auto
  export * from 'chart.js/auto';
  
  // Extend the Plugin interface if needed
  interface Plugin<TType extends ChartType = ChartType, O = unknown> {
    id: string;
    beforeInit?: (chart: Chart, args: { cancelable: boolean }) => void | { cancelable: boolean };
    afterInit?: (chart: Chart) => void;
    // Add other plugin hooks as needed
  }
  
  // Extend Chart interface if needed
  interface Chart<TType extends ChartType = ChartType, TData = any, TLabel = unknown> {
    // Add any custom properties or methods
  }
}
