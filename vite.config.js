import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import vue from "@vitejs/plugin-vue"

export default defineConfig(({ mode }) => {
    return {
        plugins: [
            laravel({
                refresh: true,
                input: ["resources/css/app.css", "resources/js/app.js"],
            }),
            vue({
                template: {
                    compilerOptions: {
                        compatConfig: {
                            MODE: 2,
                        },
                    },
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        resolve: {
            alias: {
                "@": "/resources/js",
                vue: "vue/dist/vue.esm-bundler.js",
                // vue: '@vue/compat'
            },
        },
        server: {
            host: "clinic.test",
            port: 3001,
        },
        build: {
            sourcemap: true,
        },
    }
})
