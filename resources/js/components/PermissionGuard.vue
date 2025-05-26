<template>
  <slot v-if="hasAccess"></slot>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { hasPermission, hasAnyPermission, hasAllPermissions } from '@/utils/permissions'

export default defineComponent({
  name: 'PermissionGuard',
  props: {
    permission: {
      type: String,
      default: ''
    },
    anyPermissions: {
      type: Array as () => string[],
      default: () => []
    },
    allPermissions: {
      type: Array as () => string[],
      default: () => []
    }
  },
  computed: {
    hasAccess(): boolean {
      // Check if permission prop is provided
      if (this.permission) {
        console.log(this.permission, hasPermission(this.permission))
        return hasPermission(this.permission)
      }
      
      // Check if anyPermissions prop is provided
      if (this.anyPermissions.length > 0) {
        return hasAnyPermission(this.anyPermissions)
      }
      
      // Check if allPermissions prop is provided
      if (this.allPermissions.length > 0) {
        return hasAllPermissions(this.allPermissions)
      }
      
      // If no permissions are specified, allow access by default
      return true
    }
  }
})
</script>
