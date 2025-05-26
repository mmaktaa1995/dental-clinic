<template>
    <c-container>
        <CAccordion :title="$t('roles.roleInfo')" :description="$t('roles.roleInfoDescription')">
            <div class="grid grid-cols-2 gap-6">
                <CTextField v-model="roleDetailsStore.entry.name" :label="$t('roles.name')" :errors="roleDetailsStore.errors" name="name" @update:model-value="updateSlug"></CTextField>
                <CTextField v-model="roleDetailsStore.entry.slug" :label="$t('roles.slug')" :errors="roleDetailsStore.errors" name="slug" disabled></CTextField>
            </div>
        </CAccordion>

        <CAccordion :title="$t('roles.permissionsAssignment')" :description="$t('roles.permissionsAssignmentDescription')">
            <div class="grid grid-cols-1 gap-6">
                <CMultiSelect 
                    v-model="roleDetailsStore.entry.permissions" 
                    :options="roleDetailsStore.availablePermissions" 
                    :label="$t('roles.permissions')" 
                    :errors="roleDetailsStore.errors" 
                    name="permissions"
                    :option-label="(item: { slug: string }) => $t(`permissions.${item.slug}`)"
                    option-value="id"
                ></CMultiSelect>
            </div>
        </CAccordion>
    </c-container>
</template>

<script setup lang="ts">
import { useRoleDetailsStore } from "@/modules/roles/detailStore"
import { useI18n } from "vue-i18n"
import { onMounted } from "vue"

const { t } = useI18n()
const roleDetailsStore = useRoleDetailsStore()

// Function to convert name to slug
const updateSlug = (name: string) => {
    // Convert to lowercase, replace spaces with hyphens, and remove special characters
    const slug = name
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '')
    
    roleDetailsStore.entry.slug = slug
}

onMounted(() => {
    roleDetailsStore.loadPermissions()
})
</script>
