declare module 'chart.js' {
  // Re-export all types from chart.js
  export * from 'chart.js/auto';
  
  // Extend the Plugin interface
  interface Plugin<TType extends ChartType = ChartType, O = unknown> {
    id: string;
    beforeInit?: (chart: Chart, args: { cancelable: boolean }) => void | { cancelable: boolean };
    afterInit?: (chart: Chart) => void;
  }
  
  // Extend Chart interface
  interface Chart<TType extends ChartType = ChartType, TData = any, TLabel = unknown> {
    // Add any custom properties or methods
  }
}
