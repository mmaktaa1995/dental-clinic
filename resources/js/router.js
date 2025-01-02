import { createRouter, createWebHistory } from "vue-router"
import { getPatientsRoutes } from "@/modules/patients/routes.js"
import { getPaymentsRoutes } from "@/modules/payments/routes.js"
import { getServicesRoutes } from "@/modules/services/routes.js"
import { getExpensesRoutes } from "@/modules/expenses/routes.js"
import { getDebitsRoutes } from "@/modules/debits/routes.js"
import { getAuthRoutes } from "@/modules/auth/routes.js"

export const redirectIfNotAuth = (to, from, next) => {
    const user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null

    if (user) {
        next("/")
    } else {
        next()
    }
}

export const checkAuth = (to, from, next) => {
    const user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null
    if (user) {
        if (user.admin) {
            next()
        } else {
            next("/unauthorized")
        }
    } else {
        next("/login")
    }
}

const routes = [
    { path: "/", name: "home", redirect: "/patients" },

    ...getAuthRoutes(),
    ...getPatientsRoutes(),
    ...getExpensesRoutes(),
    ...getServicesRoutes(),
    ...getPaymentsRoutes(),
    {
        path: "/statistics",
        name: "statistics",
        beforeEnter: checkAuth,
        component: () => import("./modules/statistics/Index.vue"),
        meta: {
            resource: "statistics",
            createTitle: () => "الإحصائيات",
        },
    },
    ...getDebitsRoutes(),
    {
        path: "/appointments",
        name: "appointments-index",
        beforeEnter: checkAuth,
        component: () => import("./modules/appointments/index.vue"),
        meta: {
            resource: "appointments",
            createTitle: () => "المواعيد",
        },
        children: [
            {
                path: "create",
                name: "appointments-create",
                component: () => import("./modules/appointments/create.vue"),
                meta: {
                    resource: "appointments",
                    createTitle: () => "إضافة موعد",
                },
            },
            {
                path: ":id/delete",
                name: "appointments-delete",
                component: () => import("./modules/appointments/delete.vue"),
                meta: {
                    resource: "appointments",
                    createTitle: () => "حذف موعد",
                },
            },
            {
                path: ":id/edit",
                name: "appointments-edit",
                component: () => import("./modules/appointments/edit.vue"),
                meta: {
                    resource: "appointments",
                    createTitle: () => "تعديل موعد",
                },
            },
        ],
    },
    {
        path: "/unauthorized",
        component: () => import("./modules/403.vue"),
        meta: {
            createTitle: () => "403",
        },
    },
    { path: "/:pathMatch(.*)*", redirect: "/404" },
    {
        path: "/404",
        component: () => import("./modules/404.vue"),
        meta: {
            createTitle: () => "404",
        },
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            // If you want to activate the following line, consider that the KDetailPage component also has some custom scroll handling which won't work when this line is active.
            // return { top: 0 }
        }
    },
})

router.beforeEach((to, from, next) => {
    if (to.meta.createTitle) {
        to.meta.title = to.meta.createTitle(to.params)
    }

    document.title = "Clinic - " + to.meta.title

    next()
})

export function useCurrentRouter() {
    return router
}

export default router
