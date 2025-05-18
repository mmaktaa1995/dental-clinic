import { checkAuth } from "@/router.js"

export const getPatientsRoutes = () => {
    return [
        {
            path: "/patients",
            name: "patients-index",
            beforeEnter: checkAuth,
            component: () => import("./views/Index.vue"),
            meta: {
                resource: "patients",
                createTitle: () => "المرضى",
                isDetailPageOutlet: true,
            },
            children: [
                {
                    path: "",
                    name: `patients/outlet`,
                    component: () => import("./components/PatientDetailsWrapper.vue"),
                    meta: {
                        isDetailPageOutlet: true,
                    },
                    children: [
                        {
                            path: `:id/general`,
                            name: `patients/general`,
                            component: () => import("@/modules/patients/views/Edit.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "patients",
                            },
                        },
                        {
                            path: `:id/payments`,
                            name: `patients/payments`,
                            component: () => import("@/modules/patients/views/Payments.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "patients",
                            },
                        },
                        {
                            path: `:id/files`,
                            name: `patients/files`,
                            component: () => import("@/modules/patients/views/Files.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "patients",
                            },
                        },
                        {
                            path: `:id/debits`,
                            name: `patients/debits`,
                            component: () => import("@/modules/patients/views/Debits.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "patients",
                            },
                        },
                        {
                            path: `:id/visits`,
                            name: `patients/visits`,
                            component: () => import("@/modules/patients/views/Visits.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "patients",
                            },
                        },
                    ],
                },
            ],
        },
    ]
}
