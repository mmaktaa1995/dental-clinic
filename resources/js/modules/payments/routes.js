import { checkAuth } from "@/router.js"

export const getPaymentsRoutes = () => {
    return [
        {
            path: "/payments",
            name: "payments-index",
            beforeEnter: checkAuth,
            component: () => import("@/modules/payments/views/Index.vue"),
            meta: {
                resource: "payments",
                createTitle: () => "الدفعات",
            },
        },
    ]
}
