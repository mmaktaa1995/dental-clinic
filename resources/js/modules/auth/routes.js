import { redirectIfNotAuth } from "@/router.js"

export const getAuthRoutes = () => {
    return [
        {
            path: "/login",
            name: "login",
            beforeEnter: redirectIfNotAuth,
            component: () => import("@/modules/auth/views/login.vue"),
            meta: {
                createTitle: () => "Login",
            },
        },
        {
            path: "/register",
            name: "register",
            beforeEnter: redirectIfNotAuth,
            component: () => import("@/modules/auth/views/register.vue"),
            meta: {
                createTitle: () => "Register",
            },
        },
    ]
}
