import { checkAuth } from "../../router.js"

export const getDebitsRoutes = () => {
    return [
        {
            path: "/debits",
            name: "debits-index",
            beforeEnter: checkAuth,
            component: () => import("@/modules/debits/views/Index.vue"),
            meta: {
                resource: "patients/debits",
                createTitle: () => " المبالغ المتبقية",
            },
        },
    ]
}
