import { createRouter, createWebHistory } from "vue-router"
import { getPatientsRoutes } from "@/modules/patients/routes.js"
import { getPaymentsRoutes } from "@/modules/payments/routes.js"
import { getServicesRoutes } from "@/modules/services/routes.js"
import { getExpensesRoutes } from "@/modules/expenses/routes.js"
import { getDebitsRoutes } from "@/modules/debits/routes.js"

const redirectIfNotAuth = (to, from, next) => {
    if (localStorage.getItem("user")) next("/")
    else next()
}

export const checkAuth = (to, from, next) => {
    const user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null
    if (user) {
        if (user.admin) next()
        else next("/unauthorized")
    } else next("/login")
}
const checkStudent = (to, from, next) => {
    const user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null
    if (user) {
        if (!user.admin) next()
        else next("/unauthorized")
    } else next("/login")
}
const user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null
const routes = [
    { path: "/", redirect: "/patients" },
    {
        path: "/login",
        name: "login",
        beforeEnter: redirectIfNotAuth,
        component: () => import("./modules/auth/views/login.vue"),
        meta: {
            createTitle: () => "Login",
        },
    },
    {
        path: "/register",
        name: "register",
        beforeEnter: redirectIfNotAuth,
        component: () => import("./modules/auth/views/register.vue"),
        meta: {
            createTitle: () => "Register",
        },
    },
    ...getPatientsRoutes(),

    {
        path: "/deleted-patients",
        name: "deleted-patients-index",
        beforeEnter: checkAuth,
        component: () => import("./modules/deleted-patients/index.vue"),
        meta: {
            resource: "patients",
            queryParams: { deleted: 1 },
            createTitle: () => "المرضى المحذوفين",
        },
        children: [
            {
                path: ":id/restore",
                name: "deleted-patients-restore",
                component: () => import("./modules/deleted-patients/restore.vue"),
                meta: {
                    resource: "patients",
                    createTitle: () => "استعادة مريض",
                },
            },
        ],
    },

    {
        path: "/visits",
        name: "visits-index",
        beforeEnter: checkAuth,
        component: () => import("./modules/visits/index.vue"),
        meta: {
            resource: "visits",
            createTitle: () => "الزيارات",
        },
        children: [
            {
                path: ":id/delete",
                name: "visits-delete",
                component: () => import("./modules/visits/delete.vue"),
                meta: {
                    resource: "visits",
                    createTitle: () => "حذف الزيارة",
                },
            },
            {
                path: "create",
                name: "visits-create",
                component: () => import("./modules/visits/create.vue"),
                meta: {
                    resource: "visits",
                    createTitle: () => "إنشاء زيارة",
                },
            },
            {
                path: ":id/edit",
                name: "visits-edit",
                component: () => import("./modules/visits/edit.vue"),
                meta: {
                    resource: "visits",
                    createTitle: () => "تعديل زيارة",
                },
            },
        ],
    },

    ...getExpensesRoutes(),
    ...getServicesRoutes(),
    ...getPaymentsRoutes(),
    {
        path: "/patients-files",
        name: "patients-files-index",
        beforeEnter: checkAuth,
        component: () => import("./modules/patients-files/index.vue"),
        meta: {
            resource: "patients-files",
            createTitle: () => "الإضبارات",
        },
        children: [
            {
                path: ":id/show",
                name: "patients-files-show",
                component: () => import("./modules/patients-files/show.vue"),
                meta: {
                    resource: "patients-files",
                    createTitle: () => "إضبارة مريض",
                },
                children: [
                    {
                        path: "delete",
                        name: "patients-files-delete",
                        component: () => import("./modules/patients-files/delete.vue"),
                        meta: {
                            resource: "patients-files",
                            createTitle: () => "حذف إضبارة مريض",
                        },
                    },
                ],
            },
        ],
    },

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
            createTitle: () => "Login",
        },
    },
    {
        path: "/404",
        component: () => import("./modules/404.vue"),
        meta: {
            createTitle: () => "Login",
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
