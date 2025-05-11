import { checkAuth } from "../../router.js"

export const getExpensesRoutes = () => {
    return [
        {
            path: "/expenses",
            name: "expenses-index",
            beforeEnter: checkAuth,
            component: () => import("@/modules/expenses/views/Index.vue"),
            meta: {
                resource: "expenses",
                createTitle: () => "الدفعات",
                isDetailPageOutlet: true,
            },
            children: [
                {
                    path: "",
                    name: `expenses/outlet`,
                    component: () => import("./components/ExpenseDetailsWrapper.vue"),
                    meta: {
                        isDetailPageOutlet: true,
                    },
                    children: [
                        {
                            path: `:id/general`,
                            name: `expenses/general`,
                            component: () => import("@/modules/expenses/views/Edit.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "expenses",
                            },
                        },
                    ],
                },
            ],
        },
    ]
}
