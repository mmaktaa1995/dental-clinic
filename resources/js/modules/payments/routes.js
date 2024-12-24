import { checkAuth } from "../../router.js"

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
            children: [
                {
                    path: ":id/delete",
                    name: "payments-delete",
                    component: () => import("@/modules/payments/delete.vue"),
                    meta: {
                        resource: "payments",
                        createTitle: () => "حذف دفعة",
                    },
                },
                {
                    path: "create",
                    name: "payments-create",
                    component: () => import("@/modules/payments/create.vue"),
                    meta: {
                        resource: "payments",
                        createTitle: () => "إضافة دفعة",
                    },
                },
                {
                    path: ":id/edit",
                    name: "payments-edit",
                    component: () => import("@/modules/payments/edit.vue"),
                    meta: {
                        resource: "payments",
                        createTitle: () => "تعديل دفعة",
                    },
                },
            ],
        },
    ]
}
