import { ComponentCustomProperties } from 'vue'
import { Router } from 'vue-router'

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $t: (key: string, values?: Record<string, any>) => string
    $d: (value: Date | number, options?: Intl.DateTimeFormatOptions | string) => string
    $n: (value: number, options?: Intl.NumberFormatOptions | string) => string
    $filters: {
      percentage(value: number, decimals?: number): string
    }
    $router: Router
  }
}

// This is necessary for template usage
declare global {
  const $t: (key: string, values?: Record<string, any>) => string
  const $d: (value: Date | number, options?: Intl.DateTimeFormatOptions | string) => string
  const $n: (value: number, options?: Intl.NumberFormatOptions | string) => string
}
