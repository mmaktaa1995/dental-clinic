const redirectIfNotAuth = (to, from, next) => {
    if (localStorage.getItem('user'))
        next('/');
    else
        next();
};

const checkAuth = (to, from, next) => {
    let user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null
    if (user) {
        if (user.admin)
            next();
        else
            next('/unauthorized');

    } else
        next('/login');
};
const checkStudent = (to, from, next) => {
    let user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null
    if (user) {
        if (!user.admin)
            next();
        else
            next('/unauthorized');

    } else
        next('/login');
};
let user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null
export default [
    {path: '/', redirect: '/patients'},
    {
        path: '/login',
        name: 'login',
        beforeEnter: redirectIfNotAuth,
        component: () => import('./screens/auth/login.vue'),
        meta: {
            createTitle: () => 'Login',
        },
    },
    {
        path: '/register',
        name: 'register',
        beforeEnter: redirectIfNotAuth,
        component: () => import('./screens/auth/register.vue'),
        meta: {
            createTitle: () => 'Register',
        },
    },

    {
        path: '/patients',
        name: 'patients-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/patients/index.vue'),
        meta: {
            resource: 'patients',
            createTitle: () => 'المرضى',
        },
        children: [
            {
                path: ':id/delete',
                name: 'patients-delete',
                component: () => import('./screens/patients/delete.vue'),
                meta: {
                    resource: 'patients',
                    createTitle: () => 'حذف مريض',
                },
            },
            {
                path: 'create',
                name: 'patients-create',
                component: () => import('./screens/patients/create.vue'),
                meta: {
                    resource: 'patients',
                    createTitle: () => 'إضافة مريض',
                },
            },
            {
                path: ':id/edit',
                name: 'patients-edit',
                component: () => import('./screens/patients/edit.vue'),
                meta: {
                    resource: 'patients',
                    createTitle: () => 'تعديل مريض',
                },
            },
            {
                path: ':id/visits',
                name: 'patients-visits',
                component: () => import('./screens/patients/visits.vue'),
                meta: {
                    resource: 'patients/:id/visits',
                    createTitle: () => 'زيارات المريض',
                },
            },
            {
                path: ':id/files',
                name: 'patients-files',
                component: () => import('./screens/patients/upload-files.vue'),
                meta: {
                    resource: 'patients',
                    createTitle: () => 'ملفات المريض',
                },
            },
        ]
    },

    {
        path: '/deleted-patients',
        name: 'deleted-patients-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/deleted-patients/index.vue'),
        meta: {
            resource: 'patients',
            queryParams: {deleted: 1},
            createTitle: () => 'المرضى المحذوفين',
        },
        children: [
            {
                path: ':id/restore',
                name: 'deleted-patients-restore',
                component: () => import('./screens/deleted-patients/restore.vue'),
                meta: {
                    resource: 'patients',
                    createTitle: () => 'استعادة مريض',
                },
            },
        ]
    },

    {
        path: '/visits',
        name: 'visits-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/visits/index.vue'),
        meta: {
            resource: 'visits',
            createTitle: () => 'الزيارات',
        },
        children: [
            {
                path: ':id/delete',
                name: 'visits-delete',
                component: () => import('./screens/visits/delete.vue'),
                meta: {
                    resource: 'visits',
                    createTitle: () => 'حذف الزيارة',
                },
            },
            {
                path: 'create',
                name: 'visits-create',
                component: () => import('./screens/visits/create.vue'),
                meta: {
                    resource: 'visits',
                    createTitle: () => 'إنشاء زيارة',
                },
            },
            {
                path: ':id/edit',
                name: 'visits-edit',
                component: () => import('./screens/visits/edit.vue'),
                meta: {
                    resource: 'visits',
                    createTitle: () => 'تعديل زيارة',
                },
            },
        ]
    },

    {
        path: '/expenses',
        name: 'expenses-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/expenses/index.vue'),
        meta: {
            resource: 'expenses',
            createTitle: () => 'النفقات',
        },
        children: [
            {
                path: ':id/delete',
                name: 'expenses-delete',
                component: () => import('./screens/expenses/delete.vue'),
                meta: {
                    resource: 'expenses',
                    createTitle: () => 'حذف نفقة',
                },
            },
            {
                path: 'create',
                name: 'expenses-create',
                component: () => import('./screens/expenses/create.vue'),
                meta: {
                    resource: 'expenses',
                    createTitle: () => 'إضافة نفقة',
                },
            },
            {
                path: ':id/edit',
                name: 'expenses-edit',
                component: () => import('./screens/expenses/edit.vue'),
                meta: {
                    resource: 'expenses',
                    createTitle: () => 'تعديل نفقة',
                },
            },
        ]
    },

    {
        path: '/services',
        name: 'services-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/services/index.vue'),
        meta: {
            resource: 'services',
            createTitle: () => 'الخدمات',
        },
        children: [
            {
                path: ':id/delete',
                name: 'services-delete',
                component: () => import('./screens/services/delete.vue'),
                meta: {
                    resource: 'services',
                    createTitle: () => 'حذف خدمة',
                },
            },
            {
                path: 'create',
                name: 'services-create',
                component: () => import('./screens/services/create.vue'),
                meta: {
                    resource: 'services',
                    createTitle: () => 'إضافة خدمة',
                },
            },
            {
                path: ':id/edit',
                name: 'services-edit',
                component: () => import('./screens/services/edit.vue'),
                meta: {
                    resource: 'services',
                    createTitle: () => 'تعديل خدمة',
                },
            },
        ]
    },

    {
        path: '/payments',
        name: 'payments-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/payments/index.vue'),
        meta: {
            resource: 'payments',
            createTitle: () => 'الدفعات',
        },
        children: [
            {
                path: ':id/delete',
                name: 'payments-delete',
                component: () => import('./screens/payments/delete.vue'),
                meta: {
                    resource: 'payments',
                    createTitle: () => 'حذف دفعة',
                },
            },
            {
                path: 'create',
                name: 'payments-create',
                component: () => import('./screens/payments/create.vue'),
                meta: {
                    resource: 'payments',
                    createTitle: () => 'إضافة دفعة',
                },
            },
            {
                path: ':id/edit',
                name: 'payments-edit',
                component: () => import('./screens/payments/edit.vue'),
                meta: {
                    resource: 'payments',
                    createTitle: () => 'تعديل دفعة',
                },
            },
        ]
    },

    {
        path: '/patients-files',
        name: 'patients-files-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/patients-files/index.vue'),
        meta: {
            resource: 'patients-files',
            createTitle: () => 'الإضبارات',
        },
        children: [
            {
                path: ':id/show',
                name: 'patients-files-show',
                component: () => import('./screens/patients-files/show.vue'),
                meta: {
                    resource: 'patients-files',
                    createTitle: () => 'إضبارة مريض',
                },
                children:[
                    {
                        path: 'delete',
                        name: 'patients-files-delete',
                        component: () => import('./screens/patients-files/delete.vue'),
                        meta: {
                            resource: 'patients-files',
                            createTitle: () => 'حذف إضبارة مريض',
                        },
                    }
                ]
            }
        ]
    },

    {
        path: '/statistics',
        name: 'statistics',
        beforeEnter: checkAuth,
        component: () => import('./screens/statistics/index.vue'),
        meta: {
            resource: 'statistics',
            createTitle: () => 'الإحصائيات',
        },
    },
    {
        path: '/debits',
        name: 'debits-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/debits/index.vue'),
        meta: {
            resource: 'patients/debits',
            createTitle: () => ' المبالغ المتبقية',
        },
    },
    {
        path: '/appointments',
        name: 'appointments-index',
        beforeEnter: checkAuth,
        component: () => import('./screens/appointments/index.vue'),
        meta: {
            resource: 'appointments',
            createTitle: () => 'المواعيد',
        },
        children: [
            {
                path: 'create',
                name: 'appointments-create',
                component: () => import('./screens/appointments/create.vue'),
                meta: {
                    resource: 'appointments',
                    createTitle: () => 'إضافة موعد',
                },
            },
            {
                path: ':id/delete',
                name: 'appointments-delete',
                component: () => import('./screens/appointments/delete.vue'),
                meta: {
                    resource: 'appointments',
                     createTitle: () => 'حذف موعد',
                },
            },
            {
                path: ':id/edit',
                name: 'appointments-edit',
                component: () => import('./screens/appointments/edit.vue'),
                meta: {
                    resource: 'appointments',
                     createTitle: () => 'تعديل موعد',
                },
            },
        ]
    },
    {
        path: '/unauthorized',
        component: () => import('./screens/403.vue'),
        meta: {
            createTitle: () => 'Login',
        },
    },

    {
        path: '*',
        redirect: '/404'
    },

    {
        path: '/404',
        component: () => import('./screens/404.vue'),
        meta: {
            createTitle: () => 'Login',
        },
    },
];
