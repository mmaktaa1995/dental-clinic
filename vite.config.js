import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import vue from "@vitejs/plugin-vue"
import path from "path"

// https://github.com/intlify/bundle-tools/tree/main/packages/unplugin-vue-i18n
import VueI18nPlugin from "@intlify/unplugin-vue-i18n/vite"

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
    return {
        plugins: [
            laravel({
                refresh: true,
                input: ["resources/css/app.css", "resources/js/app.js"],
            }),
            // VueI18nPlugin({
            //     include: [path.resolve(path.dirname("/"), "./resources/js/lang/*.json")],
            //     strictMessage: false,
            // }),
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
