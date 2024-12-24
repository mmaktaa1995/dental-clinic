import { checkAuth } from "../../router.js"

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
                    component: () => import("./components/PatientDetailWrapper.vue"),
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
                            path: `:id/debits`,
                            name: `patients/debits`,
                            component: () => import("@/modules/patients/views/Debits.vue"),
                            meta: {
                                isDetailPage: true,
                                module: "patients",
                            },
                        },
                    ],
                },
                {
                    path: ":id/visits",
                    name: "patients-visits",
                    component: () => import("./views/visits.vue"),
                    meta: {
                        resource: "patients/:id/visits",
                        createTitle: () => "زيارات المريض",
                    },
                },
                {
                    path: ":id/files",
                    name: "patients-files",
                    component: () => import(".//views/upload-files.vue"),
                    meta: {
                        resource: "patients",
                        createTitle: () => "ملفات المريض",
                    },
                },
            ],
        },
    ]
}
