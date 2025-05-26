import { checkAuth } from "@/router.js"

export const getRolesRoutes = () => {
    return [
        {
            path: "/roles",
            name: "roles-index",
            beforeEnter: checkAuth,
            component: () => import("./views/Index.vue"),
            meta: {
                resource: "roles",
                createTitle: () => "الأدوار",
                isDetailPageOutlet: true,
            },
            children: [
                {
                    path: "",
                    name: `roles-outlet`,
                    component: () => import("./components/RoleDetailsWrapper.vue"),
                    meta: {
                        isDetailPageOutlet: true,
                    },
                    children: [
                        {
                            path: `:id/general`,
                            name: `roles/general`,
                            component: () => import("@/modules/roles/views/Edit.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "roles",
                            },
                        },
                        {
                            path: `create`,
                            name: `roles-create`,
                            component: () => import("@/modules/roles/views/Edit.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "roles",
                                isCreatePage: true,
                            },
                        },
                    ],
                },
            ],
        },
    ]
}
