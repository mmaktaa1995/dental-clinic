import { createRouter, createWebHistory } from "vue-router"
import { getPatientsRoutes } from "@/modules/patients/routes.js"
import { getPaymentsRoutes } from "@/modules/payments/routes.js"
import { getServicesRoutes } from "@/modules/services/routes.js"
import { getExpensesRoutes } from "@/modules/expenses/routes.js"
import { getDebitsRoutes } from "@/modules/debits/routes.js"
import { getAuthRoutes } from "@/modules/auth/routes.js"
import { getAppointmentsRoutes } from "@/modules/appointments/routes.js"
import { getUsersRoutes } from "@/modules/users/routes.js"
import { getRolesRoutes } from "@/modules/roles/routes.js"

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
    
    if (!user) {
        return next("/login")
    }
    
    // Check if route requires email verification
    if (to.matched.some(record => record.meta.requiresVerifyEmail) && !user.email_verified_at) {
        return next({ name: 'verification.notice' })
    }
    
    // Check admin access
    console.log(to.matched.some(record => record.meta.requiresAdmin), user.admin, to.path.startsWith('/users'))
    if (to.matched.some(record => record.meta.requiresAdmin) && !user.admin) {
        return next("/unauthorized")
    }
    
    // Restrict access to users and roles modules to admin users only
    if ((to.path.startsWith('/users') || to.path.startsWith('/roles')) && !user.admin) {
        return next("/unauthorized")
    }
    
    next()
}

const routes = [
    { path: "/", name: "home", redirect: "/patients" },

    ...getAuthRoutes(),
    ...getPatientsRoutes(),
    ...getExpensesRoutes(),
    ...getServicesRoutes(),
    ...getPaymentsRoutes(),
    ...getUsersRoutes(),
    ...getRolesRoutes(),
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
    ...getAppointmentsRoutes(),
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
    // Set document title
    if (to.meta.createTitle) {
        to.meta.title = to.meta.createTitle(to.params)
        document.title = to.meta.title + " | " + "Dental Clinic"
    } else {
        document.title = "Dental Clinic"
    }

    // Check authentication and verification status
    const user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null
    
    // Redirect to login if not authenticated and route requires auth
    if (to.matched.some(record => record.meta.requiresAuth) && !user) {
        return next('/login')
    }
    
    // Redirect to email verification if email is not verified
    if (user && !user.email_verified_at && to.name !== 'verification.notice') {
        return next({ name: 'verification.notice' })
    }
    
    next()
})

export function useCurrentRouter() {
    return router
}

export default router
