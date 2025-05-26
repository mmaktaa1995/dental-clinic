import { useAccountStore } from '@/modules/auth/accountStore'
import type { Role, Permission } from '@/modules/auth/accountStore'

/**
 * Check if the current user has a specific permission
 * @param permission The permission slug to check
 * @returns boolean indicating if user has the permission
 */
export function hasPermission(permission: string): boolean {
  const accountStore = useAccountStore()
  
  // If user is not logged in, they have no permissions
  if (!accountStore.isLoggedIn || !accountStore.user) {
    return false
  }
  
  // If user is admin, they have all permissions
  if (accountStore.user.admin) {
    return true
  }
  
  // Check if user has the specific permission through their roles
  if (accountStore.user.roles && Array.isArray(accountStore.user.roles)) {
    return accountStore.user.roles.some((role: Role) => 
      role.permissions && Array.isArray(role.permissions) && 
      role.permissions.some((p: Permission) => p.slug === permission)
    )
  }
  
  return false
}

/**
 * Check if the current user has any of the specified permissions
 * @param permissions Array of permission slugs to check
 * @returns boolean indicating if user has any of the permissions
 */
export function hasAnyPermission(permissions: string[]): boolean {
  return permissions.some(permission => hasPermission(permission))
}

/**
 * Check if the current user has all of the specified permissions
 * @param permissions Array of permission slugs to check
 * @returns boolean indicating if user has all of the permissions
 */
export function hasAllPermissions(permissions: string[]): boolean {
  return permissions.every(permission => hasPermission(permission))
}
