/// <reference types="vite/client" />

declare module "*.vue" {
    import type { DefineComponent } from "vue"
    // eslint-disable-next-line @typescript-eslint/no-explicit-any, @typescript-eslint/ban-types
    const component: DefineComponent<{}, {}, any>
    export default component
}

interface ImportMetaEnv {
    readonly VITE_BACKEND_URL: string
    readonly VITE_OLD_BACKEND_ROOT: string
    readonly VITE_INTERCOM_APP_ID: string
    readonly VITE_HUBSPOT_CHAT_WIDGET: string
    readonly VITE_HUBSPOT_TRACKING: string
}

interface ImportMeta {
    readonly env: ImportMetaEnv
}
