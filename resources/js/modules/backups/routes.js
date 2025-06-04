export const getBackupsRoutes = () => [
  {
    path: '/backups',
    name: 'backups',
    component: () => import('@/modules/backups/views/Index.vue'),
    meta: {
      requiresAuth: true,
      requiresVerified: true,
      permission: 'manage-backups'
    }
  }
]
