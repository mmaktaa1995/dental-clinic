import { checkAuth } from "@/router.js"

export const getServicesRoutes = () => {
    return [
        {
            path: "/services",
            name: "services-index",
            beforeEnter: checkAuth,
            component: () => import("@/modules/services/views/Index.vue"),
            meta: {
                resource: "services",
                createTitle: () => "الدفعات",
                isDetailPageOutlet: true,
            },
            children: [
                {
                    path: "",
                    name: `services/outlet`,
                    component: () => import("./components/ServiceDetailsWrapper.vue"),
                    meta: {
                        isDetailPageOutlet: true,
                    },
                    children: [
                        {
                            path: `:id/general`,
                            name: `services/general`,
                            component: () => import("@/modules/services/views/Edit.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "services",
                            },
                        },
                    ],
                },
            ],
        },
    ]
}
