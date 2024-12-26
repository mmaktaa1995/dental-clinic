const defaultTheme = require("tailwindcss/defaultTheme")

module.exports = {
    purge: {
        content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.vue", "./resources/js/**/*.js"],
        // content: require('fast-glob').sync([
        //     'source/**/*.{blade.php,md,html,vue}'  // Added 'vue'
        // ],{ dot: true }),

        options: {
            whitelist: ["text-green-900", "text-green-800", "bg-green-100", "text-blue-900", "text-blue-800", "bg-blue-100", "text-yellow-900", "text-yellow-800", "bg-yellow-100", "text-red-900", "text-red-800", "bg-red-100", "text-gray-900", "text-gray-800", "bg-gray-100"],
        },
    },
    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                blink: {
                    "0%, 100%": { opacity: 1 },
                    "50%": { opacity: 0 },
                },
            },
            animation: {
                blink: "blink 1s infinite",
            },
        },
    },
    variants: {},
}
