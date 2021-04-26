const redirectIfNotAuth = (to, from, next) => {
    if (localStorage.getItem('user'))
        next('/');
    else
        next();
};

const checkAuth = (to, from, next) => {
    let user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null
    if (user) {
        // if (user.admin)
            next();
        // else
        //     next('/unauthorized');

    } else
        next('/login');
};

export default [
    {path: '/', redirect: '/courses'},
    // {
    //     path: '/metrics',
    //     name: 'metrics',
    //     component: require('./screens/jobs/metrics').default,
    //     meta: {
    //         resource: 'jobs',
    //         createTitle: () => 'Metrics',
    //     },
    // },
    {
        path: '/login',
        name: 'login',
        beforeEnter: redirectIfNotAuth,
        component: require('./screens/auth/login').default,
        meta: {
            createTitle: () => 'Login',
        },
    },
    {
        path: '/register',
        name: 'register',
        beforeEnter: redirectIfNotAuth,
        component: require('./screens/auth/register').default,
        meta: {
            createTitle: () => 'Register',
        },
    },

    {
        path: '/courses',
        name: 'courses-index',
        beforeEnter: checkAuth,
        component: require('./screens/courses/index').default,
        meta: {
            resource: 'courses',
            createTitle: () => 'Courses',
        },
        children: [
            {
                path: ':id/delete',
                name: 'courses-delete',
                component: require('./screens/courses/delete').default,
                meta: {
                    resource: 'courses',
                    createTitle: () => 'Delete Course',
                },
            },
            {
                path: 'create',
                name: 'courses-create',
                component: require('./screens/courses/create').default,
                meta: {
                    resource: 'courses',
                    createTitle: () => 'Create Course',
                },
            },
            {
                path: ':id/edit',
                name: 'courses-edit',
                component: require('./screens/courses/edit').default,
                meta: {
                    resource: 'courses',
                    createTitle: () => 'Edit Course',
                },
            },
        ]
    },

    {
        path: '/students',
        name: 'students-index',
        beforeEnter: checkAuth,
        component: require('./screens/students/index').default,
        meta: {
            resource: 'students',
            createTitle: () => 'Students',
        },
        children: [
            {
                path: ':id/delete',
                name: 'students-delete',
                component: require('./screens/students/delete').default,
                meta: {
                    resource: 'students',
                    createTitle: () => 'Delete Student',
                },
            },
            {
                path: 'create',
                name: 'students-create',
                component: require('./screens/students/create').default,
                meta: {
                    resource: 'students',
                    createTitle: () => 'Create Student',
                },
            },
            {
                path: ':id/edit',
                name: 'students-edit',
                component: require('./screens/students/edit').default,
                meta: {
                    resource: 'students',
                    createTitle: () => 'Edit Student',
                },
            },
        ]
    },
    {
        path: '/students/my-courses',
        name: 'students-my-courses',
        beforeEnter: checkAuth,
        component: require('./screens/students/my-courses').default,
        meta: {
            resource: 'students/my-courses/list',
            createTitle: () => 'My Courses',
        },
    },
    {
        path: '/students/enroll',
        name: 'students-enroll',
        beforeEnter: checkAuth,
        component: require('./screens/students/enroll').default,
        meta: {
            resource: 'students/',
            createTitle: () => 'Enroll',
        },
    },

    {
        path: '/instructors',
        name: 'instructors-index',
        beforeEnter: checkAuth,
        component: require('./screens/instructors/index').default,
        meta: {
            resource: 'instructors',
            createTitle: () => 'Instructors',
        },
        children: [
            {
                path: ':id/delete',
                name: 'instructors-delete',
                component: require('./screens/instructors/delete').default,
                meta: {
                    resource: 'instructors',
                    createTitle: () => 'Delete Instructor',
                },
            },
            {
                path: 'create',
                name: 'instructors-create',
                component: require('./screens/instructors/create').default,
                meta: {
                    resource: 'instructors',
                    createTitle: () => 'Create Instructor',
                },
            },
            {
                path: ':id/edit',
                name: 'instructors-edit',
                component: require('./screens/instructors/edit').default,
                meta: {
                    resource: 'instructors',
                    createTitle: () => 'Edit Instructor',
                },
            },
        ]
    },

    {
        path: '/sections',
        name: 'sections-index',
        beforeEnter: checkAuth,
        component: require('./screens/sections/index').default,
        meta: {
            resource: 'sections',
            createTitle: () => 'Sections',
        },
        children: [
            {
                path: ':id/delete',
                name: 'sections-delete',
                component: require('./screens/sections/delete').default,
                meta: {
                    resource: 'sections',
                    createTitle: () => 'Delete Section',
                },
            },
            {
                path: 'create',
                name: 'sections-create',
                component: require('./screens/sections/create').default,
                meta: {
                    resource: 'sections',
                    createTitle: () => 'Create Section',
                },
            },
            {
                path: ':id/edit',
                name: 'sections-edit',
                component: require('./screens/sections/edit').default,
                meta: {
                    resource: 'sections',
                    createTitle: () => 'Edit Section',
                },
            },
        ]
    },

    {
        path: '/unauthorized',
        component: require('./screens/403').default,
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
        component: require('./screens/404').default,
        meta: {
            createTitle: () => 'Login',
        },
    },
];
