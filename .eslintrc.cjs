/* eslint-env node */

module.exports = {
    extends: ["plugin:vue/vue3-recommended", "eslint:recommended", "@vue/eslint-config-typescript/recommended", "@vue/eslint-config-prettier", "plugin:jsonc/recommended-with-jsonc"],
    parserOptions: {
        ecmaVersion: "latest",
    },
    globals: {
        amp: true,
    },
    rules: {
        "prettier/prettier": [
            "error",
            {
                semi: false,
                printWidth: 300,
            },
        ],
        "jsonc/quote-props": "off",
        "jsonc/sort-keys": ["warn"],
        "jsonc/quotes": "off",
        semi: ["error", "never"],
        "@typescript-eslint/no-explicit-any": "off",
        "@typescript-eslint/ban-ts-comment": "off",
        "@typescript-eslint/no-non-null-assertion": "off",
        "vue/no-v-html": "off",
        "vue/multi-word-component-names": "off",
        "@typescript-eslint/no-this-alias": "off",
        "no-irregular-whitespace": "off",
    },
    ignorePatterns: ["public/vendor/tinymce/*"],
}
