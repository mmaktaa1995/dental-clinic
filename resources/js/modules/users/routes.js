import { checkAuth } from "@/router.js"

export const getUsersRoutes = () => {
    return [
        {
            path: "/users",
            name: "users-index",
            beforeEnter: checkAuth,
            component: () => import("./views/Index.vue"),
            meta: {
                resource: "users",
                createTitle: () => "المستخدمين",
                isDetailPageOutlet: true,
            },
            children: [
                {
                    path: "",
                    name: `users-outlet`,
                    component: () => import("./components/UserDetailsWrapper.vue"),
                    meta: {
                        isDetailPageOutlet: true,
                    },
                    children: [
                        {
                            path: `:id/general`,
                            name: `users/general`,
                            component: () => import("@/modules/users/views/Edit.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "users",
                            },
                        },
                        {
                            path: `create`,
                            name: `users-create`,
                            component: () => import("@/modules/users/views/Edit.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "users",
                                isCreatePage: true,
                            },
                        },
                    ],
                },
            ],
        },
    ]
}
