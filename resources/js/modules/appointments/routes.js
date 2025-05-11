import { checkAuth } from "../../router.js"

export const getAppointmentsRoutes = () => {
    return [
        {
            path: "/appointments",
            name: "appointments-index",
            beforeEnter: checkAuth,
            component: () => import("@/modules/appointments/views/Index.vue"),
            meta: {
                resource: "appointments",
                createTitle: () => "المواعيد",
            },
        },
    ]
}
